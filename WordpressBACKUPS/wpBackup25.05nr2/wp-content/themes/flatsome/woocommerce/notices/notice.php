<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info message-wrapper">
     <div class="message-container container medium-text-center">
  	   <?php echo wp_kses_post( $message ); ?>
     </div>
	</div>
<?php endforeach; ?>
