<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authex {
	
	function Authex() {
		
	}

	function get_userdata() {
		
	}

	function logged_in() {
		return (isset($_SESSION['user_email']) && $_SESSION['user_email'] != '');
	}


	function login($email, $password) {
		
	}

	function logout() {
		$_SESSION = null;
		session_destroy();
		header('location: /');
	}

	function register($email, $password) {
		
	}

	function can_register($email) {
		
	}
	
	public function hybrid_auth() {
		include ($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/hybridauth-php-2.5.0/hybridauth/index.php');
	}
}