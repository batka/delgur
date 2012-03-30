<?php
	//获取数据兼容file_get_contents与curl
	function vita_get_url_content($url) {
		if(!function_exists('file_get_contents')) {
			$file_contents = file_get_contents($url);
		} else {
		$ch = curl_init();
		$timeout = 5; 
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		}
	return $file_contents;
	}
	
	//签名函数 
	function createSign ($paramArr) { 
	    global $appSecret; 
	    $sign = $appSecret; 
	    ksort($paramArr); 
	    foreach ($paramArr as $key => $val) { 
	       if ($key !='' && $val !='') { 
	           $sign .= $key.$val; 
	       } 
	    } 
	    $sign = strtoupper(md5($sign.$appSecret));
	    return $sign; 
	}

	//组参函数 
	function createStrParam ($paramArr) { 
	    $strParam = ''; 
	    foreach ($paramArr as $key => $val) { 
	       if ($key != '' && $val !='') { 
	           $strParam .= $key.'='.urlencode($val).'&'; 
	       } 
	    } 
	    return $strParam; 
	} 

	//解析xml函数
	function getXmlData ($strXml) {
		$pos = strpos($strXml, 'xml');
		if ($pos) {
			$xmlCode=simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
			$arrayCode=get_object_vars_final($xmlCode);
			return $arrayCode ;
		} else {
			return '';
		}
	}
	
	function get_object_vars_final($obj){
		if(is_object($obj)){
			$obj=get_object_vars($obj);
		}
		if(is_array($obj)){
			foreach ($obj as $key=>$value){
				$obj[$key]=get_object_vars_final($value);
			}
		}
		return $obj;
	}
?>