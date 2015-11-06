<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function index() {
		$this->load->view('login');
	}
	
	public function logged_in() {
		return true;
	}
	
	public function show_login() {
		$data['logins'] = array();
		
		$file = $_SERVER['DOCUMENT_ROOT'] . '/application/third_party/hybridauth-php-2.5.0/hybridauth/config.php';
		if (is_file($file)) {
			$data['logins'] = include $file;
		}
		
		$view = $this->load->view('login',$data,true);
		
		echo json_encode(array(
			'vbox' => $view
		));
	}

	public function hybrid_auth() {
		include ($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/hybridauth-php-2.5.0/hybridauth/index.php');
	}
	
	public function social_login($provider_name) {
		
		//$provider_name = $_REQUEST["provider"];
		
		require_once( $_SERVER['DOCUMENT_ROOT'] . "/application/third_party/hybridauth-php-2.5.0/hybridauth/Hybrid/Auth.php" );
		$config_file_path = $_SERVER['DOCUMENT_ROOT'] . '/application/third_party/hybridauth-php-2.5.0/hybridauth/config.php';
		$hybridauth = new Hybrid_Auth( $config_file_path );
		//var_dump($hybridauth->getAdapter('Google'));
		//exit(0);

		$adapter = $hybridauth->authenticate( $provider_name );
		
		// then grab the user profile
		$user_profile = $adapter->getUserProfile();
		
		// TODO -> Refactor to check if user exists based on email...
		//$user_exist = $this->get_user_by_provider_and_id( $provider_name, $user_profile->identifier );
		$user = $this->get_user_by_email( $user_profile->email );
		
		//var_dump($user_exist); exit(0);
		
		if( empty($user) ) {
			// email not registered. Search for provider info.
			
			$user = $this->get_user_by_provider_and_id( $provider_name, $user_profile->identifier );
			if (empty($user)) {
				// Neither email nor provider exist in the database.
				// Create new record with ALL info. (insert into).
				$user_email = $user_profile->email;
				
				$data = array(
					'user_email' => strtolower($user_profile->email),
					'user_password' => md5(rand(100000,999999) . time()),
					'user_fname' => $user_profile->firstName,
					'user_lname' => $user_profile->lastName,
					'user_gender' => $user_profile->gender,
					'user_birthday' => date("Y-m-d", strtotime($user_profile->birthMonth . '/' . $user_profile->birthDay . '/' . $user_profile->birthYear )),
					'user_phone' => $user_profile->phone,
					'user_address' => $user_profile->address,
					'user_city' => $user_profile->city,
					'user_region' => $user_profile->region,
					'user_country' => $user_profile->country,
					'user_zip' => $user_profile->zip,
					'user_photo' => $user_profile->photoURL,
					'user_provider' => $provider_name,
					'user_provider_id' => $user_profile->identifier,
					'user_created' => time(),
					'user_last_login' => time(),
				);
				$this->db->insert('user',$data);
				
			} else {
				// User exists by provider.
				// Update all info (email, provider, provider id.)
				// set session data
				$user_email = $user_profile->email;
			}
			
		} else {
			// email registered. UPDATE record with credentials. (email, provider, provider id)
			$user_email = $user->user_email;
			
			$data =  array(
				'user_provider' => $provider_name,
				'user_provider_id' => $user_profile->identifier,
				'user_password' => md5(rand(100000,999999) . time()),
				'user_last_login' => time(),
			);
			$this->db->update('user',$data,array('user_email' => strtolower($user_email)));
			
		}
		
		// -> TODO <- \\
		// set cookies and sessions.
		// redirect to dashboard.
		$this->session->user_email = $user_email;
		$this->session->user_type = 'user';

		header('location: /dashboard');
	}
	
	private function get_user_by_provider_and_id( $provider_name, $provider_user_id ) {
		
		$user = $this->db->get_where('user',array(
			'user_provider' => $provider_name,
			'user_provider_id' => $provider_user_id,
		));
		
		return $user->first_row();
	}
	
	public function logout() {
		$this->session->sess_destroy();
		header('location: /');
		
	}

	private function get_user_by_email($email) {
		
		$user = $this->db->get_where('user',array(
			'user_email' => strtolower($email)
		));
		
		return $user->first_row();
	}
		
}
