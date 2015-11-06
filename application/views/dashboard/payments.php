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

					<div class="box-header">
						<h3>Add Credit</h3>
					</div>
					<div class="box-body">
						
						
						<?php if ($this->config->item('stripe')) { ?>
							<form action="/dashboardpayments/checkout" method="post">
								<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
									data-key="<?php echo $stripe['publishable_key']; ?>"
									data-description="Access for a year"
									data-amount="1000"
									data-locale="auto"></script>
							</form>
						<?php } ?>
						
						
						
					</div>
				</div>
				
				
				<div class="box box-info">

					<div class="box-header">
						<h3>Add Credit</h3>
					</div>
					<div class="box-body">
						
						
						<?php if ($this->config->item('braintree')) { ?>
							<form id="checkout" method="post" action="/dashboardpayments/checkout">
								<div id="payment-form"></div>
								<input type="submit" class="btn btn-lg btn-primary" value="Add $10">
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
