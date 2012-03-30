<?php 
	
	$api_path = str_replace(array('category\save.php', 'category/save.php'), '', __FILE__);
	//$api_path = '';
	include_once($api_path.'config.php');
	include_once($api_path.'lib/functions.php');
	include_once($api_path.'lib/page.Class.php');
	include_once($api_path.'lib/translator2.php');
	include_once($api_path.'model/opencart.php');
	
	//header("Content-Type:text/html;charset=UTF-8");
	//include_once($api_path.'check_auth.php');
	/* Build By fhalipay */
	
	/*$parent_cid    = empty($_REQUEST['parent_cid'])   ? '0' : intval($_REQUEST['parent_cid']);*/
	
	if (isset($_POST))
	{
		$DBCtrl = new DBCtrlModel();
		$languages = $DBCtrl->getLanguages();
		$store_config = $DBCtrl->getSettingByGroup('config');
		$language = $store_config['config_admin_language'];
		
		$DBCtrl->editCategoryAjax($_POST['change'], $_POST['id'], $_POST['value'], $language);
		
		
		//print_r($aCategories);die();
		
	}
?>