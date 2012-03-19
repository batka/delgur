<?php  
class ControllerModuleCategory extends Controller {
	protected function index() {
		//loading category module
		$this->language->load('module/category');
		
		//heading title for category module
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		//if there is path in url then explode it by _ and give it to $parts
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
			$path = 0;
		}
		
		//if $parts[0] has value then give it to $this->data['category_id']
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		//if $parts[0] has value then give to it $this->data['child_id']
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
		
		//loading model catalog/category and catalog/product				
		//$this->load->model('catalog/category');
		//$this->load->model('catalog/product');
		
		//declare array $this->data['categories'] for categories
		$this->data['categories'] = array();
		
		//if last $parts[] isset then use this id to getCategories and give it to 
		//$children which is all the categories going to be on module category 
		/*if(isset($parts[count($parts)-1]))
			$children = $this->model_catalog_category->getCategories($parts[count($parts)-1]);
		else
			$children = $this->model_catalog_category->getCategories(0);
		*/

		$this->data['categories'] = $this->model_catalog_category->getAllCategories(0,10);
		//print_r($categories);
		
		//print_r($children);
		//foreach $children as $child 
		/*foreach ($children as $child) {
			$data = array(
				'filter_category_id'  => $child['category_id'],
				'filter_sub_category' => true
			);			
			$this->data['categories'][] = array(
				'category_id' => $child['category_id'],
				'name'        => $child['name'],
				'href'        => $this->url->link('product/category', 'path=' . $path . '_' . $child['category_id'])	
			);
		}*/

		//Custom function get children of $children
		/*foreach ($children as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_4_data = array();

					$categories_4 = $this->model_catalog_category->getCategories($category_3['category_id']);
					
					foreach ($categories_4 as $category_4) {

						$level_4_data[] = array(
							'name' => $category_4['name'],
							'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'] . '_' . $category_4['category_id'])
						);
					}
					
					$level_3_data[] = array(
						'name' => $category_3['name'],
						'children' => $level_4_data,
						'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'])
					);
				}
				
				$level_2_data[] = array(
					'name'     => $category_2['name'],
					'children' => $level_3_data,
					'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'])	
				);					
			}
			
			$this->data['categories'][] = array(
				'name'     => $category_1['name'],
				'children' => $level_2_data,
				'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'])
			);
		}*/
		
		$this->data['other_categories'] = array(
				'name' => $this->language->get('other_categories'),
				'href' => $this->url->link('information/categories')
		);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
  	}
}
?>