<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	protected $user;
	
	protected function authenticate() {
		$this->load->library("authex");
		//var_dump($this->authex->logged_in($this->session->user_email)); exit(0);
		if (!$this->authex->logged_in($this->session->user_email)) {
			header('location: /');
		} else {
			//if (isset($this->session->user_type)) {
			switch ($this->session->user_type) {
				case 'admin':
					$res = $this->db->get_where('admin',array('user_email' => $this->session->user_email));
					break;
				case 'merchant':
					$res = $this->db->get_where('merchant',array('user_email' => $this->session->user_email));
					break;
				case 'user':
					$res = $this->db->get_where('user',array('user_email' => $this->session->user_email));
					break;
			}
			
			$this->user = $res->row();
		}
		
	}

	public function __construct() {
		parent::__construct();
		$this->authenticate();
		
	}
	
	public function index() {
		$data['user'] = $this->user;
		$this->load->view('dashboard/index',$data);
	}
	
}
