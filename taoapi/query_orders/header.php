<?php
	$ref =$_SERVER['SCRIPT_NAME'];
	if(empty($sessionKey)){
		echo '请先登录：<a href="'.$sessionurl.$appKey.'&ref='.$ref.'">获取Session</a>';exit;
	}else{
		echo '当前登陆用户:'.$userNick.'<a href="../logout.php?ref='.$ref.'">退出登陆</a>';
	}
?>