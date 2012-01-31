<?php 
class ControllerTaoapidowncategory extends Controller {
	private $error = array(); 
    
  	public function index() {
  		
		$this->load->language('taoapi/common');
		$this->load->language('taoapi/down_category');
		    	
		$this->data['error_warning']    = '';
		$this->data['success']          = '';
		$this->data['heading_title']    =  $this->language->get('heading_title');
		$url = '';
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_taoapi'),
			'href'      => 'javascript:;',
      		'separator' => ' :: '
   		);
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('taoapi/down_category', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('catalog/category');
				
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
   		
		$this->template = 'taoapi/down_category.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
			);
		$this->cache->delete('category');
		$this->response->setOutput($this->render());
  	}
  
	public function checkItemIsdown_category($taobao_num_iid)
	{
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE taobao_num_iid = '" . $taobao_num_iid ."'");
		
		if (isset($query->row['product_id']) && $query->row['product_id'] > 0) return true;
		else return false;
	}
}
?>