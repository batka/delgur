<?
	header("Content-Type:text/html;charset=UTF-8");
	$api_path = str_replace(array('search_product\down_attrs.php', 'search_product/down_attrs.php'), '', __FILE__);
	$root_path = str_replace(array('taoapi\search_product\down_attrs.php', 'taoapi/search_product/down_attrs.php'), '', __FILE__);
	include_once($api_path.'config.php'); // the config is taobao api config
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/translator.php');
	include_once($api_path.'model/opencart.php');
	
	
	
	
	
	$Translator = new Translator;
	
	
	$DBCtrl = new DBCtrlModel();

  	$num_iid = empty($_REQUEST['num_iid']) ? 0 : $_REQUEST['num_iid'];
	
	
/* 获取淘宝商品详情 Start */	

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

		    'method' => 'taobao.itemprops.get',   //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式
		'partner_id' => 'top-apitools',

		/* API系统级参数 End */			

		/* API应用级输入参数 Start*/

			'fields' => 'pid,name,must,multi,prop_values,is_color_prop,is_sale_prop', //返回字段
	      'cid' => 1512, //Num_iid
	
		/* API应用级输入参数 End*/	
	);
	
	$sign = createSign($paramArr, $appSecret);	//生成签名
	
	$strParam  = createStrParam($paramArr);	//组织参数		
	$strParam .= 'sign=' . $sign . '&app_key=' . $appKey;
	$api_url   = $api_url.$strParam; //构造Url
	
	//连接超时自动重试
	$cnt = 0;	
	while($cnt < 3 && ($result = @vita_get_url_content($api_url)) === FALSE) $cnt++;
	
	
	$Translator->setText($result);					
	$result  = $Translator->translate('zh-CN', 'en');
	$result = getXmlData($result);//解析Xml数据
	print_r($result);
/* 获取淘宝商品详情 End*/

	function split_attrs_options($props, $skus)
	{
		$aProps   = explode(';', $props);
		$aOptions = array();
		
		if (count($skus['sku']) > 0)
		{
			foreach($skus['sku'] as $sku)
			{
				$aOption = explode(';', $sku['properties']);
				$aOptions = array_merge($aOptions, $aOption);
				
				foreach($aOption as $option)
				{
					unset($aProps[array_search($option, $aProps)]);
				}
			}
		}
		$aOptions = array_unique($aOptions);
		return array($aProps, $aOptions);
	}
?>
