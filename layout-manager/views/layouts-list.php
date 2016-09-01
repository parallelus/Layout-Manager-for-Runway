<div id="post-body">
	<div id="post-body-content" style="width: auto;">
		<br/>

		<?php if ( ! empty( $layouts ) ) { ?>
		<table class="wp-list-table widefat layouts-list-sortable" style="width: auto; min-width: 50%;">
			<thead>
			<tr>
				<th id="field-name" class="manage-column column-name"><?php _e( 'Title', 'runway' ); ?></th>
				<th id="field-header" class="manage-column column-header"><?php _e( 'Header', 'runway' ); ?></th>
				<th id="field-footer" class="manage-column column-footer"><?php _e( 'Footer', 'runway' ); ?></th>
				<th id="field-controls" class="manage-column column-controls" style="text-align:right;">&nbsp;</th>
			</tr>
			</thead>
			<tbody id="the-list">
			<?php
			$count = 0;
			foreach ( $layouts as $key => $values ) {
				$trClass = ( $count % 2 == 0 ) ? 'active alt' : 'active';
				?>
				<tr class="<?php echo $trClass ?>" data-sort-alias="<?php echo $values['alias']; ?>">
					<td class="column-name">
						<a href="<?php echo $this->self_url( 'edit-layout' ); ?>&alias=<?php echo $values['alias']; ?>">
							<strong><?php echo sprintf( __( '%s', 'runway' ), stripslashes( $values['title'] ) ); ?></strong>
						</a>
					</td>
					<td class="column-header">
						<?php echo ! empty( $values['header'] ) ? $values['header'] : ''; ?>
					</td>
					<td class="column-footer">
						<?php echo ! empty( $values['footer'] ) ? $values['footer'] : ''; ?>
					</td>
					<td class="column-controls" style="text-align:right;">
						<a href="<?php echo $this->self_url( 'edit-layout' ); ?>&alias=<?php echo $values['alias']; ?>">
							<?php _e( 'Edit', 'runway' ); ?>
						</a>
						|
						<a href="#" class="duplicate_layout" data-alias="<?php echo $values['alias']; ?>">
							<?php _e( 'Duplicate', 'runway' ); ?>
						</a>
						|
						<a style="color: #BC0B0B;" href="<?php echo $this->self_url(); ?>&navigation=confirm-delete-layout&alias=<?php echo $values['alias']; ?>">
							<?php _e( 'Delete', 'runway' ); ?>
						</a>
					</td>
				</tr>
				<?php
				$count++;
			} ?>
			</tbody>
			</table>

			<div style="display: none;" class="duplicate_layout_dialog">
				<form action="<?php echo $this->self_url( 'duplicate-layout' ); ?>" method="post">
					<input type="hidden" name="duplicated_alias" class="duplicated_alias"/>

					<label for="duplicated_name"><?php _e( 'Enter new layout title', 'runway' ); ?>: </label>
					<input type="text" value="" name="duplicated_name"/>

					<input type="submit" value="<?php _e( 'Duplicate', 'runway' ); ?>"/>
				</form>
			</div>
			<div style="display: none;" class="save-layouts-sort">
				<p>
					<input class="button-primary" value="<?php echo __( 'Save Layouts Order', 'runway' ); ?>">
				</p>
			</div>

			<br>

			<form action="<?php echo $this->self_url(); ?>&action=update-contexts" method="post">

				<?php

				// ==========================================
				// Layout Contexts
				// ==========================================

				// Defaults
				$default_contexts = array(
					'default' => array(
						'name'        => __( 'Default', 'runway' ),
						'description' => __( 'The default layout used when a layout is not assigned.', 'runway' )
					),
					'home'    => array(
						'name'        => __( 'Home Page', 'runway' ),
						'description' => __( 'The home page layout.', 'runway' )
					),
					'page'    => array(
						'name'        => __( 'Page', 'runway' ),
						'description' => __( 'Page layout.', 'runway' )
					),
					'post'    => array(
						'name'        => __( 'Single Post', 'runway' ),
						'description' => __( 'Single Post layout.', 'runway' )
					),
					'blog'    => array(
						'name'        => __( 'Blog', 'runway' ),
						'description' => __( 'The blog post list layout.', 'runway' )
					),
					// 'category' => array( 'name' => __('Category', 'runway'), 'description' => __('Optional', 'runway') ),
					// 'author'   => array( 'name' => __('Author', 'runway'), 'description' => __('Optional', 'runway') ),
					// 'tag'      => array( 'name' => __('Tag', 'runway'), 'description' => __('Optional', 'runway') ),
					// 'date'     => array( 'name' => __('Date', 'runway'), 'description' => __('Optional', 'runway') ),
					'search'  => array(
						'name'        => __( 'Search', 'runway' ),
						'description' => __( 'Search results layout.', 'runway' )
					),
					'error'   => array(
						'name'        => __( 'Error', 'runway' ),
						'description' => __( '404 error page layout.', 'runway' )
					),
				);

				$default_context_list = array_keys( $default_contexts ); // get keys for comparison with other lists

				// More contexts can be registered using the "register_context()" function
				// For reference see the function notes in 'template-functions/template-engine.php'

				// These are some contexts that might be in the theme or reason just needs to be restricted to prevent naming conflicts
				$ignore_context_list = array(
					'static_block'
				);

				// The list we get from the saved global array created by "register_context()"
				$custom_context_list = $GLOBALS['master_context_list'];

				// Output selects for default contexts
				// ---------------------------------------

				if ( ! empty( $default_contexts ) ) {

					echo '<div class="hr"></div> <br> <h3>' . __( 'Defaults', 'runway' ) . '</h3>';
					echo '<p>' . __( 'Layouts for the default areas of your site.', 'runway' ) . '</p>';
					echo '<table class="form-table">';

					foreach ( $default_contexts as $context => $details ) {

						// Skip anything that might be a duplicate of a default
						if ( ! in_array( $context, $ignore_context_list ) && $context ) {

							?>
							<tr class="">
								<th scope="row" valign="top">
									<span><?php rf_e( $details['name'] ); ?></span>
									<p class="description"></p>
								</th>
								<td>
									<select class="input-select" name="options[<?php echo $context; ?>]">
										<option value=""></option>
										<?php foreach ( $layouts as $key => $values ) {
											$selected = '';
											if ( ! empty( $contexts ) && $values['alias'] == $contexts[ $context ] ) {
												$selected = 'selected="selected"';
											}
											?>
											<option value="<?php echo $values['alias']; ?>" <?php echo $selected; ?>>
												<?php rf_e( stripslashes( $values['title'] ) ); ?>
											</option>
										<?php } ?>
									</select>
									<p class="description"><?php rf_e( $details['description'] ); ?></p>
								</td>
							</tr>
							<?php
						}
					}
					echo '</table>';
				}

				// Manually registered context/layouts
				// ---------------------------------------

				// get an array of manually registered contexts after subtracting all defaults and ignored context values
				$manual_context_list = array_flip( array_diff( (array) array_flip( (array) $custom_context_list['manual'] ), $default_context_list, $ignore_context_list ) );

				if ( ! empty( $manual_context_list ) ) {

					echo '<br> <div class="hr"></div> <br> <h3>' . __( 'Registered Templates', 'runway' ) . '</h3>';
					echo '<p>' . __( 'Additional layout assignments can be registered manually using the <code>register_context()</code> function. When registered they will appear in this area. ', 'runway' ) . '</p>';
					echo '<table class="form-table">';

					foreach ( $manual_context_list as $context => $name ) {

						// Skip anything that might be a duplicate of a default
						if ( ! in_array( $context, $default_context_list ) && ! in_array( $context, $ignore_context_list ) && $context ) {
							?>
							<tr class="">
								<th scope="row" valign="top">
									<span><?php rf_e( $name ); ?></span>
									<p class="description"></p>
								</th>
								<td>
									<select class="input-select" name="options[<?php echo $context; ?>]">
										<option value=""></option>
										<?php foreach ( $layouts as $key => $values ) {
											$selected = '';
											if ( ! empty( $contexts ) && isset( $contexts[ $context ] ) && $values['alias'] == $contexts[ $context ] ) {
												$selected = 'selected="selected"';
											}
											?>
											<option value="<?php echo $values['alias']; ?>" <?php echo $selected; ?>>
												<?php rf_e( $values['title'] ); ?>
											</option>
										<?php } ?>
									</select>
									<p class="description"></p>
								</td>
							</tr>
							<?php
						}
					}
					echo '</table>';

				}

				// Auto registered context/layouts (these might be from plugins, found by searching the WP post type object)
				// ---------------------------------------

				// get an array of manually registered contexts after subtracting all defaults and ignored context values
				$auto_context_list = isset( $custom_context_list['auto'] ) ? array_flip( array_diff( (array) array_flip( (array) $custom_context_list['auto'] ), $default_context_list, $ignore_context_list ) ) : array();

				if ( ! empty( $auto_context_list ) ) {

					echo '<br> <div class="hr"></div> <br> <h3>' . __( 'Custom Post Types (auto generated)', 'runway' ) . '</h3>';
					echo '<table class="form-table">';

					foreach ( $auto_context_list as $context => $name ) {

						// Skip anything that might be a duplicate of a default
						if ( ! in_array( $context, $default_context_list ) && ! in_array( $context, $ignore_context_list ) && $context ) {

							?>
							<tr class="">
								<th scope="row" valign="top">
									<span><?php rf_e( $name ); ?></span>
									<p class="description"></p>
								</th>
								<td>
									<select class="input-select" name="options[<?php echo $context; ?>]">
										<option value=""></option>
										<?php foreach ( $layouts as $key => $values ) {
											$selected = '';
											if ( ! empty( $contexts ) && isset( $contexts[ $context ] ) && $values['alias'] == $contexts[ $context ] ) {
												$selected = 'selected="selected"';
											}
											?>
											<option value="<?php echo $values['alias']; ?>" <?php echo $selected; ?>>
												<?php rf_e( $values['title'] ); ?>
											</option>
										<?php } ?>
									</select>
									<p class="description"></p>
								</td>
							</tr>
							<?php
						}
					}
					echo '</table>';
				} ?>

				<br>
				<p>
					<input class="button-primary" type="submit" value="<?php echo __( 'Save Settings', 'runway' ); ?>">
				</p>
				<br>

				<a name="register_custom_template_file"></a>
				<div class="meta-box-sortables metabox-holder">
					<div class="postbox">
						<div class="handlediv" title="<?php _e( 'Click to toggle', 'runway' ); ?>"><br></div>
						<h3 class="postbox-title">
							<span><?php _e( 'Register Custom Template Files', 'runway' ); ?></span></h3>
						<div class="inside">
							<?php
							echo '<p>' . __( 'To manually register a template file you can use the <code>register_context()</code> function. To do this add an entry to your <code>functions.php</code> file.', 'runway' ) . '</p>';
							echo '<p>' . __( 'Below is an example usage of how you might register a custom layout for a "news" category file. After you create a category with the slug "news" and add a file to the theme folder named <code>category-news.php</code> you would copy the following to your <code>functions.php</code> file.', 'runway' ) . '</p>';
							echo '<pre class="code" style="background: #F3F3F3; color: #333; padding: 10px; font-size: 11px; border: 1px solid #EAEAEA;">' .
							     '// Register custom template file for layout settings' . PHP_EOL .
							     '// -------------------------------------------------' . PHP_EOL .
							     ' ' . PHP_EOL .
							     '$news_template = locate_template(\'category-news.php\'); // returns full path of file in theme folder' . PHP_EOL .
							     'register_context( \'News Category\', \'category_news\', $news_template); // $name, $context, $template_file</pre>';

							?>
						</div>
					</div>
				</div>

			</form>

		<?php } else { ?>
			<h3><?php _e( 'You have not created any layouts yet.', 'runway' ); ?></h3>
		<?php } ?>

	</div> <!-- / #post-body-content -->
</div> <!-- / #post-body -->
