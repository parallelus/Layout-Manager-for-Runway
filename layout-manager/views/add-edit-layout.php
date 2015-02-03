<?php global $shortname; ?>
<?php $custom_post_types = get_post_types(array('_builtin'=>false), 'object'); ?>

<div id="poststuff">

	<input type = "hidden" id="prefix_header" value="<?php  echo $shortname . "layout_header_"; ?>">
	<input type = "hidden" id="prefix_footer" value="<?php echo $shortname . "layout_footer_"; ?>">
	<input type = "hidden" id="prefix_other" value="<?php echo $shortname . "other_options_"; ?>">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">

			<div style="clear:both;"></div>
			<input id="layout_alias" type="hidden" value="<?php echo isset($layout) ? $layout['alias'] : ''; ?>">		
			<!-- BEGIN - LAYOUT MANAGER -->
			<br>

			<div>
				<div id="titlediv">

					<div id="titlewrap">
						<label class="hide-if-no-js" style="visibility: hidden; " id="title-prompt-text" for="title"><?php _e('Enter title here', 'framework') ?></label>
						<input type="text" name="post_title" size="30" tabindex="1" value="<?php esc_attr_e($layout['title']); ?>" id="title" autocomplete="off">
					</div>
				</div>

				<h3 id="big-header" class="layout-heading"><?php _e('HEADER', 'framework') ?></h3>
				<div id="HeaderWrapper">
					<div class="control-bar">
						<div id="HeaderAddNew">
							<a href="#add_header" id="AddHeader" class="button"><?php _e('Add New', 'framework') ?></a>
						</div>
						<div id="HeaderSelect">
							<span class="spinner"></span>
							<select name="header_select">
								<?php 
								foreach ((array)$headers as $key => $value) :
									$selected = "";
									if ( isset($layout['header']) && $layout['header'] == $key) {
										$selected = 'selected="selected"';
									} ?>
									<option value="<?php echo $value['alias']; ?>" <?php echo $selected; ?> >
										<?php echo __(stripslashes($value['title']), 'framework'); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<a href="#edit_header" id="EditHeader" class="button"><?php _e('Edit', 'framework') ?></a>
						</div>
						<div id="HeaderAddEdit">
							<input type="text" name="header_title" class="header-title">
							<a href="#save_header" id="SaveHeader" class="button button-primary"><?php _e('Save', 'framework') ?></a>
							<a href="#cancel_edit_header" id="CancelEditHeader" class="button"><?php _e('Cancel', 'framework') ?></a>
						</div>
					</div>
					<div class="inner-container" style="display: none;" id="headerAddEditForm">
						<!-- TODO: layout header form -->				
						<?php //require_once "header-form.php"; ?>
					</div>
				</div>

				<?php if(! isset($layouts_manager->layouts_manager_options['settings']['headers']) ): ?>
					<script type="text/javascript">
						(function($){
							$("#HeaderWrapper, #big-header").css('display','none');
						})(jQuery);
					</script>
				<?php endif; ?>	

				<h3 class="layout-heading"><?php _e('BODY', 'framework') ?></h3>
				<div id="GridWrapper">
					<div id="GridEnd" class="not-a-row">
						<a href="#add_row_button" id="AddNewRow" class="button button-hero"><?php _e('Add Row', 'framework') ?></a>
					</div>
				</div><!-- / #GridWrapper -->

				<h3 id="big-footer" class="layout-heading"><?php _e('FOOTER', 'framework') ?></h3>
				<div id="FooterWrapper">
					<div class="control-bar">
						<div id="FooterAddNew">
							<a href="#add_footer" id="AddFooter" class="button"><?php _e('Add New', 'framework') ?></a>
						</div>
						<div id="FooterSelect">
							<span class="spinner"></span>
							<select name="footer_select">
								<?php 

								foreach ((array)$footers as $key => $value) :
									$selected = "";
									if ( isset($layout['footer']) && $layout['footer'] == $key ) {
										$selected = 'selected="selected"';
									} ?>
									<option value="<?php echo $value['alias']; ?>" <?php echo $selected; ?> >
										<?php echo __(stripslashes($value['title']), 'framework'); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<a href="#edit_footer" id="EditFooter" class="button"><?php _e('Edit', 'framework') ?></a>
						</div>
						<div id="FooterAddEdit">
							<input type="text" name="footer_title" class="footer-title">
							<a href="#save_footer" id="SaveFooter" class="button button-primary"><?php _e('Save', 'framework') ?></a>
							<a href="#cancel_edit_footer" id="CancelEditFooter" class="button"><?php _e('Cancel', 'framework') ?></a>
						</div>
					</div>
					<div class="inner-container" style="display: none;" id="footerAddEditForm" >
						<!-- TODO: layout footer settings -->
						<?php // require_once "footer-form.php"; ?>
					</div>
				</div>
				
				<?php if(! isset($layouts_manager->layouts_manager_options['settings']['footers']) ): ?>
					<script type="text/javascript">
						(function($){
							$("#FooterWrapper, #big-footer").css('display','none');
						})(jQuery);
					</script>
				<?php endif; ?>	
				
				<!-- NEW ROW TEMPLATE -->
				<div id="RowTemplate">

					<div class="rowWrapper">
						<div class="rowContainer">			
						</div><!-- / .rowContainer -->
						<div class="handle rowHandle"></div>
						<a href="#close" class="delete-row ui-dialog-titlebar-close" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
					</div><!-- / .rowWrapper -->

				</div>
				<!-- / NEW ROW TEMPLATE -->

				<!-- NEW COLUMN TEMPLATE -->
				<div id="ColumnTemplate">

					<div class="column">
						<div class="column-header">
							<a href="#desktop" id="desktop" class="responsive-visibility show-on desktop-view" title="<?php _e('Visible on Desktop', 'framework') ?>"></a>
							<a href="#tablet" id="tablet" class="responsive-visibility show-on tablet-view" title="<?php _e('Visible on Tablet', 'framework') ?>"></a>
							<a href="#phone" id="phone" class="responsive-visibility show-on phone-view" title="<?php _e('Visible on Phone', 'framework') ?>"></a>
							<a href="#close" class="delete-column ui-dialog-titlebar-close" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
						</div>
						<div class="content-element">
							<div class="handle"></div>
								<a href="#close" class="delete-element ui-dialog-titlebar-close" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
								<select name="content_element_type" class="content-element-type">
									<option value="default_content"><?php _e('Default Content', 'framework') ?></option>
									<?php
									foreach($custom_post_types as $key => $custom_post_type) {
										$custom_post_type_label = isset($custom_post_type->label) ? $custom_post_type->label : "";
										$custom_posts[$key] = get_posts(array('post_type' => $key));
										if(count($custom_posts[$key]) > 0) {
											if( isset($layouts_manager->layouts_manager_options['settings']["content-source-$key"]) &&
											 		  $layouts_manager->layouts_manager_options['settings']["content-source-$key"] == true ): ?>
												<option value="<?php echo $key; ?>"><?php _e(ucfirst($custom_post_type_label), 'framework') ?></option>
											<?php endif ?>
										<?php } 
									} ?>								
									<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-posts']) && $layouts_manager->layouts_manager_options['settings']['content-source-posts'] == 'true') { ?>
										<option value="post"><?php _e('Post', 'framework') ?></option>
									<?php } ?>
									<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-pages']) && $layouts_manager->layouts_manager_options['settings']['content-source-pages'] == 'true') { ?>
										<option value="page"><?php _e('Page', 'framework') ?></option>
									<?php } ?>
									<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-sidebars']) && $layouts_manager->layouts_manager_options['settings']['content-source-sidebars'] == 'true') { ?>
										<option value="sidebar"><?php _e('Sidebar', 'framework') ?></option>
									<?php } ?>
								</select>
								<p class="content-type-help-text">
									<span class="spinner"></span>
									<span class="description-default_content"><?php _e('The default content of the area. <strong>This should exist only once in every layout.</strong>', 'framework') ?></span>
									<?php
									foreach($custom_post_types as $key => $custom_post_type) {
										$custom_post_type_label = isset($custom_post_type->label) ? $custom_post_type->label : "";
										if(count($custom_posts[$key]) > 0) {
											if( isset($layouts_manager->layouts_manager_options['settings']["content-source-$key"]) &&
											 		  $layouts_manager->layouts_manager_options['settings']["content-source-$key"] == true ): ?>
												<span class="description-<?php echo $key; ?>"><?php _e("Choose a $custom_post_type_label from as the content source.", 'framework') ?></span>
											<?php endif ?>
										<?php }
									} ?>
									<span class="description-page"><?php _e('Choose a page from as the content source.', 'framework') ?></span>
									<span class="description-sidebar"><?php _e('Select a sidebar to use as the content source.', 'framework') ?></span>
								</p>
								<div class="secondary-select" style="display: none;">
									<select name="content_element_source" class="content-element-source">
										<option value=""></option>
									</select>
									<div class="check-override-secondary">
										<label><input class="input-check-secondary" type="checkbox" value="false" name="allow_override"><?php _e('Allow override on single pages', 'framework'); ?></label>
										<p class="optional-label-override-secondary">
											<input class="input-text" type="text" name="optional-label-secondary" value="">
											<p class="optional-label-help-text-secondary">
												<?php _e('Custom label for override option.', 'framework') ?>
											</p>
										</p>
									</div>
								</div>
							</div>
						</div>
					<div class="column-divider"></div>

				</div>
				<br><br><br>

				<div style="clear:both;"></div>
			</div>

			<div id="OnlyColumnTemplate">
				<div class="column">
					<div class="column-header">
						<a href="#desktop" id="desktop" class="responsive-visibility show-on desktop-view" title="<?php _e('Visible on Desktop', 'framework') ?>"></a>
						<a href="#tablet" id="tablet" class="responsive-visibility show-on tablet-view" title="<?php _e('Visible on Tablet', 'framework') ?>"></a>
						<a href="#phone" id="phone" class="responsive-visibility show-on phone-view" title="<?php _e('Visible on Phone', 'framework') ?>"></a>
						<a href="#close" class="delete-column ui-dialog-titlebar-close" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
					</div>
					<div id="ColumnEnd" style="display:none;"></div>			
				</div>

				<div class="column-divider"></div>
			</div>

			<div id="OnlyContentElementTemplate">
				<div class="content-element">
				<div class="handle"></div>
					<a href="#close" class="delete-element ui-dialog-titlebar-close" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
					<select name="content_element_type" class="content-element-type">
						<option value="default_content" ><?php _e('Default Content', 'framework') ?></option>
						<?php
						$custom_post_types = get_post_types(array('_builtin'=>false), 'object'); 
						foreach($custom_post_types as $key => $custom_post_type) {
							$custom_post_type_label = isset($custom_post_type->label) ? $custom_post_type->label : "";
							if(count($custom_posts[$key]) > 0) {
								if( isset($layouts_manager->layouts_manager_options['settings']["content-source-$key"]) &&
								 		  $layouts_manager->layouts_manager_options['settings']["content-source-$key"] == true ): ?>
									<option value="<?php echo $key; ?>"><?php _e(ucfirst($custom_post_type_label), 'framework') ?></option>
								<?php endif ?>
							<?php } 
						} ?>		
						<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-posts']) && $layouts_manager->layouts_manager_options['settings']['content-source-posts'] == 'true') { ?>
							<option value="post"><?php _e('Post', 'framework') ?></option>
						<?php } ?>
						<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-pages']) && $layouts_manager->layouts_manager_options['settings']['content-source-pages'] == 'true') { ?>
							<option value="page"><?php _e('Page', 'framework') ?></option>
						<?php } ?>
						<?php if(isset($layouts_manager->layouts_manager_options['settings']['content-source-sidebars']) && $layouts_manager->layouts_manager_options['settings']['content-source-sidebars'] == 'true') { ?>
							<option value="sidebar"><?php _e('Sidebar', 'framework') ?></option>
						<?php } ?>
					</select>
					<p class="content-type-help-text">
						<span class="spinner"></span>
						<span class="description-default_content"><?php _e('The default content of the area. <strong>This should exist only once in every layout.</strong>', 'framework') ?></span>
						<?php
						foreach($custom_post_types as $key => $custom_post_type) {
							$custom_post_type_label = isset($custom_post_type->label) ? $custom_post_type->label : "";
							if(count($custom_posts[$key]) > 0) {
								if( isset($layouts_manager->layouts_manager_options['settings']["content-source-$key"]) &&
								 		  $layouts_manager->layouts_manager_options['settings']["content-source-$key"] == true ): ?>
									<span class="description-<?php echo $key; ?>"><?php _e("Choose a $custom_post_type_label from as the content source.", 'framework') ?></span>
								<?php endif ?>
							<?php }
						} ?>
						<span class="description-page"><?php _e('Choose a page from as the content source.', 'framework') ?></span>
						<span class="description-sidebar"><?php _e('Select a sidebar to use as the content source.', 'framework') ?></span>
					</p>
					<div class="secondary-select" style="display: none;">
						<select name="content_element_source" class="content-element-source">
							<option value=""></option>
						</select>
						<div class="check-override-secondary">
							<label><input class="input-check-secondary" type="checkbox" value="false" name="allow_override"><?php _e('Allow override on single pages', 'framework'); ?></label>
							<p class="optional-label-override-secondary">
								<input class="input-text" type="text" name="optional-label-secondary" value="">
								<p class="optional-label-help-text-secondary">
									<?php _e('Custom label for override option.', 'framework') ?>
								</p>
							</p>
						</div>
					</div>
					
				</div>
			</div>

		</div><!-- / #post-body-content -->

		<div id="postbox-container-1" class="postbox-container">

			<div class="meta-box-sortables metabox-holder">
				<div class="postbox">
					<div class="handlediv" title="<?php _e('Click to toggle', 'framework'); ?>"><br></div>
					<h3 class="hndle"><span><?php _e('Layout Options', 'framework'); ?></span></h3>
					<div class="inside" >
						<?php 
							global $layouts_manager;
							if(isset($layouts_manager->layouts_manager_options['settings']['other-options'])){
								global $layout_manager_admin, $libraries, $shortname;
								$form_builder = $libraries['FormsBuilder'];

								$form_json = isset($layouts_manager->layouts_manager_options['other-options']) ? 
									$layouts_manager->layouts_manager_options['other-options'] : '';

								do_action("before_layout_option_fields");
								if($form_json != ''){
									$form_settings = json_decode(json_encode($form_json));
									//$layout_manager_admin->data = $form_builder->get_custom_options_vals($form_settings->settings->alias);
									$layout_manager_admin->data = $form_builder->get_custom_options_vals('other_options_'.$layout['alias'], true);   
									$layout_manager_admin->elements = $form_settings->elements;
									$layout_manager_admin->builder_page = $form_settings;

									$form_builder->section = 'other_options';
									
									$form_builder->render_form($form_settings, false, $layouts_manager, $layout_manager_admin, 'other_options_'.$layout['alias']); // render form in one function call
								}
								do_action("after_layout_option_fields");
							}
						?>
						<p>
							<button style="float:right;" id="save_structure" class="button-primary ajax-save"><?php _e('Save settings', 'framework'); ?></button>
							<span class="spinner"></span>
							<div class="clear"></div>
						</p>
						<script type="text/javascript">
							jQuery(document).ready(function($){
								$('#save_structure').parents('.inside').find('.inside').css({'display': ''});
							});
						</script>
					</div>
				</div>
			</div>

		</div><!-- / #postbox-container-1 -->

	</div><!-- / #post-body -->

</div> <!-- / #poststuff -->