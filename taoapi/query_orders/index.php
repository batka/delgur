<?php
	session_start();
	header("Content-Type:text/html;charset=UTF-8");
	require_once('../config.php');
	include_once dirname(__FILE__).'../lib/function.php';
	include_once('../model/opencart.php');
?>	
<html>
<head>
<title></title>
</head>
<body>
<p></p>

<p id="msgs" style="height:24px;">
<?php include 'header.php';?>
</p>
<?php
	/* Build By fhalipay */
	
/* 得到当前会话用户出售中的商品列表 Start*/

	//参数数组
	$paramArr = array(


		/* API系统级输入参数 Start */

	    	'method' => 'taobao.trades.bought.get',  //API名称
		   'session' => $sessionKey, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

			'fields' => 'seller_nick,consign_time,tid',
	       'status'  => 'WAIT_BUYER_CONFIRM_GOODS',  
	       'page_no' => 1, //页码。取值范围:大于零的整数;默认值为1，即返回第一页数据。 
		 'page_size' => 10, //每页条数。取值范围:大于零的整数;最大值：200；默认值：40。
				
		/* API应用级输入参数 End*/
	);
	
	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$urls = $api_url.$strParam;
	
	//连接超时自动重试
	$cnt=0;	
	while($cnt < 3 && ($result=@vita_get_url_content($urls))===FALSE) $cnt++;
	
	//解析Xml数据
	$result = getXmlData($result);

	if (empty($result)){
		echo '没有订单数据返回';
		exit;
	}else{
	//获取错误信息
		$msg = $result['msg'];
	
	}
	if ( ! empty($result['trades']['trade']))
	{		
		$DBCtrl = new DBCtrlModel();
		$DBCtrl->update_order($result['trades']['trade']);
	}
/* 得到当前会话用户出售中的商品列表 End*/	

	
?>


