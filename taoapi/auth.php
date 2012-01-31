<?php

	error_reporting(0);
	session_start();
	
	include 'config.php';
	
	$ref            = urldecode($_GET['ref']);
	$top_appkey     = $_GET['top_appkey']; 
	$top_parameters = $_GET['top_parameters']; 
	$top_session    = $_GET['top_session']; 
	$top_sign       = $_GET['top_sign']; 
	
	$md5  = md5( $top_appkey . $top_parameters . $top_session . $appSecret, true ); 
	$sign = base64_encode( $md5 ); 

	if ( $sign != $top_sign )
	{
		echo "Signature Invalid."; 
		exit(); 
	}
	
	$parameters = array(); 
	parse_str( base64_decode( $top_parameters ), $parameters ); 
	$now = time(); 
	$ts = $parameters['ts'] / 1000; 
	if ( $ts > ( $now + 60 * 10 ) || $now > ( $ts + 60 * 30 ) )
	{ 
		echo "Request out of date."; 
		exit(); 
	}
	
	$_SESSION['taoapi']['top_appkey']  = $top_appkey;
	$_SESSION['taoapi']['top_sign']    = $top_sign;
	$_SESSION['taoapi']['top_session'] = $top_session;
	$_SESSION['taoapi']['parameters']  = $parameters;
	$_SESSION['taoapi']['nick']        = iconv('GBK', 'utf-8', $parameters['visitor_nick']);
	
	header('Location: '.$ref);
?>