<?php
/*
    Extension Name: Layout Manager
    Extension URI: https://github.com/parallelus/Layout-Manager-for-Runway
    Version: 0.9.4
    Description: Build advanced WordPress templates with drag and drop controls.
    Author: Parallelus
    Author URI: http://runwaywp.com
*/

// Settings
$fields = array(
		'var' => array(),
		'array' => array()
);
$default = array();

$settings = array(
	'name' => __('Layout Manager', 'framework'), 
	'option_key' => $shortname.'layouts_manager',
	'fields' => $fields,
	'default' => $default,
	'parent_menu' => 'appearance',
	'wp_containers' => 'none',
	'file' => __FILE__,
	'js' => array(
		'wp-color-picker',
		'jquery',
		'formsbuilder',
		'ace',
		'rw_nouislider',
		'tiny_mce',
		'editor',
		FRAMEWORK_URL.'extensions/layout-manager/js/jquery-ui-1.10.1.custom.min.js',
		FRAMEWORK_URL.'extensions/layout-manager/js/layout-builder-plugin.js',
		FRAMEWORK_URL.'extensions/layout-manager/js/layout-manager.js',
		FRAMEWORK_URL.'extensions/layout-manager/js/layout-sort.js',
	),
	'css' => array(
		'wp-color-picker',
		'formsbuilder-style',
		'rw_nouislider_css',
		FRAMEWORK_URL.'extensions/layout-manager/css/styles.css',
	),
);

// Required components
include('object.php');
global $layouts_manager, $layout_manager_admin;
$layouts_manager = new Layout_Manager_Object($settings);

if(!is_admin() && !strstr($_SERVER['PHP_SELF'], 'wp-login')){

	if(IS_CHILD && isset($layouts_manager->layouts_manager_options['settings']))
	switch ($layouts_manager->layouts_manager_options['settings']['grid-structure']) {
		case 'bootstrap': {
			add_action('init', 'enqueue_bootstrap');
		} break;
		case '960': {
			add_action('init', 'enqueue_960');
		} break;
		case 'unsemantic': {
			add_action('init', 'enqueue_unsemantic');
		} break;
		case 'custom': {
			add_action('init', 'enqueue_custom_grid');
		} break;
		default: {
			// Nothing to do
		} break;
	} 

	//add_action('init', 'enqueue_bootstrap');

	function enqueue_bootstrap(){		

		// include twitter bootsrap styles
		wp_enqueue_style( 'bootstrap_responsive_css', FRAMEWORK_URL.'extensions/layout-manager/css/bootstrap/css/bootstrap-responsive.min.css' );
		wp_enqueue_style( 'bootstrap_css', FRAMEWORK_URL.'extensions/layout-manager/css/bootstrap/css/bootstrap.min.css' );
		// include twitter bootsrap script
		wp_enqueue_script('bootstrap_js', FRAMEWORK_URL.'extensions/layout-manager/css/bootstrap/js/bootstrap.min.js', array('jQuery'));
	}

	function enqueue_960(){		

		// include 960 grid system styles
		wp_enqueue_style( 'reset_css', FRAMEWORK_URL.'extensions/layout-manager/css/960/css/min/reset.css' );
		wp_enqueue_style( 'text_css', FRAMEWORK_URL.'extensions/layout-manager/css/960/css/min/text.css' );
		wp_enqueue_style( '960_css', FRAMEWORK_URL.'extensions/layout-manager/css/960/css/min/960.css' );
	}

	function enqueue_unsemantic(){		

		// include unsemantic styles
		wp_enqueue_style( 'unsemantic_responsive_css', FRAMEWORK_URL.'extensions/layout-manager/css/unsemantic/stylesheets/unsemantic-grid-responsive.css' );
		wp_enqueue_style( 'unsemanic_css', FRAMEWORK_URL.'extensions/layout-manager/css/unsemantic/stylesheets/unsemantic-grid-base.css' );
	}

	function enqueue_custom_grid(){		

		// include custom styles
		// TODO nothing
	}

}

// Load admin components
if (is_admin()) {	
	include('settings-object.php');
	$layout_manager_admin = new Layout_Manager_Admin_Object($settings);
	add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	add_action( 'admin_enqueue_scripts', 'add_optional_label');
}

function add_optional_label() {
	wp_enqueue_style( 'style', FRAMEWORK_URL.'extensions/layout-manager/css/admin-styles.css' );
	wp_enqueue_script('optional_label', FRAMEWORK_URL.'extensions/layout-manager/js/optional-label.js', 'jquery');
}

// Setup a custom button in the title
function title_button_new_layout( $title ) {
	$page = (isset($_GET['page'])) ? $_GET['page'] : 'layout-manager';
	$navigation = (isset($_GET['navigation'])) ? $_GET['navigation'] : '';

	if ( $page == 'layout-manager' && $navigation == 'headers-list' ) {
		$title .= ' <a href="?page=layout-manager&navigation=add-header" class="add-new-h2">'. __( 'Add New Header', 'framework' ) .'</a>';
	} elseif ( $page == 'layout-manager' && $navigation == 'footers-list' ) {
		$title .= ' <a href="?page=layout-manager&navigation=add-footer" class="add-new-h2">'. __( 'Add New Footer', 'framework' ) .'</a>';
	} elseif (IS_CHILD && get_template() == 'runway-framework' && $page == 'layout-manager' && in_array( $navigation, array('add-layout', 'edit-layout', 'add-header', 'edit-header', 'add-footer', 'edit-footer'))) {
		$title .= ' <a href="#" title="'. __( 'Show or hide the developer information.', 'framework' ) .'" class="add-new-h2" id="ToggleDevMode">'. __( 'Toggle Developer Info', 'framework' ) .'</a>';
	} elseif ( $page == 'layout-manager' && !in_array($navigation, array('add-header', 'edit-header', 'add-footer', 'edit-footer', 'settings', 'options-list'))) {
		$title .= ' <a href="?page=layout-manager&navigation=add-layout" class="add-new-h2">'. __( 'Add New Layout', 'framework' ) .'</a>';
	}		
	
	return $title;
}
add_filter( 'framework_admin_title', 'title_button_new_layout' );

// Include template functions for themes, meta fields and layout output
include('template-functions/template-engine.php');
include('template-functions/meta-content-options.php');
include('template-functions/layout-actions.php');

add_action('add_report', 'layouts_manager_report');

function layouts_manager_report($reports_object){
	$layouts_dir = get_stylesheet_directory() . '/data/layouts/';
	$reports_object->assign_report(array(
		'source' => 'Layouts Manager',
		'report_key' => 'layouts_dir_exists',
		'path' => $layouts_dir,
		'success_message' => __('Layouts dir', 'framework') . ' (' . $layouts_dir . ') ' . __('is exists', 'framework') . '.',
		'fail_message' => __('Layouts dir', 'framework') . ' (' . $layouts_dir . ') ' . __('is not exists', 'framework') . '.',
	), 'DIR_EXISTS' );

	$reports_object->assign_report(array(
		'source' => 'Layouts Manager',
		'report_key' => 'layouts_dir_writable',
		'path' => $layouts_dir,
		'success_message' => __('Layouts dir', 'framework') . ' (' . $layouts_dir . ') ' . __('is writable', 'framework') . '.',
		'fail_message' => __('Layouts dir', 'framework') . ' (' . $layouts_dir . ') ' . __('is not writable', 'framework') . '.',
	), 'IS_WRITABLE' );	
}

do_action('layouts_manager_is_loaded');

?>