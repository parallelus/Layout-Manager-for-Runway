<div id="post-body">
	<div id="post-body-content" style="width: auto; min-width: 50%;">
		<br>
		<?php if ( ! empty( $footers ) ) { ?>
			<table class="wp-list-table widefat">
				<thead>
				<tr>
					<th id="name" class="manage-column column-name"><?php _e( 'Title', 'runway' ); ?></th>
					<th id="name" class="manage-column column-description" style="text-align:right;">&nbsp;</th>
				</tr>
				</thead>
				<tbody id="the-list">
				<?php
				$count = 0;
				foreach ( $footers as $key => $values ) {
					$trClass = ( $count % 2 == 0 ) ? 'active alt' : 'active';
					?>
					<tr class="<?php echo $trClass; ?>">
						<td class="row-title">
							<a href="<?php echo $this->self_url( 'edit-footer' ); ?>&alias=<?php echo $values['alias']; ?>">
								<strong><?php rf_e( $values['title'] ); ?></strong>
							</a>
						</td>
						<td class="column-description" style="text-align:right;">
							<a href="<?php echo $this->self_url( 'edit-footer' ); ?>&alias=<?php echo $values['alias']; ?>">
								<?php _e( 'Edit', 'runway' ); ?>
							</a>
							|
							<a style="color: #BC0B0B;" href="<?php echo $this->self_url(); ?>&navigation=confirm-delete-footer&alias=<?php echo $values['alias']; ?>">
								<?php _e( 'Delete', 'runway' ); ?>
							</a>
						</td>
					</tr>
					<?php
					$count++;
				} ?>
				</tbody>
			</table>
		<?php } else { ?>

			<h3><?php _e( 'No footers created yet', 'runway' ); ?></h3>

		<?php } ?>

	</div> <!-- / #post-body-content -->
</div> <!-- / #post-body -->
