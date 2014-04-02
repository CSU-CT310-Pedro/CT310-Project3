<?php 
	include 'phpHeader.php';
	
	session_name("User310");
	session_start();
	
	unset($_SESSION['user']);
	session_destroy();
	header('Location: index.php');
	exit;
?>

<?php
	include 'phpFooter.html'; 
?>