<?php
global $form_builder, $layouts_manager;

if ( IS_CHILD ) {
	$this->check_is_header_footer_rendered();
	$this->view( 'header-footer-error', false, array(
		'header_err' => $this->header_err,
		'footer_err' => $this->footer_err
	) );
}

// Beadcrumbs
$navText = array();
switch ( $this->navigation ) {
	case 'add-layout':
		$navText = array( __( 'Add Layout', 'runway' ) );

		break;

	case 'edit-layout':
		$navText = array( __( 'Edit Layout', 'runway' ) );

		break;

	case 'headers-list':
	case 'add-header':
	case 'edit-header':
		$navText = array( __( 'Headers', 'runway' ) );

		break;

	case 'footers-list':
	case 'add-footer':
	case 'edit-footer':
		$navText = array( __( 'Footers', 'runway' ) );

		break;

	case 'settings':
		$navText = array( __( 'Layout Settings', 'runway' ) );

		break;

	case 'options-list' || 'edit-options':
		$parentNav = __( 'Options Fields', 'runway' );
		if ( isset( $_GET['option'] ) ) {
			$parentNav = '<a href="' . $this->self_url( 'options-list' ) . '">' . $parentNav . '</a>';
			if ( $_GET['option'] == 'headers' ) {
				$navText = array(
					$parentNav,
					__( 'Headers', 'runway' )
				);
			}
			if ( $_GET['option'] == 'footers' ) {
				$navText = array(
					$parentNav,
					__( 'Footers', 'runway' )
				);
			}
			if ( $_GET['option'] == 'other-options' ) {
				$navText = array(
					$parentNav,
					__( 'Layout', 'runway' )
				);
			}

		} else {
			$navText = array( $parentNav );
		}

		break;

}

// Output Breadcrumbs
if ( ! empty( $navText ) ) {
	$this->navigation_bar( $navText );
} else {
	echo '<p>&nbsp;</p>';
}

// Begin tabs
if ( ! in_array( $this->navigation, array(
	'add-layout',
	'edit-layout',
	'add-header',
	'add-footer',
	'edit-options'
) )
) {
	?>

	<h2 class="nav-tab-wrapper tab-controlls" style="padding-top: 9px;">
		<a href="<?php echo $this->self_url(); ?>"
		   class="nav-tab <?php if ( $this->navigation == '' || $this->navigation == 'duplicate-layout' ) {
			   echo "nav-tab-active";
		   } ?>"><?php _e( 'Layouts', 'runway' ) ?></a>

		<?php if ( isset( $this->layouts_manager_options['settings']['headers'] ) ) { ?>
			<a href="<?php echo $this->self_url( 'headers-list' ); ?>"
			   class="nav-tab <?php if ( $this->navigation == 'headers-list' || $this->navigation == 'edit-header' ) {
				   echo "nav-tab-active";
			   } ?>"><?php _e( 'Headers', 'runway' ) ?></a>
		<?php } ?>

		<?php if ( isset( $this->layouts_manager_options['settings']['footers'] ) ) { ?>
			<a href="<?php echo $this->self_url( 'footers-list' ); ?>"
			   class="nav-tab <?php if ( $this->navigation == 'footers-list' || $this->navigation == 'edit-footer' ) {
				   echo "nav-tab-active";
			   } ?>"><?php _e( 'Footers', 'runway' ) ?></a>
		<?php } ?>

		<?php if ( IS_CHILD && get_template() == 'runway-framework' ) { ?>
			<a href="<?php echo $this->self_url( 'settings' ); ?>"
			   class="nav-tab <?php if ( $this->navigation == 'settings' ) {
				   echo "nav-tab-active";
			   } ?>"><?php _e( 'Settings', 'runway' ) ?></a>
			<?php if (
				isset( $this->layouts_manager_options['settings']['headers'] )
				|| isset( $this->layouts_manager_options['settings']['footers'] )
				|| isset( $this->layouts_manager_options['settings']['other-options'] )
			) { ?>
				<a href="<?php echo $this->self_url( 'options-list' ); ?>"
				   class="nav-tab <?php if ( $this->navigation == 'options-list' ) {
					   echo "nav-tab-active";
				   } ?>"><?php _e( 'Custom Fields', 'runway' ) ?></a>
			<?php } ?>
		<?php } ?>

	</h2>

	<?php
}

// Set the alias variable
$alias    = isset( $_REQUEST['alias'] ) ? $_REQUEST['alias'] : '';
$js_alias = ( $alias ) ? $alias : 'false';
if ( $alias != '' ) {
	$layout = $this->get_layout( $alias );
}
echo '<script type="text/javascript">layoutAlias = "' . $js_alias . '";</script>';

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

if ( $action != '' ) {
	switch ( $action ) {
		case 'update-contexts':
			check_admin_referer( 'update-contexts-nonce', 'update-contexts-nonce' );

			$options = isset( $_REQUEST['options'] ) ? $_REQUEST['options'] : '';
			if ( $options != '' ) {
				$this->update_contexts( $options );
			}

			break;

		case 'update-header':
			check_admin_referer( 'update-header-nonce', 'update-header-nonce' );

			$title = isset( $_REQUEST['header-title'] ) ? $_REQUEST['header-title'] : '';
			$alias = isset( $_REQUEST['old_alias'] ) ? $_REQUEST['old_alias'] : sanitize_title( $_REQUEST['header-title'] );

			$custom_options = $form_builder->get_custom_options_vals( 'layout_header_' . $alias, true );

			if ( $title != '' && $alias != '' ) {
				$options = array(
					'title'          => $title,
					'alias'          => $alias,
					'custom_options' => $custom_options,
				);
				$this->update_header( $options );
			}

			break;

		case 'delete-header':
			check_admin_referer( 'delete-header' );

			$alias = isset( $_REQUEST['alias'] ) ? sanitize_title( $_REQUEST['alias'] ) : '';
			if ( $alias != '' ) {
				$this->delete_header( $alias );
			}

			break;

		case 'delete-layout':
			check_admin_referer( 'delete-layout' );

			$this->delete_layout( $_REQUEST['alias'] );

			break;

		case 'update-footer':
			check_admin_referer( 'update-footer-nonce', 'update-footer-nonce' );

			$title = isset( $_REQUEST['footer-title'] ) ? $_REQUEST['footer-title'] : '';
			$alias = isset( $_REQUEST['old_alias'] ) ? $_REQUEST['old_alias'] : sanitize_title( $_REQUEST['footer-title'] );

			$custom_options = $form_builder->get_custom_options_vals( 'layout_footer_' . $alias, true );

			if ( $title != '' && $alias != '' ) {
				$options = array(
					'title'          => $title,
					'alias'          => $alias,
					'custom_options' => $custom_options,
				);
				$this->update_footer( $options );
			}

			break;

		case 'delete-footer':
			check_admin_referer( 'delete-footer' );

			$alias = isset( $_REQUEST['alias'] ) ? sanitize_title( $_REQUEST['alias'] ) : '';
			if ( $alias != '' ) {
				$this->delete_footer( $alias );
			}

			break;

		case 'update-settings':
			if ( ! empty( $_POST ) ) {
				check_admin_referer( 'update-settings-nonce', 'update-settings-nonce' );

				$this->update_layout_settings( $_POST );
				$link     = admin_url( 'themes.php?page=layout-manager&navigation=settings&action=update-settings' );
				$redirect = '<script type="text/javascript">window.location = "' . $link . '";</script>';
				echo $redirect;
			}

			break;

		default:
			// nothing to do
			break;

	}

}

// Load the specific area
switch ( $this->navigation ) {
	case 'add-layout':
		$headers = $this->get_headers();
		$footers = $this->get_footers();
		$skins   = $this->get_skin_css();

		$layout = array(
			'title' => __( 'New Layout', 'runway' ),
			'alias' => 'layout-' . time(),
		);

		require_once( 'views/add-edit-layout.php' );

		break;

	case 'edit-layout':
		// TODO: save other options us layout options

		$headers = $this->get_headers();
		$footers = $this->get_footers();
		$skins   = $this->get_skin_css();

		$headers = stripslashes_deep( $headers );
		$footers = stripslashes_deep( $footers );

		$this->enqueue_wp_editor_scripts();

		require_once( 'views/add-edit-layout.php' );

		break;

	case 'duplicate-layout':
		check_admin_referer( 'duplicate-layout-nonce', 'duplicate-layout-nonce' );

		$this->duplicate_layout( $_POST['duplicated_alias'], $_POST['duplicated_name'] );

		$layouts  = $this->get_layouts();
		$contexts = $this->get_contexts();
		require_once( 'views/layouts-list.php' );

		break;

	case 'confirm-delete-layout':
		$item_confirm   = 'layout';
		$item_title     = $layouts_manager->layouts_manager_options['layouts'][ $alias ]['title'];
		$action_url_yes = admin_url( 'themes.php?page=layout-manager&navigation=layouts-list&action=delete-layout&alias=' . $alias . '&_wpnonce=' . wp_create_nonce( 'delete-layout' ) );
		$action_url_no  = admin_url( 'themes.php?page=layout-manager' );

		require_once( get_template_directory() . '/framework/templates/delete-confirmation.php' );

		break;

	case 'headers-list':
		$headers = $this->get_headers();
		if ( ! empty( $headers ) ) {
			foreach ( $headers as $key => $values ) {
				$values['title'] = stripslashes( $values['title'] );
				$headers[ $key ] = $values;
			}
		}

		require_once( 'views/headers-list.php' );

		break;

	case 'add-header':
		require_once( 'views/add-edit-header.php' );

		break;

	case 'edit-header':
		$alias = isset( $_REQUEST['alias'] ) ? $_REQUEST['alias'] : '';
		if ( $alias != '' ) {
			$header          = $layouts_manager->get_header( $alias );
			$header['title'] = stripslashes( $header['title'] );
		}
		require_once( 'views/add-edit-header.php' );

		break;

	case 'confirm-delete-header':
		$item_confirm   = 'header';
		$header_confirm = $layouts_manager->get_header( $alias );
		$item_title     = $header_confirm['title'];
		$action_url_yes = admin_url( 'themes.php?page=layout-manager&navigation=headers-list&action=delete-header&alias=' . $alias . '&_wpnonce=' . wp_create_nonce( 'delete-header' ) );
		$action_url_no  = admin_url( 'themes.php?page=layout-manager&navigation=headers-list' );

		require_once( get_template_directory() . '/framework/templates/delete-confirmation.php' );

		break;

	case 'footers-list':
		$footers = $this->get_footers();
		if ( ! empty( $footers ) ) {
			foreach ( $footers as $key => $values ) {
				$values['title'] = stripslashes( $values['title'] );
				$footers[ $key ] = $values;
			}
		}

		require_once( 'views/footers-list.php' );

		break;

	case 'add-footer':
		require_once( 'views/add-edit-footer.php' );

		break;

	case 'edit-footer':
		$alias = isset( $_REQUEST['alias'] ) ? $_REQUEST['alias'] : '';
		if ( $alias != '' ) {
			$footer          = $layouts_manager->get_footer( $alias );
			$footer['title'] = stripslashes( $footer['title'] );
		}
		require_once( 'views/add-edit-footer.php' );

		break;

	case 'confirm-delete-footer':
		$item_confirm   = 'footer';
		$footer_confirm = $layouts_manager->get_footer( $alias );
		$item_title     = $footer_confirm['title'];
		$action_url_yes = admin_url( 'themes.php?page=layout-manager&navigation=footers-list&action=delete-footer&alias=' . $alias . '&_wpnonce=' . wp_create_nonce( 'delete-footer' ) );
		$action_url_no  = admin_url( 'themes.php?page=layout-manager&navigation=footers-list' );

		require_once( get_template_directory() . '/framework/templates/delete-confirmation.php' );

		break;

	case 'settings':
		$this->view( 'settings', false, array( 'settings' => isset( $this->layouts_manager_options['settings'] ) ? $this->layouts_manager_options['settings'] : '' ) );

		break;

	case 'edit-options':
		$this->view( 'edit-options' );

		break;

	case 'options-list':
		$this->view( 'options-list' );

		break;

	default:
		$layouts  = $this->get_layouts();
		$contexts = $this->get_contexts();
		require_once( 'views/layouts-list.php' );

}
