<?php
	$api_path = str_replace(array('search_product\index.php', 'search_product/index.php'), '', __FILE__);
	include_once($api_path.'config.php');
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/page.Class.php');
	
	header("Content-Type:text/html;charset=UTF-8");
//	include_once($api_path.'check_auth.php');
	/* Build By fhalipay */
	
	$search_type   = empty($_REQUEST['search_type'])  ? 'keyword' : $_REQUEST['search_type'];
	$cid           = empty($_REQUEST['cid'])          ? '0' : intval($_REQUEST['cid']);
	$parent_cid    = empty($_REQUEST['parent_cid'])   ? '0' : intval($_REQUEST['parent_cid']);
  	$keyword       = empty($_REQUEST['keyword'])      ? '' : $_REQUEST['keyword'];
  	$category_id   = empty($_REQUEST['category_id'])  ? 0 : intval($_REQUEST['category_id']);
  	$start_price   = empty($_REQUEST['start_price'])  ? '' : intval($_REQUEST['start_price']);
  	$end_price     = empty($_REQUEST['end_price'])    ? '' : intval($_REQUEST['end_price']);
  	$state         = empty($_REQUEST['state'])        ? '' : $_REQUEST['state'];
  	$start_score   = empty($_REQUEST['start_score'])  ? '1' : $_REQUEST['start_score'];
  	$end_score     = empty($_REQUEST['end_score'])    ? '20' : $_REQUEST['end_score'];	  
  	$order_by      = empty($_REQUEST['order_by'])     ? '' : $_REQUEST['order_by'];
	$page_no       = empty($_REQUEST['page'])         ? '1' : intval($_REQUEST['page']);
  	$page_size     = empty($_REQUEST['page_size'])    ? '10' : intval($_REQUEST['page_size']);
	
  	$paramArr = array(
  		   'search_type' => $search_type, //查询类型
  			   'keyword' => $keyword,     //查询关键字	
  		   'category_id' => $category_id, //本站商品分类	
		    	   'cid' => $cid,         //商品所属分类id 	
	             'state' => $state,       //商品所属分类id
		   'start_price' => $start_price, //起始价格
		     'end_price' => $end_price,   //最高价格
		   'start_score' => $start_score, //店铺等级
		     'end_score' => $end_score,   //店铺等级
		      'order_by' => $order_by,    //排序方式
	  	     'page_size' => $page_size ,  //每页返回结果数.最大每页40 
  	);
  	$strPageParam  = createStrParam($paramArr);	//组织分页参数
  	$paramArr = array();
  	
	if (!empty($_REQUEST['keyword']))
	{
		/* 获取指定类目或指定关键字淘宝商品列表 Start*/
		
	  	
		//参数数组
		$paramArr = array(
	
			/* API系统级输入参数 Start */
	
		    	'method' => 'taobao.items.search',  //API名称
		     'timestamp' => date('Y-m-d H:i:s'),			
			    'format' => 'xml',  //返回格式,本demo仅支持xml
	    	   'app_key' => $appKey,  //Appkey			
		    		 'v' => '2.0',   //API版本号		   
			'sign_method'=> 'md5', //签名方式			
	
			/* API系统级参数 End */				 
	
			/* API应用级输入参数 Start*/
	
		      	'fields' =>  'detail_url,num_iid,title,nick,pic_url,cid,price,type,location.state,location.city,delist_time,post_fee,score,volume,is_prepay',  //返回字段
		    	   'cid' => $cid,         //商品所属分类id 	
	    'location.state' => $state,       //商品所属分类id 		   
		   'start_price' => $start_price, //起始价格
		     'end_price' => $end_price,   //最高价格
		   'start_score' => $start_score, //店铺等级
		     'end_score' => $end_score,   //店铺等级
		  'stuff_status' => 'new',        //只搜索新品
		      'order_by' => $order_by,    //排序方式
			   'page_no' => $page_no,     //结果页数.1~99
	  	     'page_size' => $page_size ,  //每页返回结果数.最大每页40 
					
			/* API应用级输入参数 End*/
		);
		if ($search_type == 'keyword')  $paramArr['q'] = $keyword; //查询关键字	
		elseif ($search_type == 'shop') $paramArr['nicks'] = $keyword; //查询店铺	
		
		$paramArr = array_filter($paramArr);

		$sign = createSign($paramArr, $appSecret);	//生成签名
		
		$strParam  = createStrParam($paramArr);	//组织参数		
		$strParam .= 'sign=' . $sign . '&app_key=' . $appKey;
		$api_url   = $api_url.$strParam; //构造Url		
		$cnt = 0;	//连接超时自动重试
		while($cnt < 3 && ($result = @vita_get_url_content($api_url)) === FALSE) $cnt++;
		
		//解析Xml数据
		$result = getXmlData($result);
//		var_export($result);die();
		if (!empty($result))
		{
			//返回结果
			if ($result['total_results'] > 0) $TaobaoItems  = $result['item_search']['items']['item'];
			$Total_results = $result['total_results'];
		}
		/* 获取指定类目或指定关键字淘宝客商品列表 End*/	
	}
	include_once('search.php');
	include_once('items.php');
?>