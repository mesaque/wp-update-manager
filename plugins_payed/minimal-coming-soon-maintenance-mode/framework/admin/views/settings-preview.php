<?php

/**
 * Preview view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="preview">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'PREVIEW', 'signals' ); ?></div>
		<p><?php _e( 'You can preview the maintenance page to see how it will look once the maintenance mode is turned on. Clicking the button below will open the preview in a new window.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<a href="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/preview.php" class="signals-btn" target="_blank"><strong><?php _e( 'Preview Maintenance Page', 'signals' ); ?></strong></a>
		</div>
	</div>
</div><!-- #preview -->