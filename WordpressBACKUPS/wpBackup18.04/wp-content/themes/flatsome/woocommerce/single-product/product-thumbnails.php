<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see       https://docs.woocommerce.com/document/template-structure/
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version     3.3.2
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$image_size = get_theme_mod('product_layout') == 'gallery-wide' ? 'full' : 'woocommerce_single';


if ( $attachment_ids && has_post_thumbnail() ) {
  foreach ( $attachment_ids as $attachment_id ) {
    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', flatsome_wc_get_gallery_image_html( $attachment_id, $main_image = false, $image_size ), $attachment_id );
  }
}
