<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index()
	{
		$more_js = '/pub';
		$data['more_js'] = ',/pub/js/page/index.js';
		$this->layouts->layout_one('shop/index', $data);
	}
	
	public function widgets()
	{
		$data = array();
		$widgets = explode(",",$this->input->post('widgets'));
		$ret = array();
		
		foreach ($widgets as $widget) {
			// $ret['#' . $widget] = 
			$ret['#' . $widget] = utf8_encode($this->load->view('shop/widgets/' . $widget, $data, true));
		}
		
		//$ret['#features_items'] = $this->load->view('shop/widgets/features_items', $data, true);
		//$ret['#footer'] = $this->load->view('shop/widgets/footer', $data, true);
		
		echo json_encode(
			array('htmls' => $ret)
			//'widgets' => $widgets
		);
		// echo json_encode(array("test" => "1"));
	}
	
	public function install() {
		if (INSTALLABLE) {
			$this->load->dbforge();
			
			if ($this->dbforge->drop_database(DBNAME)) {
				echo 'Database deleted!';
			}
			if ($this->dbforge->create_database(DBNAME)) {
				echo 'Database created!';
				
				$this->db->query('use ' . DBNAME);
				
				$this->dbforge->add_field("user_id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
				$this->dbforge->add_field("user_email VARCHAR(255)");
				$this->dbforge->add_field("user_password VARCHAR(255)");
				$this->dbforge->add_field("user_fname VARCHAR(40)");
				$this->dbforge->add_field("user_lname VARCHAR(40)");
				$this->dbforge->add_field("user_gender VARCHAR(10)");
				$this->dbforge->add_field("user_birthday DATE");
				$this->dbforge->add_field("user_phone VARCHAR(20)");
				$this->dbforge->add_field("user_address VARCHAR(100)");
				$this->dbforge->add_field("user_city VARCHAR(50)");
				$this->dbforge->add_field("user_region VARCHAR(50)");
				$this->dbforge->add_field("user_country VARCHAR(50)");
				$this->dbforge->add_field("user_zip VARCHAR(20)");
				$this->dbforge->add_field("user_photo VARCHAR(255)");
				$this->dbforge->add_field("user_provider VARCHAR(255)");
				$this->dbforge->add_field("user_provider_id VARCHAR(255)");
				$this->dbforge->add_field("user_created INT(10)");
				$this->dbforge->add_field("user_last_login INT(10)");
				$this->dbforge->add_field("user_stripe_customer_id VARCHAR(255)");
				$this->dbforge->add_field("user_bt_customer_id VARCHAR(255)");
				
				$this->dbforge->create_table('user', TRUE);
				
				
				$this->dbforge->add_field("user_id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
				$this->dbforge->add_field("user_email VARCHAR(255)");
				$this->dbforge->add_field("user_password VARCHAR(255)");
				$this->dbforge->add_field("user_fname VARCHAR(40)");
				$this->dbforge->add_field("user_lname VARCHAR(40)");
				$this->dbforge->add_field("user_photo VARCHAR(255)");
				$this->dbforge->add_field("user_provider VARCHAR(255)");
				$this->dbforge->add_field("user_provider_id VARCHAR(255)");
				$this->dbforge->add_field("user_created INT(10)");
				$this->dbforge->add_field("user_last_login INT(10)");
				$this->dbforge->add_field("user_verified TINYINT(1) DEFAULT 0");
				$this->dbforge->add_field("user_verification_code VARCHAR(255)");
				$this->dbforge->add_field("user_stripe_customer_id VARCHAR(255)");
				$this->dbforge->add_field("user_bt_customer_id VARCHAR(255)");
				
				$this->dbforge->create_table('merchant', TRUE);
				
				$this->dbforge->add_field("user_id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
				$this->dbforge->add_field("user_email VARCHAR(255)");
				$this->dbforge->add_field("user_password VARCHAR(255)");
				$this->dbforge->add_field("user_fname VARCHAR(40)");
				$this->dbforge->add_field("user_lname VARCHAR(40)");
				$this->dbforge->add_field("user_created INT(10)");
				$this->dbforge->add_field("user_last_login INT(10)");
				
				$this->dbforge->create_table('admin', TRUE);
			}
		}
	}
}
