<?php
	$api_path = str_replace(array('category\index.php', 'category/index.php'), '', __FILE__);
	include_once($api_path.'config.php');
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/page.Class.php');
	
	header("Content-Type:text/html;charset=UTF-8");
//	include_once($api_path.'check_auth.php');
	/* Build By fhalipay */
	
	$parent_cid    = empty($_REQUEST['parent_cid'])   ? '0' : intval($_REQUEST['parent_cid']);
	$parent_name    = empty($_REQUEST['parent_name'])   ? '' : $_REQUEST['parent_name'];
	
  	$paramArr = array();
  	
	/* 获取指定类目或指定关键字淘宝商品列表 Start*/
	
	
	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

			'method' => 'taobao.itemcats.get',  //API名称
		 'timestamp' => date('Y-m-d H:i:s'),			
			'format' => 'xml',  //返回格式,本demo仅支持xml
		   'app_key' => $appKey,  //Appkey			
				 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

			'fields' => 'cid,parent_cid,name,is_parent',  //返回字段
		'parent_cid' => $parent_cid,         //商品所属分类id
		/* API应用级输入参数 End*/
	);
	
	$sign = createSign($paramArr, $appSecret);	//生成签名
	
	$strParam  = createStrParam($paramArr);	//组织参数		
	$strParam .= 'sign=' . $sign . '&app_key=' . $appKey;
	$api_url   = $api_url.$strParam; //构造Url		
	$cnt = 0;	//连接超时自动重试
	while($cnt < 3 && ($result = @vita_get_url_content($api_url)) === FALSE) $cnt++;
	
	//解析Xml数据
	$result = getXmlData($result);
	
		//var_export($result);die();
	if (!empty($result))
	{
		//返回结果
		if (is_array($result['item_cats']['item_cat']))
		{
			$Categories = $result['item_cats']['item_cat'];
		}
	}
	/* 获取指定类目或指定关键字淘宝客商品列表 End*/	
	include_once('categories.php');
?>