<script type="text/javascript">
(function($){
	$(document).ready(function(){
		setStates();
	});

	$('body').on('change', '#grid-structure', function(e){
		setStates();
	});

	function setStates(){
		var gridStruct = $('#grid-structure').val();
		// console.log(gridStruct);
		switch(gridStruct){
			case 'bootstrap':{
				$('#columns').val(12);
				//$('#columns').attr('disabled', true);
				//$('#row-class').val('row-fluid');
				$('#column-class-format').val('span#');
				//$('#column-class-format').attr('disabled', true);
			} break;

			case '960':{
				$('#column-class-format').val('grid_#');
				//$('#column-class-format').attr('disabled', true);
				$('#columns').attr('disabled', false);
			} break;

			case 'unsemantic':{
				$('#column-class-format').val('grid-%');
				//$('#column-class-format').attr('disabled', true);
				$('#columns').attr('disabled', false);
			} break;			

			case 'custom':{
				//$('#column-class-format').attr('disabled', false);
				$('#columns').attr('disabled', false);
			} break;

			default:{
				// Nothing to do
			} break;
		}
	}

})(jQuery);
</script>
<form action="<?php echo $this->self_url(); ?>&navigation=settings&action=update-settings" method="post">
	<h3><?php echo __('Layout Sections', 'framework'); ?></h3>
	<table class="form-table">
		<tbody>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Headers', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
					<label><input class="input-check" type="checkbox" value="true" name="headers" <?php echo (isset($settings['headers']) && $settings['headers'] == 'true') ? 'checked' : ''; ?>> <?php echo __('Enable Layout Headers', 'framework'); ?></label>
				</td>
			</tr>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Footers', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
					<label><input class="input-check" type="checkbox" value="true" name="footers" <?php echo (isset($settings['footers']) && $settings['footers'] == 'true') ? 'checked' : ''; ?>> <?php echo __('Enable Layout Footers', 'framework'); ?></label>
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Layout Options', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
					<label><input class="input-check" type="checkbox" value="true" name="other-options" <?php echo (isset($settings['other-options']) && $settings['other-options'] == 'true') ? 'checked' : ''; ?>> <?php echo __('Enable Layout Options', 'framework'); ?></label>
				</td>
			</tr>		
		</tbody>
	</table>
	<h3><?php echo __('Layout Structure', 'framework'); ?></h3>
	<table class="form-table">
		<tbody>				
			<tr class="">
				<th scope="row" valign="top">
					<span><?php _e('Grid structure', 'framework') ?></span>
					<p class="description"></p>
				</th>
				<td>
					<select id="grid-structure" class="input-select" name="grid-structure">
						<option value="bootstrap" <?php echo (isset($settings['grid-structure']) && $settings['grid-structure'] == 'bootstrap') ? 'selected="selected"' : ''; ?>><?php echo __('Bootstrap (default)', 'framework'); ?></option>
						<option value="960" <?php echo (isset($settings['grid-structure']) && $settings['grid-structure'] == '960') ? 'selected="selected"' : ''; ?>><?php echo __('960 Grid System', 'framework'); ?></option>
						<option value="unsemantic" <?php echo (isset($settings['grid-structure']) && $settings['grid-structure'] == 'unsemantic') ? 'selected="selected"' : ''; ?>><?php echo __('unsemantic', 'framework'); ?></option>
						<option value="custom" <?php echo (isset($settings['grid-structure']) && $settings['grid-structure'] == 'custom') ? 'selected="selected"' : ''; ?>><?php echo __('Custom', 'framework'); ?></option>
					</select>
					<p class="description"><?php _e('Select a source for the top footer content', 'framework') ?></p>
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Design width', 'framework') ?> <p class="description required"><?php echo __('Required', 'framework'); ?></p>
				</th>
				<td>
					<?php
						$design_width = ( isset( $settings['design-width'] ) && !empty( $settings['design-width'] ) ) ? $settings['design-width'] : '1200';
					?>
					<input class="input-text " type="text" name="design-width" value="<?php echo $design_width; ?>">
				</td>
			</tr>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Columns', 'framework') ?> <p class="description required"><?php echo __('Required', 'framework'); ?></p>
				</th>
				<td>
					<?php
						$columns = ( isset( $settings['columns'] ) && !empty( $settings['columns'] ) ) ? $settings['columns'] : '12';
					?>
					<input id="columns" class="input-text " type="text" name="columns" value="<?php echo $columns; ?>">
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Margins', 'framework') ?> <p class="description required"><?php echo __('Required', 'framework'); ?></p>
				</th>
				<td>
					<?php
						$margins = ( isset( $settings['margins'] ) && !empty( $settings['margins'] ) ) ? $settings['margins'] : '50';
					?>
					<input class="input-text " type="text" name="margins" value="<?php echo $margins; ?>">
				</td>
			</tr>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Row Class', 'framework') ?> <p class="description required"><?php echo __('Required', 'framework'); ?></p>
				</th>
				<td>
					<?php
						$row_class = ( isset( $settings['row-class'] ) && !empty( $settings['row-class'] ) ) ? $settings['row-class'] : 'row-fluid';
					?>
					<input id="row-class" class="input-text " type="text" name="row-class" value="<?php echo $row_class; ?>">
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Column class format', 'framework') ?> <p class="description required"><?php echo __('Required', 'framework'); ?></p>
				</th>
				<td>
					<?php 
						$column_class_format = ( isset( $settings['column-class-format'] ) && !empty( $settings['column-class-format'] ) ) ? $settings['column-class-format'] : 'span#';
					?>
					<input id="column-class-format" class="input-text " type="text" name="column-class-format" value="<?php echo $column_class_format; ?>">
					<p class="description">
						<?php _e('Accepted wild cards: # = number of columns, % = column percentage width', 'framework') ?>
					</p>
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<span><?php _e('Minimum column size', 'framework') ?></span>
					<p class="description"></p>
				</th>
				<td>
					<select id="min-column-size" class="input-select" name="min-column-size">
						<option value="1" <?php echo (isset($settings['min-column-size']) && $settings['min-column-size'] == '1') ? 'selected="selected"' : ''; ?>>1</option>
						<option value="2" <?php echo (isset($settings['min-column-size']) && $settings['min-column-size'] == '2') ? 'selected="selected"' : ''; ?>>2</option>					
					</select>
					<p class="description">
						<?php _e('The smallest size a user may resize a column in the grid structure. In a 12 column layout structure, entering 2 will limit layouts to having no sections spanning less than 2 columns', 'framework') ?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<h3><?php echo __('Content Source Section', 'framework'); ?></h3>
	<table class="form-table">
		<tbody>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Pages', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
                    <label><input class="input-check" type="checkbox" value="true" name="content-source-pages" <?php if((isset($settings['content-source-pages']) && $settings['content-source-pages'] === 'true')) { ?>checked<?php } ?>> <?php echo __('Enable Pages', 'framework'); ?></label>
				</td>
			</tr>		
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Posts', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
					<label><input class="input-check" type="checkbox" value="true" name="content-source-posts" <?php if((isset($settings['content-source-posts']) && $settings['content-source-posts'] === 'true')) { ?>checked<?php } ?>> <?php echo __('Enable Posts', 'framework'); ?></label>
				</td>
			</tr>
			<tr class="">
				<th scope="row" valign="top">
					<?php _e('Sidebars', 'framework') ?>
					<p class="description required"><?php //_e('Required', 'framework') ?></p>
				</th>
				<td>
					<label><input class="input-check" type="checkbox" value="true" name="content-source-sidebars" <?php if((isset($settings['content-source-sidebars']) && $settings['content-source-sidebars'] === 'true')) { ?>checked<?php } ?>> <?php echo __('Enable Sidebars', 'framework'); ?></label>
				</td>
			</tr>
<!-- 			<?php $static_block = get_pages(array('post_type' => 'static_block'));
			if (sizeof($static_block) > 0): ?>
				<tr class="">
					<th scope="row" valign="top">
						<?php _e('Static Block', 'framework') ?>
						<p class="description required"><?php //_e('Required', 'framework') ?></p>
					</th>
					<td>
						<label><input class="input-check" type="checkbox" value="true" name="content-source-static_block" checked> <?php echo __('Enable Static Blocks', 'framework'); ?></label>
					</td>
				</tr>			
			<?php endif ?> -->
			<?php $custom_post_types = get_post_types(array('_builtin'=>false), 'object');
				foreach($custom_post_types as $key => $custom_post_type): 
					$custom_post_type_label = isset($custom_post_type->label) ? $custom_post_type->label : "";
                                    ?>
					<tr class="">
						<th scope="row" valign="top">
							<?php _e(ucfirst($custom_post_type_label), 'framework') ?>
							<p class="description required"><?php //_e('Required', 'framework') ?></p>
						</th>
						<td>
							<label><input class="input-check" type="checkbox" value="true" name="content-source-<?php echo $key; ?>" <?php echo (isset($settings["content-source-$key"]) && $settings["content-source-$key"] == 'true') ? 'checked' : ''; ?>> <?php echo __('Enable', 'framework'); ?> <?php echo __(ucfirst($custom_post_type_label), 'framework'); ?></label>
						</td>
					</tr>					
				<?php endforeach ?>
		</tbody>
	</table>
	<br><br>

	
	<?php global $layouts_manager; ?>
	<?php if(isset($layouts_manager->layouts_manager_options['settings']['headers']) || 
			 isset($layouts_manager->layouts_manager_options['settings']['footers']) ): ?>
				<a name="header_footer_render"></a>
				<div class="meta-box-sortables metabox-holder" data-template="${template}" data-index="${index}">
					<div class="postbox">
						<div class="handlediv" title="<?php _e('Click to toggle', 'framework'); ?>"><br></div>
						<?php if(isset($layouts_manager->layouts_manager_options['settings']['headers']) &&
			 					 isset($layouts_manager->layouts_manager_options['settings']['footers']) ): ?>						
									<h3 class="postbox-title hndle"><span><?php _e('Rendering of Header and Footer', 'framework'); ?></span></h3>
						<?php endif; ?>
						<?php if(isset($layouts_manager->layouts_manager_options['settings']['headers']) &&
			 					 !isset($layouts_manager->layouts_manager_options['settings']['footers']) ): ?>						
									<h3 class="postbox-title hndle"><span><?php _e('Rendering of Header', 'framework'); ?></span></h3>
						<?php endif; ?>	
						<?php if(!isset($layouts_manager->layouts_manager_options['settings']['headers']) &&
			 					 isset($layouts_manager->layouts_manager_options['settings']['footers']) ): ?>						
									<h3 class="postbox-title hndle"><span><?php _e('Rendering of Footer', 'framework'); ?></span></h3>
						<?php endif; ?>							
						<div class="inside" >
							<?php if(isset($layouts_manager->layouts_manager_options['settings']['headers']) ):
								_e('The header action needs to be placed at the very end of header.php. In most cases it will be the last thing in the file:', 'framework'); ?>
								<pre class="code sample-code">// <?php echo wordwrap(__('Begin Layout', 'framework')." do_action('output_layout','start');", 20); ?></pre>
							<?php endif; ?>	
							<?php if(isset($layouts_manager->layouts_manager_options['settings']['footers']) ):						
								_e('The footer action needs to be placed at the very end of footer.php. In most cases it will be the very first thing in the file:', 'framework'); ?>
								<pre class="code sample-code">// <?php echo wordwrap(__('End Layout', 'framework')." do_action('output_layout','end');", 20); ?></pre>
							<?php endif; ?>									
						</div>
					</div>
				</div>
	<?php endif; ?>									
	<input class="button-primary" type="submit" value="<?php _e('Save Settings', 'framework') ?>">
</form>
