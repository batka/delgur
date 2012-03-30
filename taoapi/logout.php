<?php
	session_start();
	unset($_SESSION['taoapi']);
	
	$ref = urldecode($_GET['ref']);
	header('Location: '.$ref);
?>