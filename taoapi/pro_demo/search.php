   
<form action="itemcats.php" method="get" name="form1" id="form1">
<table width="100%" class="table">
	<tr>
      <td colspan="8" bgcolor="#ffffff">搜索选项</td>
    </tr>
	<tr>
      <td>分类ID</td>
      <td>关键字</td>      
      <td>起始价格</td>
      <td>最高价格</td>
      <td>起始佣金比率</td>
      <td>最高佣金比率</td>      
      <td style="background:#ddd;">是否自动发货</td>                        
      <td style="background:#ddd;">查询是否消保卖家</td>      
    </tr>
	<tr>
      <td>
<select name="cid" style="width:200px; ">
<option value="0" selected="selected">所有分类</option>
<option value="99">&nbsp;网络游戏点卡</option>
<option value="50017708">&nbsp;网游装备、游戏币、帐号、代练</option>
<option value="40">&nbsp;腾讯QQ专区</option>
<option value="50004958">&nbsp;移动联通小灵通充值中心</option>
<option value="50008907">&nbsp;IP卡、网络电话、手机号码</option>
<option value="1512">&nbsp;品牌手机</option>
<option value="50019321">&nbsp;国货精品手机</option>
<option value="50018367">&nbsp;手机配件</option>
<option class="S" value="11">&nbsp;电脑硬件、台式整机、网络设备</option>
<option class="S" value="1101">&nbsp;笔记本电脑</option>
<option value="50018377">&nbsp;笔记本配件</option>
<option class="S" value="14">&nbsp;数码相机、摄像机、图形冲印</option>
<option class="S" value="1201">&nbsp;MP3、MP4、iPod、录音笔</option>
<option class="S" value="50008090">&nbsp;3C数码配件市场</option>
<option class="S" value="50007218">&nbsp;办公设备、办公用品及耗材</option>
<option class="S" value="50018627">&nbsp;电子辞典/学习机、文具</option>
<option class="S" value="50018908">&nbsp;影音电器</option>
<option value="50017285">&nbsp;国货精品视听</option>
<option class="S" value="50018957">&nbsp;生活电器</option>
<option class="S" value="50018930">&nbsp;厨房电器</option>
<option class="S" value="50008168">&nbsp;网络服务、电脑软件</option>
<option class="S" value="50019393">&nbsp;闪存卡、U盘、移动存储</option>
<option value="50010788">&nbsp;彩妆/香水/美发/工具</option>
<option value="1801">&nbsp;美容护肤/美体/精油</option>
<option value="50015926">&nbsp;珠宝、钻石、翡翠、黄金</option>
<option value="50005700">&nbsp;品牌手表、流行手表</option>
<option value="1705">&nbsp;饰品、流行首饰、时尚饰品</option>
<option value="16">&nbsp;女装、女士精品</option>
<option value="50006843">&nbsp;女鞋</option>
<option value="50006842">&nbsp;男女箱包、单肩包、手提包、旅行箱</option>
<option value="1625">&nbsp;女士内衣、男士内衣、家居服</option>
<option value="50016853">&nbsp;男鞋/皮鞋/休闲鞋</option>
<option value="30">&nbsp;男装</option>
<option value="50010404">&nbsp;配件、皮带、围巾、领带、帽子手套</option>
<option value="50010728">&nbsp;运动、瑜伽、健身、球迷用品</option>
<option value="50016756">&nbsp;运动服、运动包、颈环配件</option>
<option value="50010388">&nbsp;运动鞋</option>
<option value="2203">&nbsp;户外、登山、野营、涉水</option>
<option value="50018963">&nbsp;旅游度假、打折机票、特惠酒店</option>
<option value="50008165">&nbsp;童装、童鞋、婴儿服、孕妇装</option>
<option value="35">&nbsp;奶粉、尿片、母婴用品</option>
<option value="50005998">&nbsp;益智玩具、童车、童床、书包</option>
<option class="S" value="21">&nbsp;居家日用、厨房餐饮、卫浴洗浴</option>
<option class="S" value="2128">&nbsp;时尚家饰、工艺品、十字绣</option>
<option class="S" value="50002768">&nbsp;个人护理保健</option>
<option class="S" value="50008164">&nbsp;家具、家具定制、宜家代购</option>
<option class="S" value="50008163">&nbsp;家纺 床上用品 地毯 布艺</option>
<option class="S" value="27">&nbsp;装潢、灯具、五金、安防、卫浴</option>
<option value="50008825">&nbsp;保健食品</option>
<option value="50002766">&nbsp;食品、茶叶、零食、特产</option>
<option value="23">&nbsp;古董、邮币、字画、收藏</option>
<option value="26">&nbsp;汽车、配件、改装、摩托、自行车</option>
<option value="29">&nbsp;宠物、宠物食品及用品</option>
<option value="28">&nbsp;ZIPPO、瑞士军刀、眼镜</option>

<option value="2813">&nbsp;成人用品、避孕用品、情趣内衣</option>
<option value="33">&nbsp;书籍、杂志、报纸</option>
<option value="34">&nbsp;音乐、影视、明星、乐器</option>
<option value="20">&nbsp;电玩、配件、游戏、攻略</option>
<option value="25">&nbsp;模型、娃娃、人偶、毛绒、KITTY</option>
<option value="50008075">&nbsp;演出、旅游、吃喝玩乐折扣券</option>
<option value="50007216">&nbsp;鲜花速递、蛋糕配送、园艺花艺</option>
<option value="50003754">&nbsp;网店装修、用品、物流快递、图片存储</option>
</select>      
      </td>
      <td><input type="text" name="keyword" id="keyword"></td>      
      <td><input type="text" name="start_price" id="start_price"></td>
      <td><input type="text" name="end_price" id="end_price"></td>
      <td><input type="text" name="start_commissionRate" id="start_commissionRate"></td>
      <td><input type="text" name="end_commissionRate" id="end_commissionRate"></td>      
      <td style="background:#ddd;">
      <select name="auto_send">
      <option value="" selected>选择</option>      
        <option value="true">是</option>
        <option value="true">否</option>        
      </select>
     </td>
      <td style="background:#ddd;">
      <select name="guarantee">
      <option value="" selected>选择</option>      
        <option value="true">是</option>
        <option value="true">否</option>        
      </select>      
      </td>                              
    </tr> 
    
	<tr>
      <td>排序方式</td>
      <td>商品所在地</td>
      <td>起始累计推广量选项</td>
      <td>最高累计推广量</td>
      <td>卖家起始信用项</td>
      <td>卖家最高信用</td>
      <td style="background:#ddd;">每页返回结果数</td>      
      <td style="background:#ddd;"></td>      
   
    </tr>
 	<tr>
      <td>
      <select name="sort">
      <option value="price_desc">(价格从高到低)</option>      
      <option value="price_asc">(价格从低到高)</option>
      <option value="credit_desc">(信用等级从高到低)</option>
      <option value="commissionRate_desc">(佣金比率从高到底</option>
      <option value="commissionRate_asc">(佣金比率从低到高)</option>
      <option value="commissionNum_desc">(成交量成高到低) </option>
      <option value="commissionNum_asc">(成交量从低到高</option>
      <option value="commissionVolume_desc">(总支出佣金从高到底)</option>
      <option value="commissionVolume_asc">(总支出佣金从低到高)</option>
      <option value="delistTime_desc()">(商品下架时间从高到底)</option>
      <option value="delistTime_asc">(商品下架时间从低到高)</option>
      </select>
      </td>
      <td>
      <select name="area">
      <option value="" selected> 所有地区</option>
      <option value='江苏,浙江,上海'>江浙沪</option>
      <option value='广州,深圳,中山,珠海,佛山,东莞,惠州'>珠三角</option>
      <option value='香港,澳门,台湾'>港澳台</option>
      <option value='美国,英国,法国,瑞士,澳洲,新西兰,加拿大,奥地利,韩国,日本,德国,意大利,西班牙,俄罗斯,泰国,印度,荷兰,新加坡,其它国家'>海外</option>
      <option value='北京'>北京</option>
      <option value='上海'>上海</option>
      <option value='杭州'>杭州</option>
      <option value='广州'>广州</option>
      <option value='深圳'>深圳</option>
      <option value='南京'>南京</option>
      <option value='武汉'>武汉</option>
      <option value='天津'>天津</option>
      <option value='成都'>成都</option>
      <option value='哈尔滨'>哈尔滨</option>
      <option value='重庆'>重庆</option>
      <option value='宁波'>宁波</option>
      <option value='无锡'>无锡</option>
      <option value='济南'>济南</option>
      <option value='苏州'>苏州</option>
      <option value='温州'>温州</option>
      <option value='青岛'>青岛</option>
      <option value='沈阳'>沈阳</option>
      <option value='福州'>福州</option>
      <option value='西安'>西安</option>
      <option value='长沙'>长沙</option>
      <option value='合肥'>合肥</option>
      <option value='南宁'>南宁</option>
      <option value='南昌'>南昌</option>
      <option value='郑州'>郑州</option>
      <option value='厦门'>厦门</option>
      <option value='大连'>大连</option>
      <option value='常州'>常州</option>
      <option value='石家庄'>石家庄</option>
      <option value='东莞'>东莞</option>
      <option value='安徽'>安徽</option>
      <option value='福建'>福建</option>
      <option value='甘肃'>甘肃</option>
      <option value='广东'>广东</option>
      <option value='广西'>广西</option>
      <option value='贵州'>贵州</option>
      <option value='海南'>海南</option>
      <option value='河北'>河北</option>
      <option value='黑龙江'>黑龙江</option>
      <option value='河南'>河南</option>
      <option value='湖北'>湖北</option>
      <option value='湖南'>湖南</option>
      <option value='江苏'>江苏</option>
      <option value='江西'>江西</option>
      <option value='吉林'>吉林</option>
      <option value='辽宁'>辽宁</option>
      <option value='内蒙古'>内蒙古</option>
      <option value='宁夏'>宁夏</option>
      <option value='青海'>青海</option>
      <option value='山东'>山东</option>
      <option value='山西'>山西</option>
      <option value='陕西'>陕西</option>
      <option value='四川'>四川</option>
      <option value='新疆'>新疆</option>
      <option value='西藏'>西藏</option>
      <option value='云南'>云南</option>
      <option value='浙江'>浙江</option>
      <option value='other'>其它</option>
      </select>      
      </td>
      <td><input type="text" name="start_commissionNum" id="start_commissionNum"></td>
      <td><input type="text" name="end_commissionNum" id="end_commissionNum"></td>
      <td>
      <select name="start_credit">
      <option value="1heart">(一心)</option>      
      <option value="2heart">(两心)</option>      
      <option value="3heart">(四心)</option>      
      <option value="4heart">(一心)</option>      
      <option value="5heart">(五心)</option>                              
      <option value="1diamond">(一钻)</option>      
      <option value="2diamond">(两钻)</option>      
      <option value="3diamond">(三钻)</option>      
      <option value="4diamond">(四钻)</option>      
      <option value="5diamond">(五钻)</option>                              
      <option value="1crown">(一冠)</option>      
      <option value="2crown">(两冠)</option>      
      <option value="3crown">(三冠)</option>   
      <option value="4crown">(四冠)</option>            
      <option value="5crown">(五冠)</option>      
      <option value="1goldencrown">(一黄冠)</option>                              
      <option value="2goldencrown">(二黄冠)</option>      
      <option value="3goldencrown">(三黄冠)</option>      
      <option value="4goldencrown">(四黄冠)</option>      
      <option value="5goldencrown">(五黄冠)</option>      
      </select>
      </td>
      <td>
      <select name="end_credit">
      <option value="1heart">(一心)</option>      
      <option value="2heart">(两心)</option>      
      <option value="3heart">(四心)</option>      
      <option value="4heart">(一心)</option>      
      <option value="5heart">(五心)</option>                              
      <option value="1diamond">(一钻)</option>      
      <option value="2diamond">(两钻)</option>      
      <option value="3diamond">(三钻)</option>      
      <option value="4diamond">(四钻)</option>      
      <option value="5diamond">(五钻)</option>                              
      <option value="1crown">(一冠)</option>      
      <option value="2crown">(两冠)</option>      
      <option value="3crown">(三冠)</option>   
      <option value="4crown">(四冠)</option>            
      <option value="5crown">(五冠)</option>      
      <option value="1goldencrown">(一黄冠)</option>                              
      <option value="2goldencrown">(二黄冠)</option>      
      <option value="3goldencrown">(三黄冠)</option>      
      <option value="4goldencrown">(四黄冠)</option>      
      <option value="5goldencrown" selected>(五黄冠)</option>      
      </select>      
      </td>      
                 
      <td style="background:#ddd;">
      <select name="page_size">
      <?php 
	  for($i=10;$i<41;$i++){
	  ?>
      <option value="<?php echo $i?>"><?php echo $i?></option>
      <? }?>
      </select>      
      </td>    
      <td style="background:#ddd;"><input type="submit" value="搜索">
      </td>                                
    </tr>    


</table>
</form>
