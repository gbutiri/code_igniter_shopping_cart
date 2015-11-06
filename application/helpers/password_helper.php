<?php 
function passwordError ($pwd) {
	$error="";
	if( strlen($pwd) < 8 ) {$error = "Password too short. Must be at least 8 characters.";}
	elseif( strlen($pwd) > 20 ) {$error = "Password too long. Must be no longer than 20 characters.";}
	elseif( !preg_match("#[0-9]+#", $pwd) ) {$error = "Password must include at least one number!";}
	elseif( !preg_match("#[a-zA-Z]+#", $pwd) ) {$error = "Password must include at least one letter!";}
	//elseif( !preg_match("#[a-z]+#", $pwd) ) {$error = "Password must include at least one lowercase letter!";}
	//elseif( !preg_match("#[A-Z]+#", $pwd) ) {$error = "Password must include at least one uppercase letter!";}
	//elseif( !preg_match("#\W+#", $pwd) ) {$error = "Password must include at least one symbol!";}
	if($error!=""){
		return $error;
	} else {
		return false;
	}
}
?>