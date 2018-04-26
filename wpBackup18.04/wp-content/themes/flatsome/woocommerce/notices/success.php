<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}
?>
<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message message-wrapper" role="alert">
		<div class="message-container container success-color medium-text-center">
			<?php echo get_flatsome_icon( 'icon-checkmark' ); ?>
			<?php echo wp_kses_post( $message ); ?>
			<?php if ( is_product() && get_theme_mod( 'cart_dropdown_show', 1 ) ) { ?>
				<span class="added-to-cart" data-timer=""></span>
			<?php } ?>
		</div>
	</div>
<?php endforeach; ?>
