<?php 



// this page will logout the user by destroying the session and setting
// a cookie with time = the past.
	
	// session info
	$timeout = 3 * 60; // 3 minutes
	$fingerprint = md5('SECRET-SALT'.$_SERVER['HTTP_USER_AGENT']);
	session_start();

	if( (isset($_SESSION['lastActive'])) ) {
		if(time() > ($_SESSION['lastActive']+$timeout)) {
			header("Location:logout.php");
		}
		if(isset($_SESSION['fingerprint']) && $_SESSION['fingerprint']!=$fingerprint)
			header("Location:logout.php");
	}
	session_regenerate_id(); 
	$_SESSION['lastActive'] = time();
	$_SESSION['fingerprint'] = $fingerprint;


	if(!isset($_SESSION['user']))
		include("logout.php");	

	if(isset($_SESSION['user'])) {		// check if user has logged in
		$_SESSION = array();
		session_destroy();
		setcookie("PHPSESSID", "", time() - 61200,"/");									// if user not logged in send to login
		header("Location:a2login.php");
	}	
	else
		header("Location:a2login.php");
?>