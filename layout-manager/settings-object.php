<?php

class Layout_Manager_Admin_Object extends Runway_Admin_Object {

	public $data, $elements, $builder_page, $option_key, $layouts_manager_options, $layouts_path;

	public function __construct($settings) {

		// Get parent constructor
		parent::__construct($settings);

		$this->option_key = $settings['option_key'];
		$this->layouts_manager_options = get_option( $this->option_key );
		$this->layouts_path = get_stylesheet_directory().'/data/layouts/';

		add_action('wp_ajax_get_layout_data', array($this, 'ajax_get_layout_data'));
		add_action('wp_ajax_save_layout', array($this, 'ajax_save_layout'));		
		add_action('wp_ajax_get_content_elements', array($this, 'ajax_get_content_elements'));

		add_action('wp_ajax_update_header', array($this, 'ajax_update_header'));
		add_action('wp_ajax_get_headers', array($this, 'ajax_get_headers'));		

		add_action('wp_ajax_update_footer', array($this, 'ajax_update_footer'));
		add_action('wp_ajax_get_footers', array($this, 'ajax_get_footers'));		

		add_action('wp_ajax_save_options_headers', array($this, 'save_options_headers'));		
		add_action('wp_ajax_save_options_footers', array($this, 'save_options_footers'));		
		add_action('wp_ajax_save_options_other_options', array($this, 'save_options_other_options'));

		add_action('wp_ajax_get_header_settings_form', array($this, 'get_header_settings_form'));		
		add_action('wp_ajax_get_footer_settings_form', array($this, 'get_footer_settings_form'));		

		add_action('wp_ajax_sanitize_title', array($this, 'sanitize_title'));

		add_action('wp_ajax_get_optional_labels', array($this, 'ajax_get_optional_labels'));
		add_action('wp_ajax_save_optional_labels', array($this, 'ajax_save_optional_labels'));

		add_action('wp_ajax_save_layouts_sort', array($this, 'ajax_save_layouts_sort'));

		add_action('after_setup_theme', array($this, 'backward_compatibility'), 99);

		$this->init_options();
	}	

	// Add hooks & crooks
	function add_actions() {
		$this->dynamic = true;
		// Init action
		add_action( 'init', array( $this, 'init' ) );		
	}

	function init() {
		// global $sidebar_settings;
		if ( isset( $_REQUEST['navigation'] ) && !empty( $_REQUEST['navigation'] ) ) {
			$this->navigation = $_REQUEST['navigation'];
		}


	}
        
	function init_options() {
		if(!isset($this->layouts_manager_options) || (isset($this->layouts_manager_options) && !isset($this->layouts_manager_options['settings']))) {
			$default_settings = array(
				'grid-structure' => 'bootstrap',
				'design-width' => 'bootstrap',
				'columns' => '12',
				'margins' => '50',
				'row-class' => 'row-fluid',
				'column-class-format' => 'span#',
				'min-column-size' => '1',
				'content-source-pages' => 'true',
				'content-source-sidebars' => 'true',
			);

			if(!isset($this->layouts_manager_options)) {
				add_option($this->option_key, array(
					'settings'=> $default_settings,
				), '', 'yes');
				$this->layouts_manager_options = get_option( $this->option_key );			
			}
			else {
				$this->layouts_manager_options['settings'] = $default_settings;
				update_option($this->option_key, $this->layouts_manager_options);
			}
		}
	}

	public function check_is_header_footer_rendered(){
		$path_to_theme = get_stylesheet_directory();
		$header_func = "do_action('output_layout','start')";
		$footer_func = "do_action('output_layout','end')";
		$this->header_err = $this->footer_err = false;
		
		if(!function_exists('WP_Filesystem'))
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem();
		global $wp_filesystem;
				
		if(file_exists($path_to_theme . '/header.php' ) ) {
			//$header_data = file_get_contents($path_to_theme . '/header.php');
			$header_data = $wp_filesystem->get_contents($path_to_theme . '/header.php');
			$this->header_err = (strstr($header_data, $header_func) == false)? true : false;
		} 
		if(file_exists($path_to_theme . '/footer.php' ) ) {
			//$footer_data = file_get_contents($path_to_theme . '/footer.php');
			$footer_data = $wp_filesystem->get_contents($path_to_theme . '/footer.php');
			$this->footer_err = (strstr($footer_data, $footer_func) == false)? true : false;
		} 		
	}

	function after_settings_init() {
		/* nothing */
  	}

  	function load_objects(){
  		
  	}

	function ajax_get_layout_data(){
		echo json_encode($this->get_layout($_REQUEST['alias']));
		die();
	}

	public function get_header_settings_form(){
		$alias = isset($_REQUEST['alias'])? $_REQUEST['alias'] : '';
		if(isset($alias) && $alias != ''){
			$header = isset($this->layouts_manager_options['headers'][$alias])? stripslashes_deep($this->layouts_manager_options['headers'][$alias]) : '';
			if(empty($header)){
				$header['alias'] = $alias;
			}
		}
		require_once('views/header-form.php');
		die();
	}

	public function get_footer_settings_form(){
		$alias = isset($_REQUEST['alias'])? $_REQUEST['alias'] : '';
		if(isset($alias) && $alias != ''){
			$footer = isset($this->layouts_manager_options['footers'][$alias])? stripslashes_deep($this->layouts_manager_options['footers'][$alias]) : '';
			if(empty($footer)){
				$footer['alias'] = $alias;
			}
		}
		require_once('views/footer-form.php');
		die();
	}	

	public function update_layout_settings($options){
		if(!empty($options)){
			$this->layouts_manager_options['settings'] = $options;
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;		
	}	

	public function validate_layout_settings($options){

		$this->layouts_manager_options['settings'] = $options;
		switch ($options['grid-structure']) {
			case 'bootstrap':
				$this->layouts_manager_options['settings']['row-class'] = in_array($options['row-class'], array('row','row-fluid') )? $options['row-class'] : 'row';
				$this->layouts_manager_options['settings']['column-class-format'] = 'span#';
				break;
			case '960':
				$this->layouts_manager_options['settings']['row-class'] = 'container_'.$options['columns'];
				$this->layouts_manager_options['settings']['column-class-format'] = 'grid_#';
				break;
			case 'unsemantic':
				$this->layouts_manager_options['settings']['row-class'] = $options['row-class'];
				$this->layouts_manager_options['settings']['column-class-format'] = 'grid-%';
				break;
			case 'custom':
				// TODO nothing
				break;
			default:
				// TODO nothing
				break;
		}
		return true;		
	}	

	// dual save
	private function save_layout($layout_alias = null){
		update_option($this->option_key, $this->layouts_manager_options);
		if($layout_alias != null){
			if( IS_CHILD && get_template() == 'runway-framework') {
				$file_name = $this->layouts_manager_options['layouts'][$layout_alias]['alias'].'.json';
				if(file_put_contents($this->layouts_path.$file_name, json_encode($this->layouts_manager_options['layouts'][$layout_alias]))){
					return true;
				} else {
					return false;
				}
			}
		}

	}


	/* ----Work with contexts---- */
	// Add or update context
	public function update_contexts($options = array()){	
		if(!empty($options)){
			$this->layouts_manager_options['contexts'] = $options;
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;	
	}

	// Get all contexts
	public function get_contexts() {		
		return isset($this->layouts_manager_options['contexts']) ? $this->layouts_manager_options['contexts'] : false;
	}


	/* ----Work with layouts---- */
	// Add or update layout
	public function update_layout($options = array()){	
		if(!empty($options)){
			$this->layouts_manager_options['layouts'][$options['alias']] = $options;
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;	
	}
	// Delete layout
	public function delete_layout($alias){
		if($alias != ''){
			if(isset($this->layouts_manager_options['layouts'][$alias])){
				unset($this->layouts_manager_options['layouts'][$alias]);
				update_option($this->option_key, $this->layouts_manager_options);
				return true;
			}
		}
		return false;
	}
	
	public function duplicate_layout($alias, $new_name) {
		$parent_layout = $this->layouts_manager_options['layouts'][$alias];
		$time = time();
		$parent_layout['title'] = $new_name;
		$parent_layout['alias'] = 'layout-'.$time;
		$this->layouts_manager_options['layouts']['layout-'.$time] = $parent_layout;
		
		global $shortname;
		$other_options = get_option($shortname.'other_options_'.$this->layouts_manager_options['layouts'][$alias]['alias']);
		$other_option_new_key = $shortname.'other_options_'.$this->layouts_manager_options['layouts']['layout-'.$time]['alias'];

		update_option($this->option_key, $this->layouts_manager_options);
		update_option($other_option_new_key, $other_options);

		return false;
	}

	// Get layout by alias
	public function get_layout($alias) {
		return (!empty($alias) && isset($this->layouts_manager_options['layouts'][$alias])) ? stripslashes_deep($this->layouts_manager_options['layouts'][$alias]) : false;
	}

	// Get all layouts
	public function get_layouts(){
		if(isset($this->layouts_manager_options['layouts'])){
			uasort($this->layouts_manager_options['layouts'], array($this, 'sort_layouts'));
			return stripslashes_deep($this->layouts_manager_options['layouts']);
		}
		else{
			return false;
		}
	}
	private function sort_layouts($a, $b) {
		if(!isset($a['title']) || !isset($b['title']))
			return 0;
		else if(isset($a['rank']) && isset($b['rank'])) {
				return $a['rank'] - $b['rank'];
			}
		else if(is_array($a['title']) || is_array($b['title']) || is_object($a['title']) || is_object($b['title']))
			return 0;
		else
			return strcmp($a['title'], $b['title']);
	}

	// Get optional labels by alias
	public function get_optional_labels( $alias, $post_id = null, $post_type = "" ){
		$optional_labels = array();
		$found_type = false;
		$found_id = false;
		
		if(isset($this->layouts_manager_options['layouts'][$alias]['body_structure']))		
			foreach( $this->layouts_manager_options['layouts'][$alias]['body_structure'] as $key_row => $value_row) {
				foreach( $value_row as $key_col => $value_col) {
					foreach( $value_col['content_elements'] as $key_el => $value_el) {
						if((isset($value_el['content_override']) && $value_el['content_override'] == 'true')) {
							$optional_label_type = $this->get_content_elements($value_el['content_type']);
							/*if($value_el['content_type'] === $post_type)
								$found_type = true;
							if(isset($value_el['content_source']) && $value_el['content_source'] == $post_id)
								$found_id = true;*/
							
							if(!empty($optional_label_type)) {
								$index = $alias.'_'.$key_row.'_'.$key_col.'_'.$key_el;
								$optional_labels[$index] = $value_el;
								$optional_labels[$index]['label'] = (!empty($value_el['content_label'])) ? $value_el['content_label'] : ucfirst($value_el['content_type']);
							}
						}
					}
				}
			}
			
		if($found_type && !$found_id) {
			$optional_labels = array();
		}
			
		return $optional_labels;
	}

	// Get optional labels by alias via ajax
	public function ajax_get_optional_labels(){
		$alias = $_REQUEST['alias'];
		$post_id = (isset($_REQUEST['post_ID'])) ? $_REQUEST['post_ID'] : null;
		$post_type = (isset($_REQUEST['postType'])) ? $_REQUEST['postType'] : "";
		
		$optional_labels = $this->get_optional_labels($alias, $post_id, $post_type);
		$overrides_meta = get_post_meta($post_id, 'overrides', true);
		if($overrides_meta != null && $overrides_meta != false &&
				(is_array($overrides_meta) && count($overrides_meta) != 0)) {
			if(isset($overrides_meta[$alias])) {
				foreach($overrides_meta[$alias] as $override_key => $override_value) {
					if(isset($optional_labels[$override_key]) && $optional_labels[$override_key]['content_override'] == 'true')
						$optional_labels[$override_key]['content_source'] = $override_value;
				}
			}
		}
		
		die(trim(json_encode($optional_labels)));
	}

	// Save layouts order after drag/drop sort
	public function ajax_save_layouts_sort(){
		$data = $_REQUEST;
		foreach($this->layouts_manager_options['layouts'] as $key => $val) {
			foreach( $data['ranks'] as $key_sort => $val_sort) {
				if($key == $val_sort) {
					$this->layouts_manager_options['layouts'][$key]['rank'] = $key_sort;
				}
			}
		}
		
		update_option($this->option_key, $this->layouts_manager_options);		
		die();
	}

	// Save optional labels for alias
	public function ajax_save_optional_labels(){
		$alias = $_REQUEST['alias'];	//layout identificator
		$source = $_REQUEST['source'];	//selected option from select
		$index = $_REQUEST['index'];	//name of select in "Content options" sidebar
		$post_id = (isset($_REQUEST['post_ID'])) ? $_REQUEST['post_ID'] : null;

		if($post_id === null || $post_id == -1) {
			foreach( $this->layouts_manager_options['layouts'][$alias]['body_structure'] as $key_row => $value_row) {
				foreach( $value_row as $key_col => $value_col) {
					foreach( $value_col['content_elements'] as $key_el => $value_el) {
						if($alias.'_'.$key_row.'_'.$key_col.'_'.$key_el == $index) {
							$this->layouts_manager_options['layouts'][$alias]['body_structure'][$key_row][$key_col]['content_elements'][$key_el]['content_source'] = $source;
						}
					}
				}
			}
			update_option($this->option_key, $this->layouts_manager_options);
		}
		else {
			$overrides_post_meta = get_post_meta($post_id, 'overrides');
			
			if($overrides_post_meta == null || $overrides_post_meta == false || 
				(is_array($overrides_post_meta) && count($overrides_post_meta) == 0)) {
				$overrides_meta = array();
				$overrides_meta[$alias] = array();
				$overrides_meta[$alias][$index] = $source;
				add_post_meta($post_id, 'overrides', $overrides_meta, true);
			}
			else {
				$overrides_meta = $overrides_post_meta[0];
				
				if(isset($overrides_meta[$alias])) {
					$overrides_meta[$alias][$index] = $source;
				}
				else {
					$overrides_meta[$alias] = array();
					$overrides_meta[$alias][$index] = $source;
				}
				update_post_meta($post_id, 'overrides', $overrides_meta);
			}
		}
		die();
	}

	function get_content_elements($source) {

		$return_value = array();

		switch ($source) {

			case 'post':{
				$args = array(
					'posts_per_page' => -1
				);
				$posts = get_posts($args);

				foreach ($posts as $key => $value) {
					$return_value[$key]['alias'] = $value->ID;
					$return_value[$key]['title'] = $value->post_title;
				}	
			} break;
			
			case 'page':{
				$pages = get_pages(array());
				$pages_forum = get_pages(array('post_type' => 'forum'));
				if(!empty($pages_forum) )
					$pages =  array_merge($pages, $pages_forum);
				foreach ($pages as $key => $value) {
					$return_value[$key]['alias'] = $value->ID;
					$return_value[$key]['title'] = $value->post_title;
				}	
			} break;
			
			case 'sidebar':{
				$sidebars = $GLOBALS['wp_registered_sidebars'];				
				foreach ( $sidebars as $key => $value ) { 
					$return_value[$key]['alias'] = $value['id'];
					$return_value[$key]['title'] = $value['name'];
				}
			} break;

			default:{ 
				$args = array(
					'posts_per_page' => -1,
					'post_type' => $source
				);
				$posts = get_posts($args);

				foreach ($posts as $key => $value) {
					$return_value[$key]['alias'] = $value->ID;
					$return_value[$key]['title'] = $value->post_title;
				}
			} break;
		}

		return $return_value;		
	}

	function ajax_get_content_elements() {

		$source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
		$return_value = $this->get_content_elements($source);
		die(trim(json_encode($return_value)));		
	}

	function ajax_save_layout(){
		$options = $_REQUEST;

		if($_REQUEST['alias'] == ''){			
			$options['alias'] = sanitize_title($_REQUEST['title']);
		}
		$this->update_layout($options);
		die();
	}

	/* ----Work with header---- */
	// Add or update header
	public function update_header($options = array()){
		if(!empty($options)){
			$this->layouts_manager_options['headers'][$options['alias']] = $options;
			update_option($this->option_key, $this->layouts_manager_options);
			return true;			
		}
		return false;
	}	
	// Delete header
	public function delete_header($alias){
		if($alias != '' && isset($this->layouts_manager_options['headers'])){
			unset($this->layouts_manager_options['headers'][$alias]);
			foreach($this->layouts_manager_options['layouts'] as $key => $value) {
				foreach($value as $layout_key => $layout_value) {
					if($layout_key == 'header' && $layout_value == $alias)
						$this->layouts_manager_options['layouts'][$key][$layout_key] = '';
				}
			}
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;
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
	// Get all headers
	public function get_headers(){ 
		if(isset($this->layouts_manager_options['headers'])){
			uasort($this->layouts_manager_options['headers'], array($this, 'sort_headers_by_title'));
			return stripslashes_deep($this->layouts_manager_options['headers']);
		}
		else{
			return false;
		}
	}
	private function sort_headers_by_title($a, $b) {
		if(!isset($a['title']) || !isset($b['title']))
			return 0;
		else if(is_array($a['title']) || is_array($b['title']) || is_object($a['title']) || is_object($b['title']))
			return 0;
		else
			return strcmp($a['title'], $b['title']);
	}
	
	// Add or update header by ajax request
	function ajax_update_header(){		
		$alias = isset($_REQUEST['alias']) ? $_REQUEST['alias'] : sanitize_title( $_REQUEST['title'] );

		$options = array(
			'alias' => $alias,
			'title' => $_REQUEST['title'],
		);

		$this->update_header($options);
		die();
	}
	
	function ajax_get_headers(){
		echo json_encode($this->get_headers());
		die();
	}

	/* ----Work with footers---- */
	// Add or update footer
	public function update_footer($options = array()){
		if(!empty($options)){
			$this->layouts_manager_options['footers'][$options['alias']] = $options;
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;
	}
	// Delete footer
	public function delete_footer($alias){
		if($alias != ''){
			unset($this->layouts_manager_options['footers'][$alias]);
			foreach($this->layouts_manager_options['layouts'] as $key => $value) {
				foreach($value as $layout_key => $layout_value) {
					if($layout_key == 'footer' && $layout_value == $alias)
						$this->layouts_manager_options['layouts'][$key][$layout_key] = '';
				}
			}			
			update_option($this->option_key, $this->layouts_manager_options);
			return true;
		}
		return false;
	}

	// Get all footers
	public function get_footers(){
		if(isset($this->layouts_manager_options['footers'])){
			uasort($this->layouts_manager_options['footers'], array($this, 'sort_footers_by_title'));
			return stripslashes_deep($this->layouts_manager_options['footers']);
		}
		else{
			return false;
		}
	}
	private function sort_footers_by_title($a, $b) {
		if(!isset($a['title']) || !isset($b['title']))
			return 0;
		else if(is_array($a['title']) || is_array($b['title']) || is_object($a['title']) || is_object($b['title']))
			return 0;
		else
			return strcmp($a['title'], $b['title']);
	}

	/* ---- Work with "Other Options" ---- */
	// Get "other options" by alias
	/*public function get_other_options($alias=false) {
		return get_options_data('other_options_'.$alias);
	}*/

	// Add or update header by ajax request
	public function ajax_update_footer(){
		global $form_builder;
		$alias = isset($_REQUEST['alias']) ? $_REQUEST['alias'] : sanitize_title( $_REQUEST['title'] );

		$options = array(
			'alias' => $alias,
			'title' => $_REQUEST['title'],
		);

		$this->update_footer($options);
		die();

	}

	function ajax_get_footers(){
		echo json_encode($this->get_footers());
		die();
	}

	public function save_options_headers(){
		$json_form = isset($_REQUEST['json_form']) ? $_REQUEST['json_form'] : '';
		$message = '';
		$page_id = '';
		if($json_form != ''){
			$this->layouts_manager_options['headers-options'] = json_decode(stripslashes($json_form), true);
			// out(json_decode(stripslashes($json_form))); die();
			update_option($this->option_key, $this->layouts_manager_options);

			$link = admin_url('themes.php?page=layout-manager&navigation=edit-options&option=headers');

			$return = array(
				'message' => $message,
				'page_id' => $page_id,
				'reload_url' => $link,
			);

			echo json_encode($return);
		} die();
	}

	public function save_options_footers(){
		$json_form = isset($_REQUEST['json_form']) ? $_REQUEST['json_form'] : '';
		$message = '';
		$page_id = '';		
		if($json_form != ''){
			$this->layouts_manager_options['footers-options'] = json_decode(stripslashes($json_form), true);
			update_option($this->option_key, $this->layouts_manager_options);

			$link = admin_url('themes.php?page=layout-manager&navigation=edit-options&option=footers');

			$return = array(
				'message' => $message,
				'page_id' => $page_id,
				'reload_url' => $link,
			);

			echo json_encode($return);
		} die();
	}

	public function save_options_other_options(){
		// TODO: ajax save other options
		$json_form = isset($_REQUEST['json_form']) ? $_REQUEST['json_form'] : '';
		$message = '';
		$page_id = '';		
		if($json_form != ''){
			$this->layouts_manager_options['other-options'] = json_decode(stripslashes($json_form), true);
			update_option($this->option_key, $this->layouts_manager_options);

			$link = admin_url('themes.php?page=layout-manager&navigation=edit-options&option=other-options');

			$return = array(
				'message' => $message,
				'page_id' => $page_id,
				'reload_url' => $link,
			);

			echo json_encode($return);
		} die();
	}
	
	public function sanitize_title(){
		$string = $_REQUEST['string'];
		if($string != ''){
			echo sanitize_title($string);
		}
		die();
	}

	function backward_compatibility(){
		if(isset($this->layouts_manager_options['headers-options']) && 
			$this->is_json($this->layouts_manager_options['headers-options'])){
			$this->layouts_manager_options['headers-options'] = json_decode(
				stripslashes( $this->layouts_manager_options['headers-options'] ), true);
			update_option($this->option_key, $this->layouts_manager_options);
		}
		
		if(isset($this->layouts_manager_options['footers-options']) && 
			$this->is_json($this->layouts_manager_options['footers-options'])){
			$this->layouts_manager_options['footers-options'] = json_decode(
				stripslashes( $this->layouts_manager_options['footers-options'] ), true);
			update_option($this->option_key, $this->layouts_manager_options);	
		}
		
		if(isset($this->layouts_manager_options['other-options']) && 
			$this->is_json($this->layouts_manager_options['other-options'])){
			$this->layouts_manager_options['other-options'] = json_decode(
				stripslashes($this->layouts_manager_options['other-options']), true);		
			update_option($this->option_key, $this->layouts_manager_options);
		}

		/* DISABLED - This was causing the demo-data.php layouts_manager key not to import in new installs.
		if(!isset($this->layouts_manager_options['settings'])){
			$default = json_decode(base64_decode('eyJncmlkLXN0cnVjdHVyZSI6ImJvb3RzdHJhcCIsImRlc2lnbi13aWR0aCI6IjEyMDAiLCJjb2x1bW5zIjoiMTIiLCJtYXJnaW5zIjoiNTAiLCJyb3ctY2xhc3MiOiJyb3ctZmx1aWQiLCJjb2x1bW4tY2xhc3MtZm9ybWF0Ijoic3BhbiMiLCJtaW4tY29sdW1uLXNpemUiOiIxIn0'), true);
			$this->layouts_manager_options['settings'] = $default;
			update_option($this->option_key, $this->layouts_manager_options);
		} */
	}

	public function is_json($string){			
		if(is_string($string)){
			return is_array(json_decode(stripslashes($string), true));			
		}
		else{
			return false;
		}
	}
        
    public function enqueue_wp_editor_scripts() {
        //initialize scripts for ajax text-editors
        if ( ! class_exists('_WP_Editors' ) )
            require_once( ABSPATH . WPINC . '/class-wp-editor.php' );

        $editor_id = 'content1';

        $set = array(
                'teeny' => false,
                'tinymce' => true,
                'quicktags' => false
        );

        $set = _WP_Editors::parse_settings($editor_id, $set);
        _WP_Editors::editor_settings($editor_id, $set);
    }

}
?>