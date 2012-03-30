<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0, $limit = 15) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name) LIMIT 0, " . $limit);
		
		return $query->rows;
	}
	//Get All categories for homepage categories panel and categories dropdown menu
	public function getAllCategories($parent_id = 0, $limit = 15, $limit2 = 12, $limit3 = 12) {
		
		 $categories = $this->cache->get('allcategories');
        
        if (!$categories) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name) LIMIT 0, " . $limit);
		

			foreach ($query->rows as $category_1) {
				$level_2_data = array();
				
				$categories_2 = $this->getCategories($category_1['category_id'], $limit2);
				
				foreach ($categories_2 as $category_2) {
					$level_3_data = array();
					
					$categories_3 = $this->getCategories($category_2['category_id'], $limit3);
					
					foreach ($categories_3 as $category_3) {
						
						/*$level_4_data = array();

						$categories_4 = $this->getCategories($category_3['category_id']);
						
						foreach ($categories_4 as $category_4) {

							$level_4_data[] = array(
								'name' => $category_4['name'],
								'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'] . '_' . $category_4['category_id'])
							);
						}*/
						
						$level_3_data[] = array(
							'name' => $category_3['name'],
							//'children' => $level_4_data,
							'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'])
						);
					}
					
					$level_2_data[] = array(
						'name'     => $category_2['name'],
						'children' => $level_3_data,
						'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'])	
					);					
				}
				if(	   $category_1['category_id'] == 50025838 //Одежда
					|| $category_1['category_id'] == 50025839 //Обувь
					|| $category_1['category_id'] == 50025840 //Аксессуары
					|| $category_1['category_id'] == 50025842 //Красота и Здоровье
					|| $category_1['category_id'] == 50025842 //Подарки и сувениры
					|| $category_1['category_id'] == 50025842
					|| $category_1['category_id'] == 50025842
					|| $category_1['category_id'] == 50025842)
					$url_link = '#';
				else 
					$url_link = $this->url->link('product/category', 'path=' . $category_1['category_id']);

				$categories[] = array(
					'name'     => $category_1['name'],
					'children' => $level_2_data,
					'href'     => $url_link
				);
			}
            $this->cache->set('allcategories', $categories);
        }
		return $categories;
	}

	//Get all category tree
	public function getCategoryTree($parent_id = 0) {
		
		 $categories = $this->cache->get('cate-tree');
        
        if (!$categories) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name) ");
		

			foreach ($query->rows as $category_1) {
				$level_2_data = array();
				
				$categories_2 = $this->getCategories($category_1['category_id'], 100);
				
				foreach ($categories_2 as $category_2) {
					$level_3_data = array();
					
					$categories_3 = $this->getCategories($category_2['category_id'], 100);
					
					foreach ($categories_3 as $category_3) {
						
						$level_4_data = array();

						$categories_4 = $this->getCategories($category_3['category_id']);
						
						foreach ($categories_4 as $category_4) {
							$level_5_data = array();

							$categories_5 = $this->getCategories($category_4['category_id']);
							
							foreach ($categories_5 as $category_5) {

								$level_5_data[] = array(
									'name' => $category_5['name'],
									'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'] . '_' . $category_4['category_id'] . '_' . $category_5['category_id'])
								);
							}

							$level_4_data[] = array(
								'name' => $category_4['name'],
								'children' => $level_5_data,
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
				if(	   $category_1['category_id'] == 50025838 
					|| $category_1['category_id'] == 50025839
					|| $category_1['category_id'] == 50025840
					|| $category_1['category_id'] == 50025842)
					$url_link = '#';
				else 
					$url_link = $this->url->link('product/category', 'path=' . $category_1['category_id']);

				$categories[] = array(
					'name'     => $category_1['name'],
					'children' => $level_2_data,
					'href'     => $url_link
				);
			}
            $this->cache->set('cate-tree', $categories);
        }
		return $categories;
	}

	public function getCategoriesByParentId($category_id) {
		$category_data = array();
		
		$category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");
		
		foreach ($category_query->rows as $category) {
			$category_data[] = $category['category_id'];
			
			$children = $this->getCategoriesByParentId($category['category_id']);
			
			if ($children) {
				$category_data = array_merge($children, $category_data);
			}			
		}
		
		return $category_data;
	}
		
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_category');
		}
	}

	/**
	 * Find if there is category has same name
	 *
	 * @return category_id
	 * @author 
	 **/
	public function findSameCategory($category_name)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE UPPER(name) = UPPER('" . $category_name . "') AND language_id = '3'");
		
		return $query->row;
	}
					
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
}
?>