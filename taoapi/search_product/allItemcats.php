<?php
	include 'config.php';
	   
/* 获取后台供卖家发布商品的标准商品类目 Start*/
			if ($cid=='0'){
   	    $parent_cid	= '0';
			}else{
   	    $parent_cid = $cid;
			}
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

	    	'fields' => 'cid,parent_cid,name,is_parent,status,sort_order,last_modified',  //返回字段
   	    'parent_cid' => $parent_cid,  //父商品类目id
				
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
	$ItemCat = $result['item_cats']['item_cat'];
	
/* 获取后台供卖家发布商品的标准商品类目 End*/	
?>

<table class="table" width="100%">
	<tr>
    	<td>
    <?php
	if (empty($_GET['parent_cid'])){
	?>
    <a href="itemcats.php?cid=0">返回上级分类</a>    
    <? }else{?>
    <a href="itemcats.php?cid=<?php echo $_GET['parent_cid'];?>">返回上级分类</a>
    <? }?>    
    
        </td>
    </tr>
    <tr>
	<?php
	$i = 1;
	if ((array)$ItemCat){
		foreach ($ItemCat as $ItemCats => $val) { 
	?>

		<td>
			<a href=itemcats.php?cid=<?php echo $val['cid'];?>&parent_cid=<?php echo $val['parent_cid'];?>><?php echo $val['name'];?></a>
		</td>

<?php
if ($i % 6 == 0) {
echo '</tr>';
}
$i++;
}}else{
echo '<td>没有下级分类</td>';
}?>
	</tr>        
</table>    