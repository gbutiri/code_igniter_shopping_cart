<div class="social-auth-links text-center">
	<legend>User Sign In</legend>
	<!--
	<p>- OR -</p>
	-->
	<?php 
	//var_dump($logins);
	foreach ($logins['providers'] as $key => $login) {
		if ($login['enabled']) { 
			//var_dump($key, $login);
			?>
			<a href="/user/social_login/<?php echo strtolower($key); ?>" class="btn btn-block btn-social btn-<?php echo strtolower($key); ?> btn-flat"><i class="fa fa-<?php echo strtolower($key); ?>"></i> Sign in using <?php echo $key; ?></a>
			<?php
		}
	}
	?>
</div>
