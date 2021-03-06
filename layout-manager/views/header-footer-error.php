<?php
if ( $header_err || $footer_err ) { ?>
	<div class="updated">
		<p>
			<?php if ( $header_err ) {
				_e( 'The header action needs to be placed at the very end of <strong>header.php</strong>. In most cases it will be the last thing in the file.', 'runway' );
			}

			if ( $footer_err ) {
				if ( $header_err ) {
					echo '<br>';
				}
				_e( 'The footer action needs to be placed at the very end of <strong>footer.php</strong>. In most cases it will be the very first thing in the file.', 'runway' );
			}

			echo sprintf( __( ' See <a href="%s#header_footer_render"><strong>here</strong></a>.', 'runway' ), $this->self_url( 'settings' ) );
			?>
		</p>
	</div>
<?php }
