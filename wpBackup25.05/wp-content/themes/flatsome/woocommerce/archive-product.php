<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );

// Add Custom Shop Content if set
if(is_shop() && flatsome_option('html_shop_page_content') && ! $wp_query->is_search() && $wp_query->query_vars['paged'] < 1 ){
   	echo do_shortcode('<div class="shop-page-content">'.flatsome_option('html_shop_page_content').'</div>');
} else {
	if ( fl_woocommerce_version_check( '3.3.0' ) ) {
		wc_get_template_part( 'layouts/category', flatsome_option( 'category_sidebar' ) );
	} else {
		wc_get_template_part( 'back-comp/layouts/w32-category', flatsome_option( 'category_sidebar' ) );
	}
}

get_footer( 'shop' );

?>
