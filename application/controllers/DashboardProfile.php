<?php
include "Dashboard.php";
class DashboardProfile extends Dashboard {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$data['user'] = $this->user;
		$this->load->view('dashboard/profile',$data);
	}
}
