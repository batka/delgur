<?php
class ControllerModuleSearchSuggestion extends Controller {
	protected function index() {
		
		//########################################################################
		// Module: Search Autocomplete
		//########################################################################
		$this->data['search_json'] = HTTP_SERVER . 'index.php?route=product/search_json';
		//########################################################################
		// Module: Search Autocomplete
		//########################################################################
		
		$this->id = 'search_suggestion';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/search_suggestion.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/search_suggestion.tpl';
		} else {
			$this->template = 'default/template/module/search_suggestion.tpl';
		}

		$this->render();
	}
}
?>