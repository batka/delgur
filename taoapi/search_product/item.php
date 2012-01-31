<?
	header("Content-Type:text/html;charset=UTF-8");
	$api_path = str_replace(array('search_product\item.php', 'search_product/item.php'), '', __FILE__);
	$root_path = str_replace(array('taoapi\search_product\item.php', 'taoapi/search_product/item.php'), '', __FILE__);
	include_once($api_path.'config.php'); // the config is taobao api config
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/translator.php');
	include_once($api_path.'model/opencart.php');
	
	$DBCtrl = new DBCtrlModel();
	$languages = $DBCtrl->getLanguages();
	print_r($languages);die();
  	$num_iid = empty($_REQUEST['num_iid']) ? 0 : $_REQUEST['num_iid'];
  	$score   = empty($_REQUEST['score']) ? 0 : $_REQUEST['score'];
  	$category_id   = empty($_REQUEST['category_id'])  ? 0 : intval($_REQUEST['category_id']);
	
	if ($num_iid == 0)
	{
		echo "商品ID不能正确，不能为空或者0";
		exit;
	}
	
	if ($DBCtrl->checkItemIsDownload($num_iid))
	{
		//echo "该商品已经下载过，不用再下载了。";
		//exit;
	}
	
	mkdir($root_path.'image/data/'.$num_iid, 0777);
	
/* 获取淘宝商品详情 Start */	

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

		    'method' => 'taobao.item.get',   //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式
		'partner_id' => 'top-apitools',

		/* API系统级参数 End */			

		/* API应用级输入参数 Start*/

			'fields' => 'detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,score,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual', //返回字段
	      'num_iid' => $num_iid, //Num_iid
	
		/* API应用级输入参数 End*/	
	);
	
	$sign = createSign($paramArr, $appSecret);	//生成签名
	
	$strParam  = createStrParam($paramArr);	//组织参数		
	$strParam .= 'sign=' . $sign . '&app_key=' . $appKey;
	$api_url   = $api_url.$strParam; //构造Url
	
	//连接超时自动重试
	$cnt = 0;	
	while($cnt < 3 && ($result = @vita_get_url_content($api_url)) === FALSE) $cnt++;
	
	
	$result = getXmlData($result);//解析Xml数据
	
	$Item = $result['item']; //返回结果
	//unset($Item['desc']);
	//print_r($Item);die();
	if (empty($Item) || !isset($Item['title']))
	{
		die('产品下载失败，请重试或者选其它产品！');
	}
	else
	{
		$languages = $DBCtrl->getLanguages();
		$store_config = $DBCtrl->getSettingByGroup('config');
		
		$DBCtrl->saveTaobaoShop(array('nick' => $Item['nick'], 'score' => $score));

		$Product = array(
					'model'           => $Item['num_iid'],  //商品型号 淘宝商品id
					'location'        => $Item['location']['state'].' '.$Item['location']['city'],			 //商品所在地
					'quantity'        => 999,			 //商品初始数量
					'stock_status_id' => $store_config['config_stock_status_id'],        //库存状态
					'image'           => $Item['pic_url'],
					'manufacturer_id' => 0,		 //品牌制造商
					'shipping'        => 0,             
					'price'           => $Item['price']*1.15,
					'price_cost'      => $Item['price'],
					'tax_class_id'    => 0,           // 税种设定
					'subtract'        => 0,
					'status'          => 1,           //上下架
					'weight' 		  => 0,
					'weight_class_id' => 0,
					'length'		  => 0,
					'width'			  => 0,
					'height' 		  => 0,
					'length_class_id' => 0,
					'minimum'         => 0,
					'sort_order'      => 0,
					'points'          => 0,
					'sku'			  => '',
					'upc'			  => '',
					'date_available'  => '',
					'taobao_cid'      => $Item['cid'],  //淘宝上的cid
					'taobao_nick'     => $Item['nick'],	//淘宝掌柜名
					'taobao_num_iid'  => $Item['num_iid'],	//淘宝商品ID
					'taobao_url'      => $Item['detail_url'], //淘宝商品url
					'taobao_score'    => $Item['score'],	//卖家信用
					);
		$Product['product_store'] = array(0);
		$Product['product_category'] = array($category_id);
		list($Product['product_attribute'], $Product['product_option']) = split_attrs_options($Item['props'], $Item['skus']);
		
		$Product['product_image'] = array();
		if (isset($Item['item_imgs']['item_img']['url']))
		{
			$image_name = basename($Item['item_imgs']['item_img']['url']);
			@file_put_contents($root_path.'image/data/'.$num_iid.'/'.$image_name, file_get_contents($Item['item_imgs']['item_img']['url']));
			$Product['product_image'][] = array('image' => 'data/'.$num_iid.'/'.$image_name, 'sort_order' => 0);
			$Product['image'] = 'data/'.$num_iid.'/'.$image_name;
		}
		else
		{
			foreach ($Item['item_imgs']['item_img'] as $i => $arr)
			{
				$image_name = basename($arr['url']);
				@file_put_contents($root_path.'image/data/'.$num_iid.'/'.$image_name, file_get_contents($arr['url']));
				$Product['product_image'][] = array('image' => 'data/'.$num_iid.'/'.$image_name, 'sort_order' => 0);
				if ($i == 0) $Product['image'] = 'data/'.$num_iid.'/'.$image_name;
			}
		}
		
		$Translator = new Translator;
		$product_description = array();
		if (count($languages) > 0)
		{
			foreach ($languages as $lang_id => $code2)
			{
				$product_description[$lang_id] = array();
				$product_description[$lang_id]['name'] = $Item['title'];
				$product_description[$lang_id]['description']  = $Item['desc'];
				
				$product_description[$lang_id]['meta_keyword']  = '';
				$product_description[$lang_id]['meta_description']  = '';
				
				if (strpos(strtolower($code2), 'cn') === false)
				{
					//翻译标题
					$Translator->setText($Item['title']);
					$product_description[$lang_id]['name'] = $Translator->translate('zh-CN', $code2); 
					
					$Translator->setText($Item['desc']);					
					$product_description[$lang_id]['description']  = $Translator->translate('zh-CN', $code2);
				}
			}
		}
		$Product['product_description'] = $product_description;
		$DBCtrl->addProduct($Product);
	}
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
<script> window.parent.document.getElementById('download_button_<?=$num_iid ?>').value= '下载完成';</script>

