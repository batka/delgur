<?php  
class ControllerModuleEPHtml extends Controller {
	protected function index($setting) {
		
		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/html_module.css');
		
		$this->data['box_status'] = $setting['box_status'];
		
    	$this->data['heading'] = html_entity_decode($setting['heading'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
    	if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		//print_r($product_info);die();
		//$this->data['product_info'] = $product_info;
    	if (isset($this->request->get['nick'])) {
			$nick = $this->request->get['nick'];
		} elseif (isset($product_info['taobao_nick'])) {
			$nick = $product_info['taobao_nick'];
		} else {
			$nick = '';
		}

		$this->data['nick'] = $nick;
		if($nick != ''){
	    	$data = array(
					//'cid'        => $filter_category_id, 
					'keyword'    => $nick,
					'language'   => $_SESSION['language'], 
					'search_type'  => 'shop'
				);
			
			include_once(ROOT_PATH.'taoapi/web_tao/itemsofnick.php');
			$CallTaobao = new CallTaobao2;
			list($sub_categories, $items, $product_total) = $CallTaobao->QueryItems($data);
			//print_r($items); die();
			foreach ($items as $result) {
				
				$img_ext = '_60x60.jpg';
				$image = $result['image'].$img_ext;
				
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], 0, $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				$category_path = '';
				if (isset($this->request->get['path'])) $category_path = 'path=' . $this->request->get['path'];
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'nick'        => $result['nick'],
					'score'       => $result['score'],
					'description' => false,
					'price'       => $price,
					'special'     => false,
					'tax'         => false,
					'rating'      => 0,
					'reviews'     => false,
					'location_city' => $result['location_city'],
					'href'        => $this->url->link('product/product', $category_path . '&product_id=' . $result['product_id' ] . '&nick=' . $nick)
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ep_html.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ep_html.tpl';
		} else {
			$this->template = 'default/template/module/ep_html.tpl';
		}
		
		$this->render();
	}
}
?>