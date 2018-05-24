<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( fl_woocommerce_version_check( '3.3.0' ) ) {
	$cols = esc_attr( wc_get_loop_prop( 'columns' ) );
	?>
	<div class="products <?php echo flatsome_product_row_classes( $cols ); ?>">
	<?php
} else {
	global $woocommerce_loop;
	$cols = empty( $woocommerce_loop['columns'] ) ? null : $woocommerce_loop['columns'];
	?>
	<div class="products <?php echo flatsome_product_row_classes( $cols ); ?>">
	<?php
}
