<?php

class Layout_Manager_Object extends Runway_Object {
	public $option_key, $layouts_manager_options;

	function __construct($settings) {

		$this->option_key = $settings['option_key'];
		$this->layouts_manager_options = get_option( $this->option_key );

	}

	// Get alias by context
	public function get_alias($context){
		if(isset($this->layouts_manager_options['contexts'][$context])){			
			return $this->layouts_manager_options['contexts'][$context];
		}
		else{
			return false;
		}
	}

	// Get layout by alias
	public function get_layout($alias) {
		return (!empty($alias) && isset($this->layouts_manager_options['layouts'][$alias])) ? stripslashes_deep($this->layouts_manager_options['layouts'][$alias]) : false;
	}

	public function get_layout_other_options($layout_alias = null){
		if($layout_alias != null){
			global $form_builder;			
			$alias = 'other_options_'.$layout_alias;
			$other_options = $form_builder->get_custom_options_vals($alias, true);
			if(is_array($other_options)) 
				return $other_options;
			else return false;
		}
	}
	
	// Get header by alias
	public function get_header($alias=false) {
		global $form_builder;
		$header = ($alias && isset($this->layouts_manager_options['headers'][$alias])) ? 
			stripslashes_deep($this->layouts_manager_options['headers'][$alias]) : false;
		
		if($header){
			$header['custom_options'] = $form_builder->get_custom_options_vals('layout_header_'.$alias, true);
		}

		return $header;
	}

	// Get footer by alias
	public function get_footer($alias=''){
		global $form_builder;		
		$footer = ($alias && isset($this->layouts_manager_options['footers'][$alias])) ? 
			stripslashes_deep($this->layouts_manager_options['footers'][$alias]) : false;

		if($footer){
			$footer['custom_options'] = $form_builder->get_custom_options_vals('layout_footer_'.$alias, true);
		}

		return $footer;
	}

} ?>