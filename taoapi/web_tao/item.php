<?php

class CallTaobao {
	
	private $DBCtrl;
	private $api_path;
	private $root_path;
	
	function QueryItem($data, $is_cached = 0)
	{
		$this->api_path = $api_path = str_replace(array('web_tao\item.php', 'web_tao/item.php'), '', __FILE__);
		$this->root_path = $root_path = str_replace(array('taoapi\web_tao\item.php', 'taoapi/web_tao/item.php'), '', __FILE__);
		include_once($api_path.'config.php'); // the config is taobao api config
		include_once($api_path.'lib/functions.php');
		include_once($api_path.'lib/translator2.php');
		include_once($api_path.'lib/translator_bing.php');
		include($api_path.'model/opencart_catelog.php');
		
		
		$num_iid  = empty($data['num_iid']) ? 0 : $data['num_iid'];
		$language = empty($data['language']) ? 1 : $data['language'];
		
		$this->DBCtrl = new DBCtrlModel();
		$aLangs = $this->DBCtrl->getLanguages();
		$language_id = array_search($language, $aLangs);
		$language_cn_id = array_search('cn', $aLangs);
		
		if ($num_iid == 0)
		{
			echo "商品ID不能正确，不能为空或者0";
			exit;
		}
					
	/* 获取淘宝商品详情 Start */	

		//参数数组
		
		if (!$ItemCached_CN = $this->DBCtrl->getItemCached($num_iid, $language_cn_id))
		{
			$fields = 'detail_url,num_iid,title,nick,type,cid,seller_cids,props,props_name,input_pids,input_str,desc,pic_url,num,valid_thru,score,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual';
		}
		else		
		{
			$fields = 'detail_url,num_iid,type,cid,seller_cids,props,props_name,input_pids,input_str,pic_url,num,valid_thru,score,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual,nick';
		}
		
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

				'fields' => $fields, //返回字段
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
				
		//unset($result['item']['desc']);
		//print_r($result['item']);die();
		
		if (empty($result) || isset($result['code']) && $result['code'] > 1)
		{
			return array();
			die('Sorry! The product out of stock!');
		}
		

		$Item = @$result['item']; //返回结果
		$Item['props']          = isset($Item['props'])          ? $Item['props']            : '';
		$Item['property_alias'] = isset($Item['property_alias']) ? $Item['property_alias']   : '';
		$Item['skus']           = isset($Item['skus'])           ? $Item['skus']             : '';
		$Item['input_pids']     = isset($Item['input_pids'])     ? $Item['input_pids']       : '';
		$Item['input_str']      = isset($Item['input_str'])      ? $Item['input_str']        : '';
		$Item['desc']      		= isset($Item['desc'])      	 ? $Item['desc']     		 : '';
		$Item['title']			= isset($Item['title'])      	 ? str_replace(array("包邮", "免邮"), '', $Item['title']) : '';
		$Item['props_keys']   	= array();
		$Item['props_values']   = array();
		$Item['nick']          	= isset($Item['nick'])			 ? $Item['nick']            : '';
		
		$images = $desc = '';
		if ($Item['desc'] != '')
		{
			list($images, $desc) = $this->clearHtml($Item['desc']);
		}
		list($cn_options, $Item['Skus']) = $this->split_skus($Item['skus'], 'cn');
		
		list($Item['options'], $Item['Skus']) = $this->split_skus($Item['skus'], $data['language']);
		list($Item['props_keys'], $Item['props_values']) = $this->split_props_name($Item['props_name']);
		
		if (!empty($Item['props']))
		{
			list($Item['Props']) =  $this->split_props($Item['props'], $cn_options['options']);
		}
		else
		{
			$Item['Props'] = array();
		}
		$cn_props_keys = $Item['props_keys'];
		
		if (!empty($Item['Skus']))
		{
			$this->CacheSkus($num_iid, $Item['Skus']);
		}
		
		
		if($ItemCached = $this->DBCtrl->getItemCached($num_iid, $language_id))
		{	
			//print_r($ItemCached);die();
			$Item['title'] = $ItemCached['name'];
			$Item['desc']  = $this->getCachedDesc($num_iid, $data['language']);
			$Item['meta_keyword']     = $ItemCached['meta_keyword'];
			$Item['meta_description'] = $ItemCached['meta_description'];
			//$Item['nick'] = $ItemCached['taobao_nick'];
			//echo $Item['nick']; die();
			$Translator = new Translator;
			
			if (!empty($Item['property_alias']) && ($data['language'] != 'cn' && $data['language'] != 'zh-CN'))
			{
				$Item['property_alias'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['property_alias']);
				$Item['property_alias'] = '<="'.$Item['property_alias'].'">';
				
				$Translator->setText($Item['property_alias']);
				$Item['property_alias'] = $Translator->translate('zh-CN', $data['language']); 
				
				$Item['property_alias'] = str_replace(array('<="', '< ="', '<= "', "_"), '', $Item['property_alias']);
				$Item['property_alias'] = substr($Item['property_alias'], 0, -2);
			}
			
			if (!empty($Item['props_name']) && ($data['language'] != 'cn' && $data['language'] != 'zh-CN'))
			{
				$Item['props_name'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['props_name']);
				$Item['props_name'] = '<="'.$Item['props_name'].'">';
				$Translator->setText($Item['props_name']);
				$Item['props_name'] = $Translator->translate('zh-CN', $data['language']); 
				$Item['props_name'] = str_replace(array('<="', '< ="', '<= "', "\n", "_"), '', $Item['props_name']);
				$Item['props_name'] = substr($Item['props_name'], 0, -2);
			}
						
			list($Item['props_keys'], $Item['props_values']) = $this->split_props_name($Item['props_name']);	
			//list($Item['Props']) = $this->split_props($Item['props'], $Item['property_alias'], $Item['skus'], $Item['props_values']);
			
		}
		elseif ($ItemCached_CN)
		{	
			//print_r($ItemCached_CN);die();
			$Item['title'] = $ItemCached_CN['name'];
			$Item['desc']  = $this->getCachedDesc($num_iid, 'cn');
			$Item['meta_keyword']     = $ItemCached_CN['meta_keyword'];
			$Item['meta_description'] = $ItemCached_CN['meta_description'];
			//$Item['nick'] = $ItemCached_CN['nick'];
			
			$desc    = $this->getCachedText($num_iid, 'cn');
			$images  = $this->getCachedImages($num_iid, 'cn');			
			$Translator = new Translator;
			if ($Item['input_str'])
			{
				$Translator->setText(str_replace('，', '+', $Item['input_str']));
				$Item['input_str'] = $Translator->translate('zh-CN', $data['language']); 
			}
			
			$Translator->setText($Item['title']);
			$Item['title'] = $Translator->translate('zh-CN', $data['language']); 		
			
			$Item['product_description'][$language_id]['name']             = $Item['title'];	//set 翻译后标题
			$Item['product_description'][$language_id]['description']      = '';	
			$Item['product_description'][$language_id]['meta_description'] = '';
			$Item['product_description'][$language_id]['meta_keyword']     = '';
			$Item['product_description'][$language_id]['attribute']        = '';
			
			if (!empty($Item['property_alias']))
			{
				$Item['property_alias'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['property_alias']);
				$Item['property_alias'] = '<="'.$Item['property_alias'].'">';
				
				$Translator->setText($Item['property_alias']);
				$Item['property_alias'] = $Translator->translate('zh-CN', $data['language']); 
				
				$Item['property_alias'] = str_replace(array('<="', '< ="', '<= "', "_"), '', $Item['property_alias']);
				$Item['property_alias'] = substr($Item['property_alias'], 0, -2);
				
			}
			
			if (!empty($Item['props_name']))
			{
				$Item['props_name'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['props_name']);
				$Item['props_name'] = '<="'.$Item['props_name'].'">';
				$Translator->setText($Item['props_name']);
				$Item['props_name'] = $Translator->translate('zh-CN', $data['language']); 
				$Item['props_name'] = str_replace(array('<="', '< ="', '<= "', "\n", "_"), '', $Item['props_name']);
				$Item['props_name'] = substr($Item['props_name'], 0, -2);
				list($Item['props_keys'], $Item['props_values']) = $this->split_props_name($Item['props_name']);
				
			}
		
			$TranslatorBing = new TranslatorBing;
			$TranslatorBing->setText($desc);
			$desc = $TranslatorBing->translate('zh-CN', $data['language']); 
			$Item['desc'] = $images . htmlspecialchars_decode($desc); 
			
			$Item[$data['language']]['title'] = $Item['title'];	//set 中文标题
			
			$this->saveCacheDesc($num_iid, $desc, $images, $data['language']);	//cache desc
			
			//list($Item['Props'], $Item['Options'], $Item['Skus']) = $this->split_props($Item['props'], $Item['property_alias'], $Item['skus'], $Item['props_values']);
			
			$this->addProduct($Item);
		}
		elseif ($data['language'] != '' && $data['language'] != 'cn' && $data['language'] != 'zh-CN')
		{			
			$Item['product_description'][$language_cn_id]['name']             = $Item['title'];	//set 中文标题
			$Item['product_description'][$language_cn_id]['description']      = '';	
			$Item['product_description'][$language_cn_id]['meta_description'] = '';
			$Item['product_description'][$language_cn_id]['meta_keyword']     = '';
			$Item['product_description'][$language_cn_id]['attribute']        = '';
			$this->saveCacheDesc($num_iid, $desc, $images, 'cn');
			
			$Translator = new Translator;
			if ($Item['input_str'])
			{
				$Translator->setText(str_replace('，', '+', $Item['input_str']));
				$Item['input_str'] = $Translator->translate('zh-CN', $data['language']); 
			}
			
			$Item['title'] = str_replace("包邮", "免邮", $Item['title']);			
			$Translator->setText($Item['title']);
			$Item['title'] = $Translator->translate('zh-CN', $data['language']); 		
			
			$Item['product_description'][$language_id]['name']             = $Item['title'];	//set 翻译后标题
			$Item['product_description'][$language_id]['description']      = '';	
			$Item['product_description'][$language_id]['meta_description'] = '';
			$Item['product_description'][$language_id]['meta_keyword']     = '';
			$Item['product_description'][$language_id]['attribute']        = '';
			
			if (!empty($Item['property_alias']))
			{
				$Item['property_alias'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['property_alias']);
				$Item['property_alias'] = '<="'.$Item['property_alias'].'">';
				
				$Translator->setText($Item['property_alias']);
				$Item['property_alias'] = $Translator->translate('zh-CN', $data['language']); 
				
				$Item['property_alias'] = str_replace(array('<="', '< ="', '<= "', "_"), '', $Item['property_alias']);
				$Item['property_alias'] = substr($Item['property_alias'], 0, -2);
				
			}
			
			if (!empty($Item['props_name']))
			{
				$Item['props_name'] = str_replace(array(';', ':'), array(";\n_", ":\n_"), $Item['props_name']);
				$Item['props_name'] = '<="'.$Item['props_name'].'">';
				$Translator->setText($Item['props_name']);
				$Item['props_name'] = $Translator->translate('zh-CN', $data['language']); 
				$Item['props_name'] = str_replace(array('<="', '< ="', '<= "', "\n", "_"), '', $Item['props_name']);
				$Item['props_name'] = substr($Item['props_name'], 0, -2);
				list($Item['props_keys'], $Item['props_values']) = $this->split_props_name($Item['props_name']);
				
			}
			
			$TranslatorBing = new TranslatorBing;
			$TranslatorBing->setText($desc);
			$desc = $TranslatorBing->translate('zh-CN', $data['language']); 
			
			$Item['desc'] = $images . htmlspecialchars_decode($desc); 
			
			$Item[$data['language']]['title'] = $Item['title'];	//set 中文标题
			
			$this->saveCacheDesc($num_iid, $desc, $images, $data['language']);	//cache desc
			
			
			//list($Item['Props'], $Item['Options'], $Item['Skus']) = $this->split_props($Item['props'], $Item['property_alias'], $Item['skus'], $Item['props_values']);
			
			$this->addProduct($Item);
		}
		else
		{
			$Item['product_description'][$language_cn_id]['name']             = $Item['title'];	//set 中文标题
			$Item['product_description'][$language_cn_id]['description']      = '';	
			$Item['product_description'][$language_cn_id]['meta_description'] = '';
			$Item['product_description'][$language_cn_id]['meta_keyword']     = '';
			$Item['product_description'][$language_cn_id]['attribute']        = '';
			
			$this->saveCacheDesc($num_iid, $desc, $images, 'cn');
						
			$Item['desc'] = $images.$desc;
			if ($data['language'] != '' && $data['language'] != 'cn' && $data['language'] != 'zh-CN')
			{
				$Translator = new Translator;
				$Translator->setText($Item['title']);
				$Item['title'] = $Translator->translate('zh-CN', $data['language']); 	
			}
			if (!empty($Item['props_name']))
			{
				list($Item['props_keys'], $Item['props_values']) = $this->split_props_name($Item['props_name']);
			}
			
			//list($Item['Props'], $Item['Options'], $Item['Skus']) = $this->split_props($Item['props'], $Item['property_alias'], $Item['skus'], $Item['props_values']);
			$this->addProduct($Item);
		}
		//$this->DBCtrl->db_api->query('ALTER TABLE `os_order` CHANGE `currency_value` `currency_value` VARCHAR(20) NOT NULL');
		
		//$this->DBCtrl->db_api->query('ALTER TABLE `os_order` ADD `western_union_pin` VARCHAR(10) NOT NULL DEFAULT '' AFTER `ip`');
		//$this->DBCtrl->db_api->query('ALTER TABLE `os_order_product` ADD `taobao_order` VARCHAR(20) NOT NULL DEFAULT '' AFTER `cn_option`');
		$this->DBCtrl->setOptions($num_iid, $cn_options, $cn_props_keys, $language_cn_id);
		$this->DBCtrl->setOptions($num_iid, $Item['options'], $Item['props_keys'], $language_id);
		return $Item;
	}
	
	/* 获取淘宝商品详情 End*/

	//整理属性为数组
	function split_props_name($props)
	{
		if (empty($props)) return array(false, false);
		
		$aProps  = explode(';', $props);
		
		$keys    = array();
		$values  = array();
		foreach ($aProps as $sProp)
		{
			list($key, $value, $key_text, $value_text) = explode(':', $sProp);
			$keys[floatval($key)] = $key_text;
			$values[floatval($value)] = $value_text;
		}
		
		return array($keys, $values);
	}
	//整理skus为数组
	function split_skus($skus, $lang = '')
	{
		if (empty($skus) || ! isset($skus['sku'][0])) return array(FALSE, FALSE);
		
		$aSkus = array();
		$properties_name = '';
		foreach($skus['sku'] as $sku)
		{
			$properties_name .= $sku['properties_name']."\n";
			$aSkus[$sku['properties']] = $sku;
		}
		
		if ($lang != '' && $lang != 'cn' && $lang != 'zh-CN')
		{
			$Translator = new Translator;
			$Translator->setText($properties_name);
			$properties_name = $Translator->translate('zh-CN', $lang); 
		}
		
		$arr_sku = explode("\n", trim($properties_name));
		$options = array();
		$values = array();
		foreach($arr_sku as $sku)
		{
			$temp = explode(';', trim($sku));
			
			foreach($temp as $str)
			{
				list($key, $value_key, $key_text, $value_text) = explode(':', $str);
				$key        = trim($key);
				$value_key  = trim($value_key);
				$key_text   = trim($key_text);
				$value_text = trim($value_text);
				if( ! isset($options[$key]))
				{
					$options[$key] = $key_text;
				}
				$values[$key][$value_key] = $value_text;
				
			}
		}
		
		foreach ($values as $key => $arr)
		{
			$values[$key] = $arr;
		}
		$Options['options'] = $options;
		$Options['values']  = $values;
		return array($Options, $aSkus);
	}
	//整理出淘宝sku和属性为opencart能用的数组
	function split_props($props = '', $skus = array())
	{
		if (empty($props)) return array();
		
		$aTemp   = explode(';', $props);
		
		$aProps = array();		
		foreach ($aTemp as $str)
		{
			list($key, $value) = explode(':', $str);
			if (isset($skus[$key])) continue;
			
			$aProps[$key][$value] = '';
		}
		
		return array($aProps);
	}
	//缓存的商品描述
	function saveCacheDesc($id, $desc, $images, $lang)
	{
		$file = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.text';
		file_put_contents($file, $desc);
		$file = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.images';
		file_put_contents($file, $images);
	}
	//取得缓存的商品描述
	function getCachedDesc($id, $lang)
	{
		$file   = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.text';
		$images = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.images';
		$desc = '';
		if (file_exists($images))
		{
			$desc .= file_get_contents($images);
		}
		if (file_exists($file))
		{
			$desc .= file_get_contents($file);
		}
		return $desc;
	}
	//缓存文本 Get text file from cached/product
	function getCachedText($id, $lang)
	{
		$file   = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.text';
		if (file_exists($file))
		{
			return file_get_contents($file);
		}
		return '';
	}
	//取得图片集缓存 Get image links from cached/product
	function getCachedImages($id, $lang)
	{
		$images = ROOT_PATH.'cached/product/'.$lang.'/'.$id.'.images';
		if (file_exists($images))
		{
			return file_get_contents($images);
		}
		return '';
	}
	//对淘宝商品描述进行html代码清理
	function clearHtml($content)
	{
		//echo $content;die();
		$content = preg_replace("/<a.*?\/a>/i", "", $content);
		preg_match_all ("/<img[^>]*>/i", $content, $images);;
		$content = strip_tags($content);
		
		if (is_array($images) && count($images) > 0)
		{
			$images = implode("\n", $images[0]) . "\n";
		}
		else
		{
			$images = '';
		}
		return array($images, $content);
		
	}
	//缓存淘宝商品到本地 （sku不缓存）Download product from taobao to local server
	function addProduct($Item)
	{		
		$Product = array(
					'product_id'      => $Item['num_iid'],  //商品型号 淘宝商品id
					'model'           => $Item['num_iid'],  //商品型号 淘宝商品id
					'location'        => $Item['location']['state'].' '.$Item['location']['city'],			 //商品所在地
					'quantity'        => 999,			 //商品初始数量
					'stock_status_id' => 0,        //库存状态
					'manufacturer_id' => 0,		 //品牌制造商
					'shipping'        => 0,             
					'price'           => $Item['price'],
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
					'product_store'   => array(0),
					'product_category'=> array($Item['cid']),
					'taobao_cid'      => $Item['cid'],  //淘宝上的cid
					'taobao_nick'     => $Item['nick'],	//淘宝掌柜名
					'taobao_num_iid'  => $Item['num_iid'],	//淘宝商品ID
					'taobao_url'      => $Item['detail_url'], //淘宝商品url
					'taobao_score'    => 0,	//卖家信用
					'product_description'    => $Item['product_description'],	//卖家信用
					);
		
		$num_iid = $Item['num_iid'];
		$image_name = (basename($Item['pic_url'])).'_310x310.jpg';
		$image_file = $this->root_path.'image/data/'.$num_iid.'/'.$image_name;
		
		if (!is_dir($this->root_path.'image/data/'.$num_iid))
		{
			mkdir($this->root_path.'image/data/'.$num_iid, 0777);
		}
		
		if (!file_exists($image_file))
		{
			@file_put_contents($this->root_path.'image/data/'.$num_iid.'/'.$image_name, file_get_contents($Item['pic_url'].'_310x310.jpg'));
			$Product['image'] = 'data/'.$num_iid.'/'.$image_name;
		}
		else
		{
			$Product['image'] = 'data/'.$num_iid.'/'.$image_name;
		}
		$this->DBCtrl->addProduct2($Product);
	}
	//缓存skus
	function CacheSkus($id, $skus)
	{
		$dirname = ROOT_PATH.'cached/data/' . (date('Ymd', time())) .'/';
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0777);
		}
		$file = $dirname . $id.'_skus.txt';
		file_put_contents($file, serialize($skus));
	}
	//取得缓存的skus
	function getSkus($id)
	{
		$dirname = ROOT_PATH.'cached/data/' . (date('Ymd', time())) .'/';
		$file = $dirname . $id . '_skus.txt';
		if (file_exists($file))
			return file_get_contents($file);
		return '';
	}
	//缓存属性
	function CacheProps($id, $props, $lang)
	{
		$dirname = ROOT_PATH.'cached/data/' . (date('Ymd', time())) .'/' . $lang . '/';
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0777);
		}
		$file = $dirname . $id.'_props.txt';
		file_put_contents($file, serialize($props));
	}
	//取得缓存属性
	function getProps($id, $lang)
	{
		$dirname = ROOT_PATH.'cached/data/' . (date('Ymd', time())) .'/' . $lang . '/';
		$file = $dirname . $id . '_props.txt';
		if (file_exists($file))
			return file_get_contents($file);
		return '';
	}
}
?>
