<?php
/**
 * Show error messages
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
<ul class="woocommerce-error message-wrapper" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span> <?php echo wp_kses_post( $message ); ?></div></li>
	<?php endforeach; ?>
</ul>
