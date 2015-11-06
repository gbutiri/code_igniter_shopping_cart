<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

Class Layouts {
	
	public function layout_one($current_view, $data = array('more_js' => '')) {
		//$more_js = $data['more_js'];
		$CI =& get_instance();
		
		$CI->load->helper('output');
		ob_start("rmspace");
		
		$CI->load->view('shop/header',$data);
		$CI->load->view('shop/left_nav',$data);
		$CI->load->view($current_view,$data);
		$CI->load->view('shop/footer',$data);
		
		ob_end_flush();
	}
	

}