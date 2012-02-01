<?php
final class Cart {
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
      		$this->session->data['cart'] = array();
    	}
	}
	      
  	public function getProducts() {
		$product_data = array();
		
		//print_r($this->session->data['cart']);
    	foreach ($this->session->data['cart'] as $key => $quantity) {
      		$product = explode(':', $key);
      		$product_id = $product[0];
			$stock = true;
			
			// Options
      		if (isset($product[1])) {
        		$options = unserialize(base64_decode($product[1]));
      		} else {
        		$options = array();
      		} 
			
			$lang_query = $this->db->query('select language_id as Value, code as Title from '.DB_PREFIX.'language ');
			$aLangs = array();
			foreach ($lang_query->rows as $result)
			{
				$aLangs[$result['Value']] = $result['Title'];
			}
			
			$language_cn_id = array_search('cn', $aLangs);
			
      		$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . $product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");
			
      		$cn_product_query = $this->db->query("SELECT taokeys, options FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "' AND 	language_id = '" . $language_cn_id . "'");
			
			if ($product_query->num_rows) {
      			$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

      			$option_data = array();
      			$cn_option_data = array();
				
				$taokeys     = unserialize($product_query->row['taokeys']);
				$tao_options = unserialize($product_query->row['options']);
				
				$cn_taokeys     = unserialize($cn_product_query->row['taokeys']);
				$cn_tao_options = unserialize($cn_product_query->row['options']);
				$sku = '';
      			foreach ($options as $product_option_id => $option_value)
				{
					$option_data[] = array(
						'name'  => $tao_options['options'][$product_option_id],
						'value' => $tao_options['values'][$product_option_id][$option_value]
					);
					$cn_option_data[] = array(
						'name'  => $cn_tao_options['options'][$product_option_id],
						'value' => $cn_tao_options['values'][$product_option_id][$option_value]
					);
					$sku .= $product_option_id .':'. $option_value . ';';
      			}
				
				if ($this->customer->isLogged()) {
					$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
				$sku = substr($sku, 0, -1);				
				$price = $this->loadSkuPrice($product_id, $sku);
				
				if (empty($price)) $price = $product_query->row['price'];
				//echo $price;
				// Product Discounts
				$discount_quantity = 0;
				
				foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
					$product_2 = explode(':', $key_2);
					
					if ($product_2[0] == $product_id) {
						$discount_quantity += $quantity_2;
					}
				}
				
				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (float)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
				
				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}
				
				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (float)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
			
				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}						
		
				// Reward Points
				$query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (float)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");
				
				if ($query->num_rows) {	
					$reward = $query->row['points'];
				} else {
					$reward = 0;
				}
				
				// Downloads		
				$download_data = array();     		
				
				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (float)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
				
				foreach ($download_query->rows as $download) {
        			$download_data[] = array(
          				'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask'],
						'remaining'   => $download['remaining']
        			);
				}
				
				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
					$stock = false;
				}
				
      			$product_data[$key] = array(
        			'key'             => $key,
        			'product_id'      => $product_query->row['product_id'],
        			'name'            => $product_query->row['name'],
        			'model'           => $product_query->row['model'],
					'shipping'        => $product_query->row['shipping'],
        			'image'           => $product_query->row['image'],
        			'option'          => $option_data,
        			'cn_option'       => $cn_option_data,
					'download'        => $download_data,
        			'quantity'        => $quantity,
        			'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
        			'price'           => $price,
        			'total'           => $price * $quantity,
					'reward'          => $reward * $quantity,
					'points'          => ($product_query->row['points'] + $option_points) * $quantity,
					'tax_class_id'    => $product_query->row['tax_class_id'],
        			'weight'          => ($product_query->row['weight'] + $option_weight) * $quantity,
        			'weight_class_id' => $product_query->row['weight_class_id'],
        			'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
        			'length_class_id' => $product_query->row['length_class_id'],
      			);
			} else {
				$this->remove($key);
			}
    	}
						
		return $product_data;
  	}
		  
  	public function add($product_id, $qty = 1, $options = array()) {
    	if (!$options) {
      		$key = $product_id;
    	} else {
      		$key = $product_id . ':' . base64_encode(serialize($options));
    	}
		
		if ((int)$qty && ((int)$qty > 0)) {
    		if (!isset($this->session->data['cart'][$key])) {
      			$this->session->data['cart'][$key] = (int)$qty;
    		} else {
      			$this->session->data['cart'][$key] += (int)$qty;
    		}
		}
  	}

  	public function update($key, $qty) {
    	if ((int)$qty && ((int)$qty > 0)) {
      		$this->session->data['cart'][$key] = (int)$qty;
    	} else {
	  		$this->remove($key);
		}
  	}

  	public function remove($key) {
		if (isset($this->session->data['cart'][$key])) {
     		unset($this->session->data['cart'][$key]);
  		}
	}
	
  	public function clear() {
		$this->session->data['cart'] = array();
  	}
	
  	public function getWeight() {
		$weight = 0;
	
    	foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
      			$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}
	
		return $weight;
	}
	
  	public function getSubTotal() {
		$total = 0;
		
		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
  	}
	
	public function getTaxes() {
		$tax_data = array();
		
		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['total'], $product['tax_class_id']);
				
				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
		}
		
		return $tax_data;
  	}

  	public function getTotal() {
		$total = 0;
		
		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
		}

		return $total;
  	}
  	
	public function getTotalRewardPoints() {
		$total = 0;
		
		foreach ($this->getProducts() as $product) {
			$total += $product['reward'];
		}

		return $total;
  	}
	  	
  	public function countProducts() {
		$product_total = 0;
			
		$products = $this->getProducts();
			
		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}		
					
		return $product_total;
	}
	  
  	public function hasProducts() {
    	return count($this->session->data['cart']);
  	}
  
  	public function hasStock() {
		$stock = true;
		
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
	    		$stock = false;
			}
		}
		
    	return $stock;
  	}
  
  	public function hasShipping() {
		$shipping = false;
		
		foreach ($this->getProducts() as $product) {
	  		if ($product['shipping']) {
	    		$shipping = true;
				
				break;
	  		}		
		}
		
		return $shipping;
	}
	
  	public function hasDownload() {
		$download = false;
		
		foreach ($this->getProducts() as $product) {
	  		if ($product['download']) {
	    		$download = true;
				
				break;
	  		}		
		}
		
		return $download;
	}
	
	function loadSkuPrice($product_id, $sku)
	{
		$dirname = ROOT_PATH.'cached/data/' . (date('Ymd', time())) .'/';
		$file = $dirname . $product_id . '_skus.txt';
		if (file_exists($file))
		{
			$sSku = file_get_contents($file);
			$aSku = unserialize($sSku);
			
			if (isset($aSku[$sku]['price']))
			{
				$price = $aSku[$sku]['price'];
				return $price;
			}
		}
		return '';
	}
}

?>