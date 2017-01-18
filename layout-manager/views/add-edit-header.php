<?php
global $shortname;

wp_enqueue_script( 'layout-edit-header', FRAMEWORK_URL . 'extensions/layout-manager/js/layout-edit-header.js', array(
	'jquery'
), '', true );
wp_localize_script( 'layout-edit-header', 'layout_header_data', array(
	'prefix' => $shortname . 'layout_header_'
) );
?>
<form id="header-add-edit"
      action="<?php echo $this->self_url(); ?>&navigation=headers-list&action=update-header<?php echo isset( $header ) ? '&old_alias=' . $header['alias'] : ''; ?>"
      method="post">
	<?php 
	require_once __DIR__ . '/header-form.php'; 
	wp_nonce_field( 'update-header-nonce' ,'update-header-nonce' );
	?>
	<input type="hidden" id="header-alias" value="<?php echo ( isset( $_GET['alias'] ) ) ? $_GET['alias'] : ''; ?>">
	<input class="button-primary" type="button" id="save-button" value="<?php _e( 'Save Settings', 'runway' ); ?>">
</form>
