<?php 
class ControllerTaoapiDownload extends Controller {
	private $error = array(); 
    
  	public function index() {
  		
		$this->load->language('taoapi/common');
		$this->load->language('taoapi/download');
		    	
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
			'href'      => $this->url->link('taoapi/download', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('catalog/category');
				
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
   		
		$this->template = 'taoapi/download.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}
  
	public function checkItemIsDownload($taobao_num_iid)
	{
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE taobao_num_iid = '" . $taobao_num_iid ."'");
		
		if (isset($query->row['product_id']) && $query->row['product_id'] > 0) return true;
		else return false;
	}
}
?>