<?php
	
	$api_path = str_replace('check_auth.php', '', __FILE__);
	include_once($api_path.'config.php');
	//include_once($api_path.'taosdk/TopSdk.php');
	
	//$c = new TopClient;
	//$c->appkey = $appKey;
	//$c->secretKey = $appSecret;
	$top_session = isset($_SESSION['taoapi']['top_session']) ? $_SESSION['taoapi']['top_session'] : '';
	
	if (!empty($_SERVER['QUERY_STRING']))
		$ref = urlencode($_SERVER['SCRIPT_NAME'].'?'.htmlspecialchars_decode($_SERVER['QUERY_STRING']));
	else
		$ref = urlencode($_SERVER['SCRIPT_NAME']);
	
	if(empty($top_session))
	{
		//header('Location: '.$sessionurl.$appKey.'&ref='.$ref);
		header("Content-Type:text/html;charset=UTF-8");
		echo "淘宝授权没登录，<a href='javascript:;' onclick=\"window.open('{$sessionurl}{$appKey}&ref={$ref}')\">请点此登录</a>";
		exit;
	}
?>