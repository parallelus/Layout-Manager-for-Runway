<?php
/**
 * Meta Fields - Content Options for Posts and Pages
 *
 * These fields allow the custom settings of content specific options
 * such as including a page in the search results, showing the post
 * title on the single post screen and disabling the auto paragraph
 * filters on content.
 *
 * TODO:
 * ................................................................
 * Still needs to have the Layout Manager meta options for layout
 * specific fields added. This includes the "Header", "Layout" and
 * "Footer" options. We might also include "Skin" for these layout
 * specific meta options.
 * ................................................................
 *
 */

#-----------------------------------------------------------------
# Custom Meta Fields
#-----------------------------------------------------------------

// Define Meta Fields
//................................................................
if ( ! function_exists( 'theme_portfolio_meta_box_content_options' ) ) :
function theme_portfolio_meta_box_content_options() {

	global $layout_manager_admin, $post, $layouts_manager;

	// Layout Manager Data
	//................................................................

	$headers = ( isset( $layout_manager_admin ) ) ? $layout_manager_admin->get_headers() : array();
	$footers = ( isset( $layout_manager_admin ) ) ? $layout_manager_admin->get_footers() : array();
	$layouts = ( isset( $layout_manager_admin ) ) ? $layout_manager_admin->get_layouts() : array();

	$header_layouts[] = __( '- Select -', 'runway' );
	if ( ! empty( $headers ) ) {
		foreach ( $headers as $key => $values ) {
			$header_layouts[ $values['alias'] ] = $values['title'];
		}
	}

	$body_layouts[] = __( '- Select -', 'runway' );
	if ( ! empty( $layouts ) ) {
		foreach ( $layouts as $key => $values ) {
			$body_layouts[ $values['alias'] ] = $values['title'];
		}
	}

	$footer_layouts[] = __( '- Select -', 'runway' );
	if ( ! empty( $footers ) ) {
		foreach ( $footers as $key => $values ) {
			$footer_layouts[ $values['alias'] ] = $values['title'];
		}
	}

	// Post Types
	//................................................................
	// Using this array you can specify the post types which should
	// contain these meta box options.
	$meta_postTypes = apply_filters( 'layout_manager_post_types', array( 'page', 'post' ) );

	// Meta Fields
	//................................................................
	$fields = array();
	if (
		isset( $layouts_manager->layouts_manager_options['settings']['headers'] )
		&& $layouts_manager->layouts_manager_options['settings']['headers'] == 'true'
	) {
		$fields[] = array(
			'name'    => __( 'Header', 'runway' ),
			'desc'    => '',
			'id'      => 'header',
			'type'    => 'select',
			'std'     => '',
			'options' => $header_layouts
		);
	}
	$fields[] = array(
		'name'    => __( 'Body', 'runway' ),
		'desc'    => ( ! isset( $layouts_manager->layouts_manager_options['settings']['footers'] ) || $layouts_manager->layouts_manager_options['settings']['footers'] != 'true' ) ?
			__( 'Select custom layout options.', 'runway' ) :
			'',
		'id'      => 'layout',
		'type'    => 'select',
		'std'     => '',
		'options' => $body_layouts
	);
	if (
		isset( $layouts_manager->layouts_manager_options['settings']['footers'] )
		&& $layouts_manager->layouts_manager_options['settings']['footers'] == 'true'
	) {
		$fields[] = array(
			'name'    => __( 'Footer', 'runway' ),
			'desc'    => __( 'Select custom layout options.', 'runway' ),
			'id'      => 'footer',
			'type'    => 'select',
			'std'     => '',
			'options' => $footer_layouts
		);
	}

	$fields[] = array(
		'name'    => __( 'Enable Auto Paragraphs', 'runway' ),
		'desc'    => __( 'Add &lt;p&gt; and &lt;br&gt; tags automatically.<br>(disabling may fix layout issues)', 'runway' ),
		'id'      => 'wpautop',
		'type'    => 'select',
		'std'     => '',
		'options' => array(
			'default' => __( '- Select -', 'runway' ),
			'on'      => __( 'On', 'runway' ),
			'off'     => __( 'Off', 'runway' )
		)
	);
	$fields[] = array(
		'name' => __( ' Hide Title', 'runway' ),
		'desc' => __( 'Hide the title for this page.', 'runway' ),
		'id'   => 'hide_title',
		'type' => 'checkbox',
		'std'  => ''
	);
	$fields[] = array(
		'name' => __( 'Exclude from Search', 'runway' ),
		'desc' => __( 'Hide this page from search results.', 'runway' ),
		'id'   => 'search_exclude',
		'type' => 'checkbox',
		'std'  => ''
	);

	//(isset($layouts_manager->layouts_manager_options['settings']['headers']) && $layouts_manager->layouts_manager_options['settings']['headers'] == 'true')
	$meta_box_content_options = array(
		'id'       => 'theme-meta-box-content-options',
		'title'    => __( 'Content Options', 'runway' ),
		'page'     => $meta_postTypes,
		'context'  => 'side',
		'priority' => 'default',
		'fields'   => $fields
	);

	$postID   = ( isset( $post ) ) ? $post->ID : 0; // NOTE: Shouldn't we refernece "GLOBAL" for the $post object?
	$postType = ( isset( $post ) ) ? $post->post_type : "";

	$layout_alias = get_post_meta( $postID, 'layout', true );
	//if page has empty layout - find layout in parent pages
	if ( $layout_alias === null || $layout_alias === false || $layout_alias === '' ) {
		$post_parents = get_post_ancestors( $postID );
		foreach ( $post_parents as $post_parent_key => $post_parent ) {
			$parent_layout_alias = get_post_meta( $post_parent, 'layout', true );
			if ( $parent_layout_alias !== null && $parent_layout_alias !== false && $parent_layout_alias !== '' ) {
				$layout_alias = $parent_layout_alias;
				$postID       = $post_parent;
				break;
			}
		}
	}

	if ( ! empty( $layout_alias ) ) {
		//get default values for layout
		$optional_labels = ( isset( $layout_manager_admin ) ) ?
			$layout_manager_admin->get_optional_labels( $layout_alias, $postID, $postType ) :
			array();
		//get overrides for current post or page
		$overrides_meta = get_post_meta( $postID, 'overrides', true );
		//check if current page has overrides and replace them into layout
		if (
			$overrides_meta != null && $overrides_meta != false
		    && ( is_array( $overrides_meta ) && count( $overrides_meta ) != 0 )
		) {
			if ( isset( $overrides_meta[ $layout_alias ] ) ) {
				foreach ( $overrides_meta[ $layout_alias ] as $override_key => $override_value ) {
					if ( isset( $optional_labels[ $override_key ] ) && $optional_labels[ $override_key ]['content_override'] == 'true' ) {
						$optional_labels[ $override_key ]['content_source'] = $override_value;
					}
				}
			}
		}

		foreach ( $optional_labels as $label_key => $label_value ) {
			$optional_label_type = ( isset( $layout_manager_admin ) ) ?
				$layout_manager_admin->get_content_elements( $label_value['content_type'] ) :
				array();
			if ( ! empty( $optional_label_type ) ) {
				$optional_label_layouts = array();
				foreach ( $optional_label_type as $key => $values ) {
					$optional_label_layouts[ $values['alias'] ] = $values['title'];
				}
				$optional_label[] = array(
					'name'     => rf__( $label_value['label'] ),
					'desc'     => '',
					'id'       => 'optional_label',
					'type'     => 'select',
					'std'      => '',
					'options'  => $optional_label_layouts,
					'selected' => $label_value['content_source'],
					'data'     => $label_key
				);
			}
		}
	}

	if ( isset( $optional_label ) ) {
		$meta_box_content_options['fields'] = array_merge( array_slice( $meta_box_content_options['fields'], 0, 2 ),
			$optional_label,
			array_slice( $meta_box_content_options['fields'], 2 ) );
	}

	return $meta_box_content_options;

}
endif;

// Add metabox to edit screen
//................................................................
if ( ! function_exists( 'theme_add_box_content_options' ) ) :
function theme_add_box_content_options( $postType ) {

	$meta_box_content_options = theme_portfolio_meta_box_content_options();
	$types                    = $meta_box_content_options['page'];

	if ( in_array( $postType, $types ) ) {
		add_meta_box(
			$meta_box_content_options['id'],
			$meta_box_content_options['title'],
			'theme_show_box_content_options',
			$postType,
			$meta_box_content_options['context'],
			$meta_box_content_options['priority']
		);
	}

}
add_action( 'add_meta_boxes', 'theme_add_box_content_options' );
endif;

// Output metabox options to edit screen
//................................................................
if ( ! function_exists( 'theme_show_box_content_options' ) ) :
function theme_show_box_content_options() {

	global $post;

	$meta_box_content_options = theme_portfolio_meta_box_content_options();

	// Use nonce for verification
	echo '<input type="hidden" name="theme_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
	wp_nonce_field( 'theme_meta_box_nonce', 'theme_meta_box_nonce_post' );

	$increment = 0;
	foreach ( $meta_box_content_options['fields'] as $field ) {
		// some styling
		$style = ( $increment && ! in_array( $field['id'], array(
				'header',
				'optional_label',
				'layout',
				'footer'
			) ) ) ? 'border-top: 1px solid #dfdfdf;' : '';
		// get current post meta data
		$meta = ( $field['id'] == 'optional_label' ) ? $field['selected'] : get_post_meta( $post->ID, $field['id'], true );
		if ( $meta === null || $meta === false || $meta === '' ) {
			$post_parents = get_post_ancestors( $post->ID );
			foreach ( $post_parents as $post_parent_key => $post_parent ) {
				$tmp_meta = ( $field['id'] == 'optional_label' ) ? $field['selected'] : get_post_meta( $post_parent, $field['id'], true );
				if ( $tmp_meta !== null && $tmp_meta !== false && $tmp_meta !== '' ) {
					$meta = $tmp_meta;
					break;
				}
			}
		}
		$data = isset( $field['data'] ) ? 'data-index="' . $field['data'] . '"' : '';
		switch ( $field['type'] ) {

			// Select box
			case 'select':
				$style_select = ( in_array( $field['id'], array(
					'header',
					'footer',
					'layout',
					'optional_label'
				) ) ) ? 'style="width:100%;"' : '';
				echo '<div class="metaField_field_wrapper metaField_field_' . $field['id'] . '" style="' . $style . '">',
					'<p><label for="' . $field['id'] . '"><strong>' . rf__( $field['name'] ) . '</strong></label></p>',
					'<select class="metaField_select" id="' . $field['id'] . '"  name="' . $field['id'] . '" ' . $style_select . ' ' . $data . '>';
				$count = 0;
				foreach ( $field['options'] as $key => $label ) {
					$selected = ( $meta == $key || ( ! $meta && ! $count ) ) ? 'selected="selected"' : '';
					echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
					$count ++;
				}
				echo '</select>';
				if ( $field['desc'] ) {
					echo '<p class="metaField_caption" style="color:#999">' . rf__( $field['desc'] ) . '</p>';
				}
				echo '</div>';

				break;

			// Radio group
			case 'radio':
				echo '<div class="metaField_field_wrapper metaField_field_' . $field['id'] . '" style="' . $style . '">',
					'<p><label for="' . $field['id'] . '"><strong>' . rf__( $field['name'] ) . '</strong></label></p>';
				$count = 0;
				foreach ( $field['options'] as $key => $label ) {
					$checked = ( $meta == $key || ( ! $meta && ! $count ) ) ? 'checked="checked"' : '';
					echo '<label class="metaField_radio" style="display: block; padding: 2px 0;"><input class="metaField_radio" type="radio" name="' . $field['id'] . '" value="' . $key . '" ' . $checked . '> ' . $label . '</label>';
					$count ++;
				}
				echo '<p class="metaField_caption" style="color:#999">' . rf__( $field['desc'] ) . '</p>',
				'</div>';

				break;

			// Checkbox
			case 'checkbox':
				$checked = ( $meta ) ? 'checked="checked"' : '';
				echo '<div class="metaField_field_wrapper metaField_field_' . $field['id'] . '" style="' . $style . '">',
				'<p>',
					'<label for="' . $field['id'] . '"><input class="metaField_checkbox" type="checkbox" id="' . $field['id'] . '" name="' . $field['id'] . '" value="1" ' . $checked . '> ' . rf__( $field['name'] ) . '</label></p>',
					'<p class="metaField_caption" style="color:#999">' . rf__( $field['desc'] ) . '</p>',
				'</div>';

				break;
		}

		$increment++;
	}

}
endif;

// Save meta data on submit
//................................................................
if ( ! function_exists( 'theme_save_data_content_options' ) ) :
function theme_save_data_content_options( $post_id ) {

	$meta_box_content_options = theme_portfolio_meta_box_content_options();
	// verify nonce
	if (
		! isset( $_POST['theme_meta_box_nonce'] )
		|| ! isset( $_POST['theme_meta_box_nonce_post'] )
		|| ! wp_verify_nonce( $_POST['theme_meta_box_nonce_post'], "theme_meta_box_nonce" )
	) {
		return $post_id;
	}

	// check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	foreach ( $meta_box_content_options['fields'] as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = ( isset( $_POST[ $field['id'] ] ) ) ? $_POST[ $field['id'] ] : false;

		//if page has created - save default values
		if ( ( $new && $new != $old ) || ( $new === '0' && $new != $old && isset( $_POST['save'] ) && ! isset( $_POST['publish'] ) ) ) {
			update_post_meta( $post_id, $field['id'], stripslashes( htmlspecialchars( $new ) ) );
		} elseif ( ( '' == $new || '0' == $new ) && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}

}
add_action( 'save_post', 'theme_save_data_content_options' );
endif;

#-----------------------------------------------------------------
# Search Exclude Filters
#-----------------------------------------------------------------

if ( ! is_admin() ) {
	// filter search results
	if ( ! function_exists( 'filter_search_exclude' ) ) :
	function filter_search_exclude( $where = '' ) {

		global $wpdb;

		// Meta values to look up
		$meta_key   = 'search_exclude';
		$meta_value = '1';

		// Query DB for meta setting 'search-exclude = "Yes"'
		$search_exclude_ids = $wpdb->get_col( $wpdb->prepare( "
		SELECT      post_id
		FROM        $wpdb->postmeta
		WHERE       meta_key = %s
		AND			meta_value = %s
		ORDER BY    post_id ASC",
			$meta_key, $meta_value ) );

		if ( is_search() && $search_exclude_ids ) {

			$exclude = $search_exclude_ids;

			for ( $x = 0; $x < count( $exclude ); $x ++ ) {
				$where .= " AND ID != " . $exclude[ $x ];
			}
		}

		return $where;

	}
	add_filter( 'posts_where', 'filter_search_exclude' );
	endif;
}

#-----------------------------------------------------------------
# Disable Auto Paragraphs (wpautop)
#-----------------------------------------------------------------

// Global wpautop default
//................................................................
// Set default auto paragraph option. This value can be specified
// in theme's function.php using the same code below.

if ( ! defined( 'WPAUTOP_DEFAULT' ) ) {
	define( 'WPAUTOP_DEFAULT', true );
} // true = enabled, false = disabled

// Auto Paragraphs Filter
//................................................................
//
if ( ! function_exists( 'wpautop_control_filter' ) ) :
function wpautop_control_filter( $content ) {

	global $post;

	// Check if a temporary $post reassignment is set. This lets the filter work on multiple content sources
	// in a single page by setting a temporary post object immediately before filters are applied. Basically
	// a complicated way to pass $post->ID with a global variable since it can't be directly passed.
	$the_post = ( isset( $GLOBALS['wpautop_post'] ) ) ? $GLOBALS['wpautop_post'] : $post;

	// Get wpautop setting
	$remove_filter = ( isset( $the_post ) ) ? wpautop_disable( $the_post->ID ) : 0;

	// turn on/off
	if ( $remove_filter ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
	} else {
		add_filter( 'the_content', 'wpautop' );
		add_filter( 'the_excerpt', 'wpautop' );
	}

	// destroy temporary items
	unset( $GLOBALS['wpautop_post'] );
	unset( $the_post );

	// return content
	return $content;
}
add_filter( 'the_content', 'wpautop_control_filter', 9 );
endif;

// Check Auto Paragraphs Setting
//................................................................

if ( ! function_exists( 'wpautop_disable' ) ) :
function wpautop_disable( $id = '' ) {

	// Get the page/post meta setting
	$post_wpautop_value = strtolower( get_post_meta( $id, 'wpautop', true ) );

	// Global default setting
	$default_wpautop_value = WPAUTOP_DEFAULT; // (true = autop is on)

	$remove_filter = false; // to match the WP default (false = enabled autop, true = disabled autop)

	// check if set at page level
	if ( in_array( $post_wpautop_value, array( 'true', 'on', 'yes' ) ) ) {
		$remove_filter = false;
	} elseif ( in_array( $post_wpautop_value, array( 'false', 'off', 'no' ) ) ) {
		$remove_filter = true;
	} else {
		// page/post level setting not found, use global setting
		$remove_filter = ! $default_wpautop_value;
	}

	return $remove_filter;

}
endif;
