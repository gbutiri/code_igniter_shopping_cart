<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends CI_Controller {
	
	public function index()
	{
		//$this->load->view('front-page/index.html');
	}
	
	public function show_login() {
		//$this->output->set_header('Content-Type: application/json; charset=utf-8'); 
		$data = array();
		
		$popup = $this->load->view('front-page/merchant',$data,true);
		
		echo json_encode(array(
			'vbox' => $popup
		));
	}
	
	public function do_login() {
		$existing_user = $this->db->get_where('merchant',array(
			'user_email' => $this->input->post('email')
		))->row();
		
		if ($existing_user) {
			
			if ($existing_user->user_verified) {
			
				$right_pw = $this->db->get_where('merchant',array(
					'user_email' => $this->input->post('email'),
					'user_password' => md5($this->input->post('password')),
				))->row();
				
				if ($right_pw) {
					$this->session->user_email = $this->input->post('email');
					$this->session->user_type = 'merchant';
					
					echo json_encode(array(
						'htmls' => array('.vbox-content' => '<p>Logging you in!</p>'),
						'redirect' => '/dashboard',
					));
				} else {
					echo json_encode(array(
						'htmls' => array(
							//'#form-feedback' => '<p class="bg-danger message-padding">Error Logging In. <a href="#">Sign up?</a></p>',
							'#err_password' => 'Incorrect password.',
						)
					));
				}
			
			} else {
				echo json_encode(array(
					'htmls' => array(
						'#err_email' => 'Email not verified.',
					)
				));
			}
			
			
			
		} else {
			echo json_encode(array(
				'htmls' => array(
					//'#form-feedback' => '<p class="bg-danger message-padding">Error Logging In. <a href="#">Sign up?</a></p>',
					'#err_email' => 'Email not registered. Consider signing up.',
				),
			));
		}
	}
	
	public function do_signup() {
		$htmls = array();
		
		$existing_user = $this->db->get_where('merchant',array(
			'user_email' => $this->input->post('email_signup')
		))->row();
		
		if ($existing_user) {
			$htmls['#err_email_signup'] = 'User already exists, please sign in!';
			
			echo json_encode(array(
				'htmls' => $htmls,
			));
		} else {
			$this->load->helper('string');
			$this->load->helper('url');
			
			$data['merchant_email'] = $this->input->post('email_signup');
			$data['new_password'] = random_string('alnum', 8);
			$data['new_passwrod_md5'] = md5($data['new_password']);
			$data['validation_link'] = random_string('alnum', 128);
			$data['validation_url'] = base_url("merchant/validate_email/" . urlencode($this->input->post('email_signup')) . '/' . $data['validation_link']);
			
			// create new merchant acount in DB
			
			$email_body = $this->load->view('emails/merchant_signup',$data,true);
			
			//$this->load->library('email');
			$this->email->set_mailtype("html");
			$this->email->from('no-reply@cicore.com');
			$this->email->to($this->input->post('email_signup'), 'New User');
			$this->email->subject('Your new registration with cicore.');
			$this->email->message($email_body);

			if ( $this->email->send() ) {
				$this->db->insert('merchant', array(
					'user_email' => $data['merchant_email'],
					'user_password' => $data['new_passwrod_md5'],
					'user_created' => time(),
					'user_last_login' => null,
					'user_verified' => 0,
					'user_verification_code' => $data['validation_link']
				));
				echo json_encode(array(
					'closevbox' => true,
					'post' => $_POST,
				));
				
			} else {
				echo json_encode(array(
					'htmls' => array(
						'#email_signup' => 'Unable to send email invite.'
					),
				));
			}
			
		}
		
	}
	
	public function validate_email($merchant_email, $validation_code) {
		$account = $this->db->get_where('merchant',array(
			'user_email' => urldecode($merchant_email),
			'user_verification_code' => $validation_code,
		))->row();
		
		$data['email'] = urldecode($merchant_email);
		$data['token'] = $validation_code;
		
		if ($account) {
			$this->load->view('dashboard/password_reset', $data);
		} else {
			$this->load->view('dashboard/password_reset_fail');
		}
		
		//var_dump(urldecode($merchant_email), $validation_code, $account); exit(0);
	}
	
	public function verify_password() {
		$this->load->helper('password');
		$errors = array();
		
		$password_error = passwordError($this->input->post('password'));
		if ($password_error) {
			$errors['#err_password'] =  $password_error;
		}
		
		if ($this->input->post('password') != $this->input->post('password2')) {
			$errors['#err_password2'] =  'Passwords Don\'t Match.';
		}
		
		if (count($errors) == 0) {
			$this->db->update('merchant',array(
				'user_password' => md5($this->input->post('password')),
				'user_verified' => 1,
			),array(
				'user_email' => $this->input->post('email'),
				'user_verification_code' => $this->input->post('token'),
			));
			
			$this->session->user_email = $this->input->post('email');
			$this->session->user_type = 'merchant';
			
			echo json_encode(array(
				'redirect' => '/dashboard',
			));
		} else {
			
			echo json_encode(array(
				'htmls' => $errors,
			));
			
		}
		
	}
}
