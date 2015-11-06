<?php $this->load->view('dashboard/header'); ?>
<?php $this->load->view('dashboard/navbar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Payments
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Payments</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
	
		<!-- Main row -->
		<div class="row">
			<!-- Left col -->
			<section class="col-lg-6">

				<!-- quick email widget -->
				<div class="box box-info">
					
					<?php 
					$show_form = true;
					if (!empty($user->user_stripe_customer_id)) {
						$stripe_customer = \Stripe\Customer::retrieve($user->user_stripe_customer_id);
						$stripe_subscriptions = $stripe_customer->subscriptions;
						if ($stripe_subscriptions->total_count > 0) {
							$show_form = false;
						}
					}
					if (!$show_form) {
						?>
						
						<div class="box-header">
							<h3>Subscriptions</h3>
						</div>
						<div class="box-body">
							<?php
							if ($stripe_subscriptions->total_count > 0) {
								?><table class="table"><?php
								//count($subscriptions->0);
								foreach ($stripe_subscriptions->data as $subscription) {
									?>
									<tr>
										<td>
											<?php echo $subscription->plan->name; ?>
										</td>
										<td>
											$<?php echo number_format($subscription->plan->amount / 100,2); ?>
										</td>
										<td>
											<a class="btn btn-danger" href="/dashboardsubscriptions/cancel_stripe_subscription/<?php echo $subscription->id; ?>">Cancel Plan</a>
										</td>
									</tr>
									<?php 
								}
								?></table><?php
							}
							?>
						</div>
						<?php 
					} else {
						?>
						<div class="box-header">
							<h3>Subscribe</h3>
						</div>
						<div class="box-body">
							<?php if ($this->config->item('stripe')) { ?>
								<form action="/dashboardsubscriptions/subscription_checkout" method="post">
									<table class="table">
									<tr>
									<td>
										LM-BETA
									</td>
									<td>
										$325
									</td>
									<td>
										<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
											data-key="<?php echo $this->config->item('stripe_publishable_key'); ?>"
											data-description="LM-BETA"
											data-amount="32500"
											data-locale="auto"></script>
									</td>
									</tr>
									</table>
								</form>
							<?php } ?>
						
						</div>
						<?php
					}
					?>
					
				</div>
				
				
				<div class="box box-info">
					
					<?php 
					
					$show_form = true;
					if (!empty($user->user_bt_customer_id)) {
						$customer = Braintree_Customer::find($user->user_bt_customer_id);
						
						if (count($customer->paymentMethods) > 0) {
							// there are payments
							foreach ($customer->paymentMethods as $paymentMethod) {
								if (count($paymentMethod->subscriptions) > 0) {
									$show_form = true;
									foreach ($paymentMethod->subscriptions as $subscription) {
										if ($subscription->status == 'Active') {
											$show_form = false;
										}
									}
								} else {
									$show_form = true;
								}
							}
						} else {
							$show_form = true;
						}
					}
					
					if (!$show_form) {
						?><table class="table"><?php
						foreach ($customer->paymentMethods as $paymentMethod) {
							//var_dump($paymentMethod->subscriptions); exit(0);
							foreach ($paymentMethod->subscriptions as $subscription) {
								// var_dump($subscription);
								?>
								<tr>
									<td><?php echo $subscription->planId; ?></td>
									<td>$<?php echo $subscription->price; ?></td>
									<td><?php echo $subscription->status; ?></td>
									<td>
										<?php 
										if ($subscription->status != 'Canceled') { 
											?>
											<a href="/dashboardsubscriptions/cancel_bt_subscription/<?php echo $subscription->id; ?>" class="btn btn-danger">
												Cancel Plan
											</a>
											<?php 
										} 
										?>
									</td>
								</tr>
								<?php
							}
						}
						?></table><?php
					} else {
					
					
						?>
						
						<div class="box-header">
							<h3>Subscribe</h3>
						</div>
						<div class="box-body">
							
							
							<?php if ($this->config->item('braintree')) { ?>
								<form id="checkout" method="post" action="/dashboardsubscriptions/subscription_checkout">
									<div id="payment-form"></div>
									<input type="submit" class="btn btn-lg btn-primary" value="Subscribe">
								</form>

								<script src="https://js.braintreegateway.com/v2/braintree.js"></script>

								<script>
									braintree.setup(
										// Replace this with a client token from your server
										"<?php echo $client_token; ?>",
										"dropin", {
											container: "payment-form"
										}
									);
								</script>
							<?php } ?>
							


					
						</div>
						<?php 
					}
					?>
				</div>

			</section><!-- /.Left col -->
			<!-- right col (We are only adding the ID to make the widgets sortable)-->
			<section class="col-lg-6 ">

				<!-- Map box -->
				<div class="box box-info">
					<div class="box-header">
						<h3>Payment History</h3>
					</div>
					<div class="box-body">
					</div>
					
				</div>
				<!-- /.box -->
				
			</section><!-- right col -->
		</div><!-- /.row (main row) -->

	</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php $this->load->view('dashboard/footer'); ?>
