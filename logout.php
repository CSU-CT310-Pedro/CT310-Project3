<?php 
	include 'phpHeader.php';
	
	//session_name("User310");
	session_start();
	
	unset($_SESSION['user']);
	session_destroy();//I have no idea why but this is not actually logging out the user
	header('Location: index.php');
	exit;
?>

<?php
	include 'phpFooter.html'; 
?>