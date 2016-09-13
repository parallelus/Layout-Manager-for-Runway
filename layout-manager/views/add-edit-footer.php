<?php
global $shortname;

wp_enqueue_script( 'layout-edit-footer', FRAMEWORK_URL . 'extensions/layout-manager/js/layout-edit-footer.js', array(
	'jquery'
), '', true );
wp_localize_script( 'layout-edit-footer', 'layout_footer_data', array(
	'prefix' => $shortname . 'layout_footer_'
) );
?>
<form id="footer-add-edit"
      action="<?php echo $this->self_url(); ?>&navigation=footers-list&action=update-footer<?php echo isset( $footer ) ? '&old_alias=' . $footer['alias'] : ''; ?>"
      method="post">
	<input type="hidden" id="footer-alias" value="<?php echo isset( $_GET['alias'] ) ? $_GET['alias'] : ''; ?>">
	<?php require_once __DIR__ . '/footer-form.php'; ?>
	<input class="button-primary" type="button" id="save-button" value="<?php _e( 'Save Settings', 'runway' ) ?>">
</form>
