<?php
/* Google翻译PHP接口
 */ 	
class Translator {

	public $out = "";
	public $google_translator_url = 'http://translate.google.com/translate_a/t';
	public $text = '';
	public $from = '';
	public $to = '';
	
	function setText($text)
	{
		$this->text = $text;
	}
	function translate($from = 'zh_CN', $to = 'en')
	{
		$this->out  = "";
		$this->from = $from;
		$this->to   = $to;
		
		//拼凑google翻译的api url		
		
		$gphtml = $this->postPage();
		
		$arr = $this->decode($gphtml, 1);
		foreach ($arr['sentences'] as $val)
		{
			$this->out .= $val['trans'];
		}
		return $this->out;
	}
	function postPage()
	{
		$html = "";
		
		$ch = curl_init($this->google_translator_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15); 

		/*
		*hl – 界面语言，此处无用。
		*langpair – src lang to dest lang
		*ie – urlencode的编码方式
		*text – 要翻译的文本
		*/
		
		$fields = array('sl='.$this->from, 'tl='.$this->to, 'client=json', 'ie=UTF-8','text='.urlencode($this->text));
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $fields)); 
		
		$html = curl_exec($ch);

		if(curl_errno($ch)) $html = "";
		
		curl_close ($ch);
		
		return $this->decodeUnicode($html);
	}
	function decode($json, $assoc = false)
	{
		$match = '/".*?(?<!\\\\)"/';

		$string = preg_replace($match, '', $json);
		$string = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $string);

		if ($string != '') {
			return null;
		}

		$s2m = array();
		$m2s = array();

		preg_match_all($match, $json, $m);
		
		foreach ($m[0] as $s) {
			$hash = '"' . md5($s) . '"';
			$s2m[$s] = $hash;
			$m2s[$hash] = str_replace('$', '\$', $s);
		}

		$json = strtr($json, $s2m);

		$a = ($assoc) ? '' : '(object) ';
		
		$data = array(
			':' => '=>', 
			'[' => 'array(', 
			'{' => "{$a}array(", 
			']' => ')', 
			'}' => ')'
		);
		
		$json = strtr($json, $data);

		$json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);

		$json = strtr($json, $m2s);

		$function = @create_function('', "return {$json};");
		$return = ($function) ? $function() : null;

		unset($s2m); 
		unset($m2s); 
		unset($function);

		return $return;
	}
	
	
	function decodeUnicode($str)
	{
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
				create_function(
					'$matches',
					'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
				),
				$str);
	}
}

?>
