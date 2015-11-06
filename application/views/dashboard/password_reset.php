<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>AdminLTE 2 | Log in</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		<!-- Theme style -->
		<link rel="stylesheet" href="/combiner/combine/css/pub/bootstrap/css/bootstrap.min.css,/pub/dist/css/AdminLTE.min.css,/pub/plugins/iCheck/square/blue.css,/pub/css/ajax-controller.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		
		<!-- jQuery 2.1.4 -->
		<script src="/pub/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<!-- Bootstrap 3.3.5 -->
		<script src="/combiner/combine/javascript/pub/bootstrap/js/bootstrap.min.js,/pub/js/dispatch.js,/pub/plugins/iCheck/icheck.min.js"></script>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="/"><b>CI</b>Core</a>
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Set a password</p>
				<form action="#" class="ajaxform" method="post" data-module="merchant" data-action="verify_password">
					<div class="form-group has-feedback">
						<input type="password" name="password" class="form-control" placeholder="New Password">
						<span class="fa fa-lock form-control-feedback"></span>
						<div class="err" id="err_password"></div>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="password2" class="form-control" placeholder="Confirm New Password">
						<span class="fa fa-lock form-control-feedback"></span>
						<div class="err" id="err_password2"></div>
					</div>
					<div class="row">
						<div class="col-xs-8">

						</div><!-- /.col -->
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
						</div><!-- /.col -->
					</div>
					<input type="hidden" name="email" value="<?php echo $email; ?>" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
				</form>

			</div><!-- /.login-box-body -->
		</div><!-- /.login-box -->

		<script>
		  $(function () {
			$('input').iCheck({
			  checkboxClass: 'icheckbox_square-blue',
			  radioClass: 'iradio_square-blue',
			  increaseArea: '20%' // optional
			});
		  });
		</script>
	</body>
</html>
