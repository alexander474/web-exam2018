<?php

add_shortcode("ux_product_gallery", function ($atts) {
  extract(shortcode_atts(array(
    'style' => 'normal',
  ), $atts));

  add_filter('theme_mod_product_image_style', function($input) use ($style){
    return $style;
  });

  if($style == 'full-width') {
    add_filter('theme_mod_product_layout', function($input) use ($style){
      return 'gallery-wide';
    });
  }

  ob_start();
  wc_get_template_part( 'single-product/product-image');
  return ob_get_clean();
});

add_shortcode("ux_product_title", function ($atts) {
  extract(shortcode_atts(array(
    'size' => false,
    'divider' => true,
    'case' => 'normal',
    'uppercase' => false,
  ), $atts));

  add_filter('theme_mod_product_title_divider', function($input) use ($divider){
    if($divider) return true;
  });

  $classes = array('product-title-container');
  if($size) $classes[] = 'is-'.$size;
  if($uppercase) $classes[] = 'is-uppercase';

  ob_start();
  echo '<div class="'.implode(' ', $classes).'">';
  woocommerce_template_single_title();
  echo '</div>';
  return ob_get_clean();
});

add_shortcode("ux_product_rating", function ($atts) {
  extract(shortcode_atts(array(
    'size' => 'normal',
  ), $atts));

  ob_start();
    woocommerce_template_single_rating();
  return ob_get_clean();
});

add_shortcode("ux_product_hook", function ($atts) {
  extract(shortcode_atts(array(
    'hook' => 'woocommerce_single_product_summary',
  ), $atts));

  ob_start();
  do_action($hook);
  return ob_get_clean();
});


add_shortcode("ux_product_price", function ($atts) {
  extract(shortcode_atts(array(
    'size' => 'normal',
  ), $atts));

  ob_start();
  echo '<div class="product-price-container is-'.$size.'">';
    woocommerce_template_single_price();
  echo '</div>';
  return ob_get_clean();
});

add_shortcode("ux_product_excerpt", function ($atts) {
  extract(shortcode_atts(array(
    'type' => 'default',
  ), $atts));

  ob_start();
  woocommerce_template_single_excerpt();
  return ob_get_clean();
});

add_shortcode("ux_product_description", function ($atts) {
  extract(shortcode_atts(array(
    'type' => 'default',
  ), $atts));

  ob_start();
  the_content();
  return ob_get_clean();
});

add_shortcode("ux_product_add_to_cart", function ($atts) {
  extract(shortcode_atts(array(
    'style' => 'normal',
    'size' => 'normal'
  ), $atts));

  ob_start();
  echo '<div class="add-to-cart-container form-'.$style.' is-'.$size.'">';
    woocommerce_template_single_add_to_cart();
  echo '</div>';
  return ob_get_clean();
});

add_shortcode("ux_product_meta", function ($atts) {
  extract(shortcode_atts(array(
    'type' => 'default',
  ), $atts));

  ob_start();
  woocommerce_template_single_meta();
  return ob_get_clean();
});

add_shortcode("ux_product_tabs", function ($atts) {
  extract(shortcode_atts(array(
    'style' => 'tabs',
    'align' => 'left'
  ), $atts));

  add_filter('theme_mod_product_display', function($input) use ($style){
       if($style) return $style;
  });

  add_filter('theme_mod_product_tabs_align', function($input) use ($align){
       if($align) return $align;
  });

  ob_start();
  wc_get_template_part( 'single-product/tabs/tabs' );
  return ob_get_clean();
});


add_shortcode("ux_product_upsell", function ($atts) {
  extract(shortcode_atts(array(
    'style' => 'sidebar',
  ), $atts));

  add_filter('theme_mod_product_upsell', function($input) use ($style){
       if($style) return $style;
  });

  ob_start();
  woocommerce_upsell_display();
  return ob_get_clean();
});

add_shortcode("ux_product_related", function ($atts) {
  extract(shortcode_atts(array(
    'style' => 'slider',
  ), $atts));

  add_filter('theme_mod_related_products', function($input) use ($style){
       if($style) return $style;
  });

  ob_start();
  woocommerce_output_related_products();
  return ob_get_clean();
});

add_shortcode("ux_product_breadcrumbs", function ($atts) {
  extract(shortcode_atts(array(
    'size' => 'normal',
  ), $atts));

  ob_start();
  echo '<div class="product-breadcrumb-container is-'.$size.'">';
  woocommerce_breadcrumb();
  echo '</div>';
  return ob_get_clean();
});
