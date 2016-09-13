<?php
wp_enqueue_script( 'layout-settings', FRAMEWORK_URL . 'extensions/layout-manager/js/layout-settings.js', array(
	'jquery'
), '', true );
?>

<form action="<?php echo $this->self_url(); ?>&navigation=settings&action=update-settings" method="post">
	<h3><?php _e( 'Layout Sections', 'runway' ); ?></h3>
	<table class="form-table">
		<tbody>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Headers', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true"
					       name="headers" <?php echo ( isset( $settings['headers'] ) && $settings['headers'] == 'true' ) ? 'checked' : ''; ?>>
					<?php _e( 'Enable Layout Headers', 'runway' ); ?>
				</label>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Footers', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true"
			              name="footers" <?php echo ( isset( $settings['footers'] ) && $settings['footers'] == 'true' ) ? 'checked' : ''; ?>>
					<?php _e( 'Enable Layout Footers', 'runway' ); ?>
				</label>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Layout Options', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true"
			              name="other-options" <?php echo ( isset( $settings['other-options'] ) && $settings['other-options'] == 'true' ) ? 'checked' : ''; ?>>
					<?php _e( 'Enable Layout Options', 'runway' ); ?>
				</label>
			</td>
		</tr>
		</tbody>
	</table>

	<h3><?php _e( 'Layout Structure', 'runway' ); ?></h3>
	<table class="form-table">
		<tbody>
		<tr class="">
			<th scope="row" valign="top">
				<span><?php _e( 'Grid structure', 'runway' ); ?></span>
				<p class="description"></p>
			</th>
			<td>
				<select id="grid-structure" class="input-select" name="grid-structure">
					<option	value="bootstrap"
						<?php echo ( isset( $settings['grid-structure'] ) && $settings['grid-structure'] == 'bootstrap' ) ? 'selected="selected"' : ''; ?>>
						<?php _e( 'Bootstrap (default)', 'runway' ); ?>
					</option>
					<option	value="960"
						<?php echo ( isset( $settings['grid-structure'] ) && $settings['grid-structure'] == '960' ) ? 'selected="selected"' : ''; ?>>
						<?php _e( '960 Grid System', 'runway' ); ?>
					</option>
					<option value="unsemantic"
						<?php echo ( isset( $settings['grid-structure'] ) && $settings['grid-structure'] == 'unsemantic' ) ? 'selected="selected"' : ''; ?>>
						<?php _e( 'unsemantic', 'runway' ); ?>
					</option>
					<option value="custom"
						<?php echo ( isset( $settings['grid-structure'] ) && $settings['grid-structure'] == 'custom' ) ? 'selected="selected"' : ''; ?>>
						<?php _e( 'Custom', 'runway' ); ?>
					</option>
				</select>
				<p class="description"><?php _e( 'Select a source for the top footer content', 'runway' ); ?></p>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Design width', 'runway' ); ?>
				<p class="description required"><?php _e( 'Required', 'runway' ); ?></p>
			</th>
			<td>
				<?php
				$design_width = ( isset( $settings['design-width'] ) && ! empty( $settings['design-width'] ) ) ?
					$settings['design-width'] :
					'1200';
				?>
				<input class="input-text " type="text" name="design-width" value="<?php echo $design_width; ?>">
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Columns', 'runway' ); ?>
				<p class="description required"><?php _e( 'Required', 'runway' ); ?></p>
			</th>
			<td>
				<?php
				$columns = ( isset( $settings['columns'] ) && ! empty( $settings['columns'] ) ) ? $settings['columns'] : '12';
				?>
				<input id="columns" class="input-text " type="text" name="columns" value="<?php echo $columns; ?>">
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Margins', 'runway' ); ?>
				<p class="description required"><?php _e( 'Required', 'runway' ); ?></p>
			</th>
			<td>
				<?php
				$margins = ( isset( $settings['margins'] ) && ! empty( $settings['margins'] ) ) ? $settings['margins'] : '50';
				?>
				<input class="input-text " type="text" name="margins" value="<?php echo $margins; ?>">
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Row Class', 'runway' ); ?>
				<p class="description required"><?php _e( 'Required', 'runway' ); ?></p>
			</th>
			<td>
				<?php
				$row_class = ( isset( $settings['row-class'] ) && ! empty( $settings['row-class'] ) ) ? $settings['row-class'] : 'row-fluid';
				?>
				<input id="row-class" class="input-text " type="text" name="row-class" value="<?php echo $row_class; ?>">
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Column class format', 'runway' ); ?>
				<p class="description required"><?php _e( 'Required', 'runway' ); ?></p>
			</th>
			<td>
				<?php
				$column_class_format = ( isset( $settings['column-class-format'] ) && ! empty( $settings['column-class-format'] ) ) ?
					$settings['column-class-format'] :
					'span#';
				?>
				<input id="column-class-format" class="input-text " type="text" name="column-class-format"
				       value="<?php echo $column_class_format; ?>">
				<p class="description">
					<?php _e( 'Accepted wild cards: # = number of columns, % = column percentage width', 'runway' ); ?>
				</p>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<span><?php _e( 'Minimum column size', 'runway' ); ?></span>
				<p class="description"></p>
			</th>
			<td>
				<select id="min-column-size" class="input-select" name="min-column-size">
					<option value="1"
						<?php echo ( isset( $settings['min-column-size'] ) && $settings['min-column-size'] == '1' ) ? 'selected="selected"' : ''; ?>>
						1
					</option>
					<option value="2"
						<?php echo ( isset( $settings['min-column-size'] ) && $settings['min-column-size'] == '2' ) ? 'selected="selected"' : ''; ?>>
						2
					</option>
				</select>
				<p class="description">
					<?php _e( 'The smallest size a user may resize a column in the grid structure. In a 12 column layout structure, entering 2 will limit layouts to having no sections spanning less than 2 columns', 'runway' ); ?>
				</p>
			</td>
		</tr>
		</tbody>
	</table>

	<h3><?php _e( 'Content Source Section', 'runway' ); ?></h3>
	<table class="form-table">
		<tbody>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Pages', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true" name="content-source-pages"
			              <?php if ( isset( $settings['content-source-pages'] ) && $settings['content-source-pages'] === 'true' ) { ?>checked<?php } ?>>
					<?php _e( 'Enable Pages', 'runway' ); ?>
				</label>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Posts', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true" name="content-source-posts"
			              <?php if ( isset( $settings['content-source-posts'] ) && $settings['content-source-posts'] === 'true' ) { ?>checked<?php } ?>>
					<?php _e( 'Enable Posts', 'runway' ); ?>
				</label>
			</td>
		</tr>
		<tr class="">
			<th scope="row" valign="top">
				<?php _e( 'Sidebars', 'runway' ); ?>
				<p class="description required"><?php //_e('Required', 'runway') ?></p>
			</th>
			<td>
				<label>
					<input class="input-check" type="checkbox" value="true" name="content-source-sidebars"
			              <?php if ( isset( $settings['content-source-sidebars'] ) && $settings['content-source-sidebars'] === 'true' ) { ?>checked<?php } ?>>
					<?php _e( 'Enable Sidebars', 'runway' ); ?>
				</label>
			</td>
		</tr>
		<!-- 			<?php $static_block = get_pages( array( 'post_type' => 'static_block' ) );
		if ( sizeof( $static_block ) > 0 ) { ?>
				<tr class="">
					<th scope="row" valign="top">
						<?php _e( 'Static Block', 'runway' ); ?>
						<p class="description required"><?php //_e('Required', 'runway'); ?></p>
					</th>
					<td>
						<label><input class="input-check" type="checkbox" value="true" name="content-source-static_block" checked> <?php _e( 'Enable Static Blocks', 'runway' ); ?></label>
					</td>
				</tr>
			<?php } ?> -->
		<?php $custom_post_types = get_post_types( array( '_builtin' => false ), 'object' );
		foreach ( $custom_post_types as $key => $custom_post_type ) {
			$custom_post_type_label = isset( $custom_post_type->label ) ? $custom_post_type->label : '';
			?>
			<tr class="">
				<th scope="row" valign="top">
					<?php echo sprintf( __( '%s', 'runway' ), ucfirst( $custom_post_type_label ) ); ?>
					<p class="description required"><?php //_e('Required', 'runway'); ?></p>
				</th>
				<td>
					<label>
						<input class="input-check" type="checkbox" value="true" name="content-source-<?php echo $key; ?>"
							<?php echo ( isset( $settings["content-source-$key"] ) && $settings["content-source-$key"] == 'true' ) ? 'checked' : ''; ?>>
						<?php _e( 'Enable', 'runway' ); ?> <?php echo sprintf( __( '%s', 'runway' ), ucfirst( $custom_post_type_label ) ); ?>
					</label>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<br><br>

	<?php
	global $layouts_manager;
	if (
		isset( $layouts_manager->layouts_manager_options['settings']['headers'] )
		|| isset( $layouts_manager->layouts_manager_options['settings']['footers'] )
	) { ?>
		<a name="header_footer_render"></a>
		<div class="meta-box-sortables metabox-holder" data-template="${template}" data-index="${index}">
			<div class="postbox">
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'runway' ); ?>"><br></div>
				<?php
				if (
					isset( $layouts_manager->layouts_manager_options['settings']['headers'] )
					&& isset( $layouts_manager->layouts_manager_options['settings']['footers'] )
				) { ?>
					<h3 class="postbox-title hndle">
						<span><?php _e( 'Rendering of Header and Footer', 'runway' ); ?></span>
					</h3>
				<?php }

				if (
					isset( $layouts_manager->layouts_manager_options['settings']['headers'] )
					&& ! isset( $layouts_manager->layouts_manager_options['settings']['footers'] )
				) { ?>
					<h3 class="postbox-title hndle">
						<span><?php _e( 'Rendering of Header', 'runway' ); ?></span>
					</h3>
				<?php }

				if (
					! isset( $layouts_manager->layouts_manager_options['settings']['headers'] )
					&& isset( $layouts_manager->layouts_manager_options['settings']['footers'] )
				) { ?>
					<h3 class="postbox-title hndle">
						<span><?php _e( 'Rendering of Footer', 'runway' ); ?></span>
					</h3>
				<?php } ?>
				<div class="inside">
					<?php if ( isset( $layouts_manager->layouts_manager_options['settings']['headers'] ) ) {
						_e( 'The header action needs to be placed at the very end of header.php. In most cases it will be the last thing in the file:', 'runway' ); ?>
						<pre class="code sample-code">
// <?php echo wordwrap( __( 'Begin Layout', 'runway' ) . " do_action('output_layout','start');", 20 ); ?>
						</pre>
					<?php }
					if ( isset( $layouts_manager->layouts_manager_options['settings']['footers'] ) ) {
						_e( 'The footer action needs to be placed at the very end of footer.php. In most cases it will be the very first thing in the file:', 'runway' ); ?>
						<pre class="code sample-code">
// <?php echo wordwrap( __( 'End Layout', 'runway' ) . " do_action('output_layout','end');", 20 ); ?>
						</pre>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<input class="button-primary" type="submit" value="<?php _e( 'Save Settings', 'runway' ); ?>">
</form>
