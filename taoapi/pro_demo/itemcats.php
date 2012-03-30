<?php
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
?>	
<html>
<head>
<title>此DEMO的功能:获取指定类目或指定关键字淘宝客商品列表</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script>
	alert(window.parent.location);
</script>
</head>
<body>
<?
	/* Build By fhalipay */
	
/* 获取指定类目或指定关键字淘宝客商品列表 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.taobaoke.items.get',  //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

	    	'fields' =>  'iid,num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location',  //返回字段
	    	  'nick' => $userNick,  //推广者淘宝昵称
   	       'keyword' => $keyword,  //查询关键字	
	    	   'cid' => $cid,  //商品所属分类id 		   
	   'start_price' => $start_price, //起始价格
	     'end_price' => $end_price, //最高价格
		 'auto_send' => $auto_send, //是否自动发货
		 	  'area' => $area, //商品所在地 例如:杭州市
	  'start_credit' => $start_credit, //卖家起始信用
	    'end_credit' => $end_credit,  //卖家最高信用
	   		  'sort' => $sort, //排序方式
		 'guarantee' => $guarantee, //查询是否消保卖家
'start_commissionRate'=>$start_commissionRate, //起始佣金比率选项
 'end_commissionRate'=> $end_commissionRate, //最高佣金比率
'start_commissionNum'=> $start_commissionNum, //起始累计推广量选项
 'end_commissionNum' => $end_commissionNum, //最高累计推广量
		   'page_no' => $page_no, //结果页数.1~99
  	     'page_size' => $page_size , //每页返回结果数.最大每页40 
				
		/* API应用级输入参数 End*/
	);

	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$url = $url.$strParam;
	
	//连接超时自动重试
	$cnt=0;	
	while($cnt < 3 && ($result=@vita_get_url_content($url))===FALSE) $cnt++;
	
	//解析Xml数据
	$result = getXmlData($result);
	
	//获取错误信息
	$sub_msg = $result['sub_msg'];
	
	//返回结果
	$TaobaokeItem = $result['taobaoke_items']['taobaoke_item'];
	$TaobaokeCount = $result['total_results'];
	
/* 获取指定类目或指定关键字淘宝客商品列表 End*/	
?>
<b>此DEMO的功能:获取指定类目或指定关键字淘宝客商品列表</b>

	<?php
		require_once 'allItemcats.php';
		require_once 'search.php';
	?>
 
<table border="0" width="100%" class="table">
	<tr>
		<td>
			详情
		</td>
		<td>
			IID
		</td>
		<td>
			名称
		</td>        
		<td>
			Num_iid
		</td>        

		<td>
			卖家昵称
		</td>
		<td>
			图片
		</td>        
		<td>
			价格
		</td>
		<td>
			商品推广链接
		</td>
        <td>
        	佣金
		</td>            
        <td>        
			佣金比率
		</td>            
        <td>        
			总成交量
		</td>        
        <td>            
            总支出佣金
        </td>
		<td>
			卖家信用
		</td>
		<td>
			所在地
		</td>         
                
		<td>
			店铺推广链接
		</td>        
	</tr>
	<?php
	if (empty($sub_msg)){
		if (is_array($TaobaokeItem)){
		foreach ($TaobaokeItem as $key => $val) { 
	?>
	<tr>
		<td>
			<a href=item.php?num_iid=<?php echo $val['num_iid'];?> target="_blank">详情</a>
		</td>
		<td>
			<a href="javascript:;" title="<?php echo $val['iid'];?>">查看</a>
		</td>
		<td>
            <?php echo $val['title'];?>
		</td>
		<td>
			<a href="javascript:;" title="<?php echo $val['num_iid'];?>">查看</a>
		</td>        
		<td>
			<?php echo $val['nick'];?>
		</td>
		<td>
			<a href="<?php echo $val['pic_url'];?>" target="_blank">图片</a>
		</td>        
		<td>
			<?php echo $val['price'];?>
		</td>
		<td>
			<a href="<?php echo $val['click_url'];?>" target="_blank">商品推广链接</a>
		</td>
		<td>
			<?php echo $val['commission'];?>
		</td>  
 		<td>            
			<?php echo $val['commission_rate']/100;?> %
		</td>  
 		<td>
			<?php echo $val['commission_num'];?>
		</td>  
 		<td>            
            <?php echo $val['commission_volume'];?>
		</td>  
 		<td>
			<img src="img/level_<?php echo $val['seller_credit_score'];?>.gif" />
		</td>
 		<td>
			<?php echo $val['item_location'];?>
		</td>                        
		<td>
			<a href="<?php echo $val['shop_click_url'];?>" target="_blank">店铺推广链接</a>
		</td>        
	</tr>
	<?php
		}
		}else{
		echo '<tr><td colspan=15 class="sub_msg">未接收到数据,请稍候重试</td></tr>';
		}
	?>
    <tr>
      <td colspan="15"> <?php
	// 分页 new PageClass(数据总数,每页数量,页码,URL组成);
	$pages = new PageClass($TaobaokeCount,$page_size,$_GET['page'],'itemcats.php?'.$_SERVER['QUERY_STRING'].'&page={page}');
	echo $pages -> myde_write();
	?>
      </td>
    </tr>    
    <?
	}else{

	echo '<tr><td colspan=15 class="sub_msg">'.$sub_msg.'</td></tr>';		
	}
?>

</table>

</body>
</html>