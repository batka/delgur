<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/sub_total');
		
		$sub_total = 0;
		foreach ($this->cart->getProducts() as $product) {
		
			$f_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), '', '', false);
			$sub_total += $this->currency->format($f_price*$product['quantity'], '', 1, FALSE);
			
			//$sub_total += $this->currency->format($product['total'], '', '', FALSE);
		}
		
		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}
		
		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total, '', 1),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
		$total += $sub_total;
	}
}
?>