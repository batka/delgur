<?
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
?>
<html>
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src=js/colors.js></script>

<?php
	/* Build By fhalipay */
	
/* 获取淘宝客商品详情 Start */	

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

		    'method' => 'taobao.taobaoke.items.detail.get',   //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */			

		/* API应用级输入参数 Start*/

			'fields' => 'iid,detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,auto_repost,approve,status,postage_id,product_id,auction_point,property_alias,item_imgs,prop_imgs,skus,outer_id,is_virtual,is_taobao,is_ex,videos,is_3D,score,volume,one_station,click_url,shop_click_url,seller_credit_score,approve_status', //返回字段
	      'num_iids' => $num_iid, //Num_iid
		      'nick' => $userNick, //推广者昵称
	
		/* API应用级输入参数 End*/	
	);

	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$urls = $url.$strParam;
	
	//连接超时自动重试
	$cnt=0;	
	while($cnt < 3 && ($result=@vita_get_url_content($urls))===FALSE) $cnt++;
	
	//解析Xml数据
	$result = getXmlData($result);
	
	//获取错误信息
	$sub_msg = $result['sub_msg'];
	
	//返回结果
	$taobaokeItemdetail = $result['taobaoke_item_details']['taobaoke_item_detail']['item'];
	$taobaokeItem = $result['taobaoke_item_details']['taobaoke_item_detail']

/* 获取淘宝客商品详情 End*/		
?>
<title><?php echo $taobaokeItemdetail['title'];?></title>
</head>
<body>
<script type="text/javascript">
//
function toBreakWord(intLen){
var obj=document.getElementById("ff");
var strContent=obj.innerHTML;  
var strTemp="";
while(strContent.length>intLen){
strTemp+=strContent.substr(0,intLen)+"&#10;";  
strContent=strContent.substr(intLen,strContent.length);  
}
strTemp+="&#10;"+strContent;
obj.innerHTML=strTemp;
}
if(document.getElementById  &&  !document.all)  toBreakWord(37)
// 
</script>

<b>此DEMO的功能：获取淘宝客商品详情</b>
<br><br>
<table border="0" width="100%" class="table" id="table">
  <tr>
	<td>商品推广URL</td>
    <td><?php echo $taobaokeItem['click_url'];?></td>
  </tr>
  <tr>
	<td>商品所在的店铺的推广URL</td>
    <td><?php echo $taobaokeItem['shop_click_url'];?></td>
  </tr> 
  <tr>
	<td>商品所属卖家的信用等级 </td>
    <td><img src="img/level_<?php echo $taobaokeItem['seller_credit_score'];?>.gif" /></td>
  </tr>     
  <tr>
    <td>商品id </td>  
    <td><?php echo $taobaokeItemdetail['iid'];?> </td>
  </tr>
  <tr>
    <td>商品url</td>
    <td><?php echo $taobaokeItemdetail['detail_url'];?> </td>
  </tr>
  <tr>
    <td>商品数字id</td>
    <td> <?php echo $taobaokeItemdetail['num_iid'];?></td>
  </tr>
  <tr>
    <td>商品标题</td>
    <td><?php echo $taobaokeItemdetail['title'];?></td>
  </tr>
  <tr>
    <td>卖家昵称</td>
    <td><?php echo $taobaokeItemdetail['nick'];?></td>
  </tr>
  <tr>
    <td>商品类型</td>
    <td><?php echo $taobaokeItemdetail['type'];?></td>
  </tr>
  <tr>
    <td>商品所属的叶子类目id</td>
    <td><?php echo $taobaokeItemdetail['cid'];?> </td>
  </tr>
  <tr>
    <td>卖家自定义类目列表 </td>
    <td><?php echo $taobaokeItemdetail['seller_cids'];?></td>
  </tr>
  <tr>
    <td>商品属性 </td>
    <td><div id="ff" style="width:500px;word-wrap:break-word;">
	<?php
	if(!empty($taobaokeItemdetail['props'])){	
	$paramArr = array(

		/* API系统级输入参数 Start */

		    'method' => 'taobao.itempropvalues.get',   //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */			

		/* API应用级输入参数 Start*/

			'fields' => 'cid,pid,prop_name,vid,name,name_alias,status,sort_order',
	      	   'cid' => $taobaokeItemdetail['cid'], //Num_iid
		       'pvs' => $taobaokeItemdetail['props'], //推广者昵称
	
		/* API应用级输入参数 End*/	
	);

	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$urls = $url.$strParam;

	//连接超时自动重试
	$cnt=0;	
	while($cnt < 3 && ($itempropvalues=@vita_get_url_content($urls))===FALSE) $cnt++;
	
	//解析Xml数据
	$itempropvalue = getXmlData($itempropvalues);

	$itempropvalues = $itempropvalue['prop_values']['prop_value'];

	if (count($itempropvalues['name'])<'1'){
	foreach ($itempropvalues as $prop => $key){
	 echo $key['prop_name'].':'.$key['name'].'<br />';
	 }
	 }else{
	 echo $itempropvalues['prop_name'].':'.$itempropvalues['name']; 
	 }
	 }else{
 	echo $taobaokeItemdetail['props'];
	}?>
     </div></td>
  </tr>
  <tr>
    <td>用户自行输入的类目属性ID串</td>
    <td><?php print_r( $taobaokeItemdetail['input_pids']);?></td>
  </tr>
  <tr>
    <td>用户自行输入的子属性名和属性值 </td>
    <td><?php print_r( $taobaokeItemdetail['input_str']);?> </td>    
  </tr>
  <tr>
    <td>商品主图片地址 </td>
    <td><?php echo $taobaokeItemdetail['pic_url'];?> </td>    
  </tr>
  <tr>
    <td>商品数量 </td>
    <td><?php echo $taobaokeItemdetail['num'];?> </td>    
  </tr>
  <tr>
    <td>有效期 </td>
    <td><?php echo $taobaokeItemdetail['valid_thru'];?> </td>        
  </tr>
  <tr>
    <td>上架时间 </td>
    <td><?php echo $taobaokeItemdetail['list_time'];?> </td>            
  </tr>
  <tr>
    <td>下架时间 </td>
    <td><?php echo $taobaokeItemdetail['delist_time'];?> </td>                
  </tr>
  <tr>
    <td>商品新旧程度</td>
    <td><?php echo $taobaokeItemdetail['stuff_status'];?> </td>                    
  </tr>
  <tr>
    <td>商品所在地 </td>
    <td><?php echo $taobaokeItemdetail['location']['state'].$taobaokeItemdetail['location']['city'];?> </td>                        
  </tr>
  <tr>
    <td>商品价格 </td>
    <td><?php echo $taobaokeItemdetail['price'];?> </td>                            
  </tr>
  <tr>
    <td>平邮费用 </td>
    <td><?php echo $taobaokeItemdetail['post_fee'];?> </td>                                
  </tr>
  <tr>
    <td>快递费用 </td>
    <td><?php echo $taobaokeItemdetail['express_fee'];?> </td>    
  </tr>
  <tr>
    <td>ems费用 </td>
    <td><?php echo $taobaokeItemdetail['ems_fee'];?> </td>    
  </tr>
  <tr>
    <td>支持会员打折 </td>
    <td><?php echo $taobaokeItemdetail['has_discount'];?> </td>
  </tr>
  <tr>
    <td>运费承担方式 </td>
    <td><?php echo $taobaokeItemdetail['freight_payer'];?> </td>    
  </tr>
  <tr>
    <td>是否有发票 </td>
    <td><?php echo $taobaokeItemdetail['has_invoice'];?> </td>    
  </tr>
  <tr>
    <td>是否有保修 </td>
    <td><?php echo $taobaokeItemdetail['has_warranty'];?> </td>    
  </tr>
  <tr>
    <td>橱窗推荐 </td>
    <td><?php echo $taobaokeItemdetail['has_showcase'];?> </td>
  </tr>
  <tr>
    <td>商品修改时间</td>
    <td><?php echo $taobaokeItemdetail['modified'];?> </td>    
  </tr>
  <tr>
    <td>加价幅度 </td>
    <td><?php echo $taobaokeItemdetail['increment'];?> </td>    
  </tr>
  <tr>

    <td>自动重发</td>
    <td><?php echo $taobaokeItemdetail['auto_repost'];?> </td>    
  </tr>
  <tr>
    <td>商品上传后的状态</td>
    <td><?php echo $taobaokeItemdetail['approve_status'];?> </td>    
  </tr>
  <tr>
    <td>宝贝所属的运费模板ID</td>
    <td><?php echo $taobaokeItemdetail['postage_id'];?> </td>    
  </tr>
  <tr>
    <td>宝贝所属产品的id</td>
    <td><?php echo $taobaokeItemdetail['product_id'];?> </td>
  </tr>
  <tr>
    <td>返点比例 </td>
    <td><?php echo $taobaokeItemdetail['auction_point'];?> </td>    
  </tr>
  <tr>
    <td>属性值别名 </td>
    <td><?php print_r ($taobaokeItemdetail['property_alias']);?> </td>    
  </tr>
  <tr>
    <td>商品图片列表 </td>
    <td><?php print_r($taobaokeItemdetail['item_imgs']);?> </td>    
  </tr>
  <tr>
    <td>商品属性图片列表</td>
    <td><?php print_r($taobaokeItemdetail['prop_imgs']);?> </td>    
  </tr>
  <tr>
    <td>Sku列表</td>
    <td><?php print_r($taobaokeItemdetail['skus']);?> </td>        
  </tr>
  <tr>
    <td>商家外部编码</td>
    <td><?php echo $taobaokeItemdetail['outer_id'];?> </td>      
  </tr>
  <tr>
    <td>是否虚拟商品 </td>
    <td><?php echo $taobaokeItemdetail['is_virtual'];?> </td>          
  </tr>
  <tr>
    <td>是否在淘宝显示 </td>
    <td><?php echo $taobaokeItemdetail['is_taobao'];?> </td>              
  </tr>
  <tr>
    <td>是否在外部网店显示 </td>
    <td><?php echo $taobaokeItemdetail['is_ex'];?> </td>                  
  </tr>
  <tr>
    <td>商品视频列表</td>
    <td><?php echo print_r ($taobaokeItemdetail['videos']);?> </td>                        
  </tr>
  <tr>
    <td>是否是3D淘宝的商品 </td>
    <td><?php echo $taobaokeItemdetail['is_3D'];?> </td>     
  </tr>
  <tr>
    <td>是否淘1站商品 </td>
    <td><?php echo $taobaokeItemdetail['one_station'];?></td>     
  </tr>
  <tr>
    <td>商品描述 </td>
    <td width="800px"><?php echo $taobaokeItemdetail['desc'];?> </td>    
  </tr>
</table>
</body>
</html>