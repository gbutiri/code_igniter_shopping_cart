<?php $this->load->view('dashboard/header'); ?>
<?php $this->load->view('dashboard/navbar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Confirmation
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Confirmation</li>
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

					<div class="box-body">


						<p>Thank you for paying $<?php echo number_format($amt,2);?></p>


				
					</div>
				</div>

			</section><!-- /.Left col -->
			<!-- right col (We are only adding the ID to make the widgets sortable)-->
			<section class="col-lg-6 ">

				<!-- Map box -->
				<div class="box box-info">
					
				</div>
				<!-- /.box -->
				
			</section><!-- right col -->
		</div><!-- /.row (main row) -->

	</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php $this->load->view('dashboard/footer'); ?>
