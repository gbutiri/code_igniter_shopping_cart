<?php
include "Dashboard.php";
class DashboardSubscriptions extends Dashboard {
	
	protected $user;
	
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
			$data['user'] = $this->user;
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
		
		$this->load->view('dashboard/subscriptions',$data);
	}
	
	public function subscription_checkout() {
		
		if ($this->config->item('stripe') && isset($_POST['stripeToken'])) {
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/stripe-php-3.4.0/init.php');

			$stripe = array(
			  "secret_key"      => $this->config->item('stripe_secret_key'),
			  "publishable_key" => $this->config->item('stripe_publishable_key'),
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			
			$token  = $_POST['stripeToken'];

			$customer = \Stripe\Customer::create(array(
				'email' => 'customer@example.com',
				'plan'  => 'LM-BETA',
				'source'  => $token,
			));
			
			$this->db->update(
				'user',
				array(
					'user_stripe_customer_id' => $customer['id'],
				),
				array(
					'user_email' => $this->user->user_email
				)
			);

		}
		
		
		if ($this->config->item('braintree') && isset($_POST["payment_method_nonce"])) {
			
			$nonce = $_POST["payment_method_nonce"];
			
			$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/braintree-php-3.5.0/lib/Braintree.php');
			Braintree_Configuration::environment($this->config->item('braintree_environment'));
			Braintree_Configuration::merchantId($this->config->item('braintree_merchant_id'));
			Braintree_Configuration::publicKey($this->config->item('braintree_public_key'));
			Braintree_Configuration::privateKey($this->config->item('braintree_private_key'));
			
			$customer = Braintree_Customer::create([
				'firstName' => $this->user->user_fname,
				'lastName' => $this->user->user_lname,
				'email' => $this->user->user_email,
				'paymentMethodNonce' => $nonce,
			]);
			
			$this->db->update(
				'user',
				array(
					'user_bt_customer_id' => $customer->customer->id,
				),
				array(
					'user_email' => $this->user->user_email
				)
			);
					
			$result = Braintree_Subscription::create([
				'paymentMethodToken' => $customer->customer->paymentMethods[0]->token,
				'planId' => 'LM-BETA'
			]);
		}
		header('location: /dashboardsubscriptions');
	}
	
	public function cancel_stripe_subscription($plan_id) {
		$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/stripe-php-3.4.0/init.php');
		
		$stripe = array(
			  "secret_key"      => $this->config->item('stripe_secret_key'),
			  "publishable_key" => $this->config->item('stripe_publishable_key'),
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		
		$customer = \Stripe\Customer::retrieve($this->user->user_stripe_customer_id);
		
		$subscription = $customer->subscriptions->retrieve($plan_id);
		$subscription->cancel();
		
		
		header('location: /dashboardsubscriptions');
		
	}

	public function cancel_bt_subscription($plan_id) {
	
		$this->load->file($_SERVER['DOCUMENT_ROOT'] . '/application/third_party/braintree-php-3.5.0/lib/Braintree.php');
		Braintree_Configuration::environment($this->config->item('braintree_environment'));
		Braintree_Configuration::merchantId($this->config->item('braintree_merchant_id'));
		Braintree_Configuration::publicKey($this->config->item('braintree_public_key'));
		Braintree_Configuration::privateKey($this->config->item('braintree_private_key'));
	
		$res = Braintree_Subscription::cancel($plan_id);
		
		header('location: /dashboardsubscriptions');
	}
}
