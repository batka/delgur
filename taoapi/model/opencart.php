<?
/**
 * Opencart Database Control class
 *
 * @package default
 * @author Batka
 */
class DBCtrlModel {
	
	public $db_api;
	public $config;
	
	public function __construct()
	{
		//error_reporting(1);
		if(session_id() == "")  session_start();
		
		$root_path = str_replace(array('taoapi\model\opencart.php', 'taoapi/model/opencart.php'), '', __FILE__);
		require_once($root_path.'config.php');	// the config is db_api config
		require_once($root_path.'taoapi/lib/mysql2.php');//die($root_path.'taoapi/lib/mysql.php');
		$this->db_api = new MySQL2(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$this->config = $this->getSettingByGroup('config');
		
	}
	/*取得系统语系列表*/
	public function getLanguages()
	{
		return $this->db_api->queryOptions('select language_id as Value, code as Title from '.DB_PREFIX.'language ');
	}
	/*取得预设的库存状态*/
	public function getSettingByGroup($group, $store_id = 0)
	{
		$Options = $this->db_api->queryOptions('select `key` as Value, `value` as Title '
											.'from '.DB_PREFIX. 'setting '
											." where `group` = '{$group}' and store_id = $store_id");
		return $Options;
	}
	/*取得分类*/
	public function getCategories($parent_id = 0)
	{
		$category_data = array();
		$languages = $this->getlanguages();		
		$language_id = array_search($this->config['config_admin_language'], $languages);
		
		$query = $this->db_api->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . $language_id . "' ORDER BY c.sort_order, cd.name ASC");
		$rows = $query->rows;
		foreach ($rows as $result)
		{
			$category_data[] = array(
				'category_id' => $result['category_id'],
				'name'        => $this->getPath($result['category_id'], $language_id),
				'status'  	  => $result['status'],
				'sort_order'  => $result['sort_order']
			);
			$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		
		return $category_data;
	}
	
	public function getPath($category_id, $language_id)
	{
		$query = $this->db_api->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$language_id . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $language_id) . ' > ' . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}
	public function checkItemIsDownload($taobao_num_iid)
	{
		$query = $this->db_api->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE taobao_num_iid = '" . $taobao_num_iid ."'");
		
		if (isset($query->row['product_id']) && $query->row['product_id'] > 0) return true;
		else return false;
	}
	public function getItemCached($taobao_num_iid)
	{
		$query = $this->db_api->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE taobao_num_iid = '" . $taobao_num_iid ."'");
		
		if (isset($query->row['product_id']) && $query->row['product_id'] > 0) return true;
		else return false;
	}
	public function saveAttribute()
	{
	
	}
	public function saveOptions()
	{
	
	}
	/*
	*	$data = array('score' => 5, 'nick' => 'shop name')
	*/
	public function saveTaobaoShop($data)
	{
		$query = $this->db_api->query("SELECT id FROM " . DB_PREFIX . "taobao_stores WHERE nick like '" . $data['nick'] ."'");
		
		if (isset($query->row['id']) && $query->row['id'] > 0)
		{
			$this->db_api->query("UPDATE " . DB_PREFIX . "taobao_stores SET score = '" . $data['score'] ."' WHERE id = '" . $query->row['id'] ."'");
		}
		else
		{
			$this->db_api->query("INSERT INTO " . DB_PREFIX . "taobao_stores SET score = '" . $data['score'] ."', nick = '" . $data['nick'] ."'");
		}
	}
	public function addCategories($data = array(), $parent_cid = 0)
	{		
		if (count($data) > 0)
		{
			$SQL1 = '';
			$SQL2 = '';
			$SQL3 = '';
			$inserted = 0;
			foreach ($data as $language_id => $arr)
			{
				foreach ($arr as $val)
				{
					$now = date('Y-m-d H:i:s', time());
					list($cid, $is_parent, $name) = explode(':', $val);
					if ($inserted == 0)
					{
						$cid        = intval($cid);
						$parent_cid = intval($parent_cid);
						$SQL1 .= "({$cid}, {$parent_cid}, {$cid}, {$parent_cid}, '{$now}', 1, '', '{$now}', 1),";
						$SQL3 .= "({$cid}, 0),";
					}
					$name = trim($name);
					$SQL2 .= "({$cid}, {$language_id}, '{$name}', '', '', ''),";
				} 
				$inserted = 1;
			}
			
			if ($SQL1 != '')
			{
				$sql = 'insert into ' . DB_PREFIX . 'category (`category_id`, `parent_id`, `taobao_cid`, `taobao_parent_cid`, `date_added`, `status`, `image`, `date_modified`, `top`) values ' . $SQL1;
				
				$sql = mb_substr($sql, 0, -1, 'utf-8');
				echo $sql;
				$this->db_api->query($sql);
			}
			
			if ($SQL2 != '')
			{
				$sql = 'insert into ' . DB_PREFIX . 'category_description (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) values ' . $SQL2;
				
				$sql = mb_substr($sql, 0, -1, 'utf-8');
				echo $sql;
				$this->db_api->query($sql);
			}
			if ($SQL3 != '')
			{
				$sql = 'insert into ' . DB_PREFIX . 'category_to_store (`category_id`, `store_id`) values ' . $SQL3;
				
				$sql = mb_substr($sql, 0, -1, 'utf-8');
				$this->db_api->query($sql);
			}
		}
	}
	public function editCategoryAjax($change, $id, $value, $language){
		
		if($change=="sort_order"){
			$sql = 'UPDATE ' . DB_PREFIX . 'category SET sort_order = "'. $value . '" WHERE category_id = "'. $id . '" ;';

			$sql = mb_substr($sql, 0, -1, 'utf-8');
			if($this->db_api->query($sql)){
				echo $value;
			}
		}
		if($language == "ru") $language = "3";
		if($language == "en") $language = "1";
		if($language == "cn") $language = "2";
		
		if($change=="cat_name"){
			$sql = 'UPDATE ' . DB_PREFIX . 'category_description SET name = "'. $value . '" WHERE category_id = "'. $id . '" AND language_id = "'. $language .'" ;';

			$sql = mb_substr($sql, 0, -1, 'utf-8');
			if($this->db_api->query($sql)){
				echo $value;
			}
		}
		//echo $id;
		/*if($this->db_api->query($sql)){
		
		$sql = 'SELECT sort_order FROM ' . DB_PREFIX . 'category WHERE category_id = "$id" ;';
		$sql = mb_substr($sql, 0, -1, 'utf-8');
		$result = $this->db_api->query($sql);
		$row = mysql_fetch_array($result)
		  
		echo $row['sort_order'][0];
		}*/
		//echo $value." id=".$id;
	}
	/*插入商品*/
	public function addProduct($data)
	{
		$this->db_api->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db_api->escape($data['model']) . "', sku = '" . $this->db_api->escape($data['sku']) . "', upc = '" . $this->db_api->escape($data['upc']) . "', location = '" . $this->db_api->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db_api->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db_api->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), price_cost = '" . (float)$data['price_cost'] . "', taobao_cid = '" . $data['taobao_cid'] . "', taobao_nick = '" . $data['taobao_nick'] . "', taobao_num_iid = '" . $data['taobao_num_iid'] . "', taobao_url = '" . $data['taobao_url'] . "', taobao_score = '" . $data['taobao_score'] . "'");
		
		$product_id = $this->db_api->getLastId();
		
		if (isset($data['image'])) {
			$this->db_api->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db_api->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db_api->escape($value['name']) . "', meta_keyword = '" . $this->db_api->escape($value['meta_keyword']) . "', meta_description = '" . $this->db_api->escape($value['meta_description']) . "', description = '" . $this->db_api->escape($value['description']) . "'");

			/*为了方便，所以就放到这里来了。*/
			if (isset($data['product_attribute']) && !empty($data['product_attribute'])) {
			
				foreach ($data['product_attribute'] as $product_attribute) {
					
					list($attribute_id, $attribute_values_id) = explode(':', $product_attribute);
					
					$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', text = '', attribute_group_id = '" . $data['taobao_cid'] . "', attribute_values_id = '" . (int)$attribute_values_id . "'");
				}
			}
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db_api->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
	}
	/*插入商品 从前台浏览页*/
	public function addProduct2($data)
	{
		$this->db_api->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db_api->escape($data['model']) . "', sku = '" . $this->db_api->escape($data['sku']) . "', upc = '" . $this->db_api->escape($data['upc']) . "', location = '" . $this->db_api->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db_api->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db_api->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), price_cost = '" . (float)$data['price_cost'] . "', taobao_cid = '" . $data['taobao_cid'] . "', taobao_nick = '" . $data['taobao_nick'] . "', taobao_num_iid = '" . $data['taobao_num_iid'] . "', taobao_url = '" . $data['taobao_url'] . "', taobao_score = '" . $data['taobao_score'] . "'");
		
		$product_id = $this->db_api->getLastId();
		
		if (isset($data['image'])) {
			$this->db_api->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db_api->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db_api->escape($value['name']) . "', meta_keyword = '" . $this->db_api->escape($value['meta_keyword']) . "', meta_description = '" . $this->db_api->escape($value['meta_description']) . "', description = '" . $this->db_api->escape($value['description']) . "', attribute = '" . $this->db_api->escape($value['attribute']) . "'");
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
	}
	/*插入语系商品 从前台浏览页*/
	public function addProduct3($data)
	{
		$this->db_api->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db_api->escape($data['model']) . "', sku = '" . $this->db_api->escape($data['sku']) . "', upc = '" . $this->db_api->escape($data['upc']) . "', location = '" . $this->db_api->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db_api->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db_api->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), price_cost = '" . (float)$data['price_cost'] . "', taobao_cid = '" . $data['taobao_cid'] . "', taobao_nick = '" . $data['taobao_nick'] . "', taobao_num_iid = '" . $data['taobao_num_iid'] . "', taobao_url = '" . $data['taobao_url'] . "', taobao_score = '" . $data['taobao_score'] . "'");
		
		$product_id = $this->db_api->getLastId();
		
		if (isset($data['image'])) {
			$this->db_api->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db_api->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db_api->escape($value['name']) . "', meta_keyword = '" . $this->db_api->escape($value['meta_keyword']) . "', meta_description = '" . $this->db_api->escape($value['meta_description']) . "', description = '" . $this->db_api->escape($value['description']) . "', attribute = '" . $this->db_api->escape($value['attribute']) . "'");
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db_api->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
	}
	
	function update_order($data)
	{
		foreach ($data as $v)
		{
			$this->db_api->query("UPDATE " . DB_PREFIX . "order_product SET shipping_satus = '1', shipping_time = '" . $v['consign_time'] . "' WHERE shipping_satus = 0 AND taobao_order = '".$v['tid']."'");
		}
		echo '<font color="red" size=5>更新完成</b>';
	}
}
?>

