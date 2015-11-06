<?php
include "Dashboard.php";
class DashboardPayments extends Dashboard {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$data['user'] = $this->user;
		
		if ($this->config->item('stripe')) {
			//include ('path/to/stripe-php/lib/Stripe.php');
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/stripe-php-3.4.0/init.php');

			$stripe = array(
			  "secret_key"      => $this->config->item('stripe_secret_key'),
			  "publishable_key" => $this->config->item('stripe_publishable_key'),
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			$data['stripe'] = $stripe;
		}
		
		if ($this->config->item('braintree')) {
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/braintree-php-3.5.0/lib/Braintree.php');
			
			Braintree_Configuration::environment($this->config->item('braintree_environment'));
			Braintree_Configuration::merchantId($this->config->item('braintree_merchant_id'));
			Braintree_Configuration::publicKey($this->config->item('braintree_public_key'));
			Braintree_Configuration::privateKey($this->config->item('braintree_private_key'));
			
			$clientToken = Braintree_ClientToken::generate();
			$data['client_token'] = $clientToken;
		}		
		
		$this->load->view('dashboard/payments',$data);
	}
	
	public function checkout() {
		if ($this->config->item('stripe') && isset($_POST['stripeToken'])) {
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/stripe-php-3.4.0/init.php');

			$stripe = array(
			  "secret_key"      => $this->config->item('stripe_secret_key'),
			  "publishable_key" => $this->config->item('stripe_publishable_key'),
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			
			$token  = $_POST['stripeToken'];

			$customer = \Stripe\Customer::create(array(
				'email' => $this->user->user_email,
				'card'  => $token
			));

			$charge = \Stripe\Charge::create(array(
				'customer' => $customer->id,
				'amount'   => 1000,
				'currency' => 'usd'
			));
		}
		
		$res = $this->db->get_where('user',array('user_email' => $_SESSION['user_email']));
		
		// need to update user database. Add credit if charge was successful.

		
		if ($this->config->item('braintree') && isset($_POST["payment_method_nonce"])) {

			$nonce = $_POST["payment_method_nonce"];
			
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/braintree-php-3.5.0/lib/Braintree.php');
			Braintree_Configuration::environment($this->config->item('braintree_environment'));
			Braintree_Configuration::merchantId($this->config->item('braintree_merchant_id'));
			Braintree_Configuration::publicKey($this->config->item('braintree_public_key'));
			Braintree_Configuration::privateKey($this->config->item('braintree_private_key'));

			$result = Braintree_Transaction::sale(array(
				'amount' => '10.00',
				'paymentMethodNonce' => $nonce
			));
		}
		header('location: /dashboardpayments/confirmation/10');
	}
	
	public function confirmation($amt) {
		$data['user'] = $this->user;
		$data['amt'] = $amt;
		
		$this->load->view('dashboard/confirmation',$data);
		
	}	

}
