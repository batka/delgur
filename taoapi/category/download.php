<?php 
	
	$api_path = str_replace(array('category\download.php', 'category/download.php'), '', __FILE__);
	//$api_path = '';
	include_once($api_path.'config.php');
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/page.Class.php');
	include_once($api_path.'lib/translator2.php');
	include_once($api_path.'model/opencart.php');
	
	header("Content-Type:text/html;charset=UTF-8");
//	include_once($api_path.'check_auth.php');
	/* Build By fhalipay */
	
	$parent_cid    = empty($_REQUEST['parent_cid'])   ? '0' : intval($_REQUEST['parent_cid']);
	
	if (isset($_POST))
	{
		$DBCtrl = new DBCtrlModel();
		$languages = $DBCtrl->getLanguages();
		$store_config = $DBCtrl->getSettingByGroup('config');
		
		$Translator = new Translator;
		
		$sCategories = implode("','", $_POST['cid']);
		$sCategories = $sCategories;
		$sCategories = str_replace(':', "|", $sCategories);
		$aCategories = array();
		
		if (count($languages) > 0)
		{
			foreach ($languages as $lang_id => $code2)
			{
				$aCategories[$lang_id] = array();
				$aCategories[$lang_id] = $_POST['cid'];
				/*
				if (strpos(strtolower($code2), 'cn') === false)
				{
					//翻译标题
					$Translator->setText($sCategories);
					
					$str = str_replace(array('&#39;', "'"), '', $Translator->translate('zh-CN', $code2));
					$str = str_replace('|', ':', $str);
					$arr = explode(',', $str);
					
					$aCategories[$lang_id] = $_POST['cid'];
				}
				else
				{
					$aCategories[$lang_id] = $_POST['cid'];
				}
				*/
			}
		}
		//print_r($aCategories);die();
		$DBCtrl->addCategories($aCategories, $parent_cid);
	}
?>