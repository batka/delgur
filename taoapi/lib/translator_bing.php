<?php
/* Google翻译PHP接口
 */ 	
class TranslatorBing {

	public $out = "";
	public $translator_url = 'http://api.microsofttranslator.com/v2/Http.svc/Translate?';
	public $appId = '0B77CB69920EE35F5B72BC65407097482AB1C63B';
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
		
		$text_len = strlen($this->text);
		$max_length = 5000;
		
		if ($text_len < $max_length)
		{
			$trans_html = $this->postPage($this->text);
			
			if (is_array($trans_html) && $trans_html[0] == false)
			{
				$this->out = '<h1>Translation time error: </h1>'.$this->text;
				return $this->out;
			}
			else
			{
				$this->out .= $trans_html;
			}
		}
		else
		{
			$next_html = '';
			/*此处是为处理html标签问题*/
			for($i = 0; $i <= $text_len; )
			{
				$next_len  = strlen($next_html);
				$cut_html  = substr($this->text, $i, $max_length-$next_len);				
				$cut_html  = $next_html . $cut_html;				
				$Start_Pos = intval(strrpos($cut_html, "<"));
				$End_Pos   = intval(strrpos($cut_html, ">"));
				
				if ($Start_Pos > $End_Pos && $End_Pos != 0)
				{
					$trans_html = substr($cut_html, 0, $End_Pos+1);
					$next_html  = substr($cut_html, $End_Pos+1, $max_length);
				}
				else
				{
					$trans_html = substr($cut_html, 0, $max_length);
					$next_html  = '';
				}
				$i += ($max_length-$next_len);
				
				$trans_html = $this->postPage($trans_html);
				
				if (is_array($trans_html) && $trans_html[0] == false)
				{
					$this->out = '<h1>Translation time error: </h1>'.$this->text;
					return $this->out;
				}
				else
				{
					$this->out .= $trans_html;
				}
			}
		}
		
		return $this->out;
	}
	function postPage($text)
	{
		$html = "";
		

		/*
		*hl – 界面语言，此处无用。
		*langpair – src lang to dest lang
		*ie – urlencode的编码方式
		*text – 要翻译的文本
		*/
		
		$fields = array('appId='.$this->appId, 'from='.$this->from, 'to='.$this->to, 'contentType=text/html', 'text='.urlencode($text));
		
		/*
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $this->translator_url.implode('&', $fields));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		
		$string = curl_exec($ch);

		if(curl_errno($ch))
		{
			$error = curl_error($ch);
			curl_close ($ch);
			return array(false, $error);
		}
		curl_close ($ch);
		return $this->decode_xml($string);
		;*/
		return $this->parse(file_get_contents($this->translator_url.implode('&', $fields)));
	}
	function decode_xml($xml)
	{
		
		$xml = xmlrpc_decode($xml);	
		
		return html_entity_decode($xml);
	}
	function parse($xml) 
	{
		$bad_chr = array("\x00" => "", "\x01" => "", "\x02" => "", "\x03" => "", "\x04" => "", "\x05" => "", "\x06" => "", "\x07" => "", "\x08" => "", "\x09" => "", "\x0a" => "", "\x0b" => "", "\x0c" => "", "\x0d" => "", "\x0e" => "", "\x0f" => "", "\x10" => "", "\x11" => "", "\x12" => "", "\x13" => "", "\x14" => "", "\x15" => "", "\x16" => "", "\x17" => "", "\x18" => "", "\x19" => "", "\x1a" => "", "\x1b" => "", "\x1c" => "", "\x1d" => "", "\x1e" => "", "\x1f" => "");
		
		$xml = strtr($xml, $bad_chr);
		
		$xml = substr($xml, strpos($xml, '>')+1);
		
		$xml = substr($xml, 0, strrpos($xml, '</'));
		
		return $xml;
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
