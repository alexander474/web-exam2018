<?php

// Fix Cart Totals Title style
if(!function_exists('flatsome_woocommerce_before_cart_totals')) {
  function flatsome_woocommerce_before_cart_totals(){  ?>
          <table cellspacing="0">
          <thead>
              <tr>
                  <th class="product-name" colspan="2" style="border-width:3px;"><?php _e( 'Cart totals', 'woocommerce' ); ?></th>
              </tr>
          </thead>
          </table>
  <?php }
}
add_action( 'woocommerce_before_cart_totals', 'flatsome_woocommerce_before_cart_totals' );


// Custom Thank You Html
function flatsome_thank_you_html(){
    echo get_theme_mod('html_thank_you');
}
add_action( 'woocommerce_thankyou', 'flatsome_thank_you_html', 100);

// Add HTML Checkout sidebar
if(!function_exists('flatsome_html_checkout_sidebar')) {
  function flatsome_html_checkout_sidebar(){
     $content = flatsome_option('html_checkout_sidebar');
     echo '<div class="html-checkout-sidebar pt-half">'.do_shortcode($content).'</div>';
  }
}
add_action('woocommerce_checkout_after_order_review', 'flatsome_html_checkout_sidebar');


function flatsome_override_existing_checkout_fields( $fields ) {

  // Make sure address 1 and address 2 is on same line
  $fields['address_1']['class'][] = 'form-row-first';
  $fields['address_2']['class'][] = 'form-row-last';
  $fields['address_2']['label'] = esc_attr__( 'Apartment, suite, unit etc. (optional)', 'woocommerce' );

  // Remove "form-row-wide" class from address 1 and address 2
  if($fields['address_1']['class'][0] == 'form-row-wide') unset($fields['address_1']['class'][0]);
  if($fields['address_2']['class'][0] == 'form-row-wide') unset($fields['address_2']['class'][0]);

  // Fix labels for floating labels option
  if(get_theme_mod('checkout_floating_labels', 0)) {
    $fields['address_1']['placeholder'] = __( 'Street address', 'woocommerce' );
    $fields['address_2']['label'] = esc_attr__( 'Apartment, suite, unit etc. (optional)', 'woocommerce' );

    // Set Placeholders
    foreach ($fields as $key => $value) {
      if(isset($fields[$key]['label']) && !isset($fields[$key]['placeholder'])) {
        $fields[$key]['placeholder'] = $fields[$key]['label'];
      }
    }
  }

  return $fields;
}

add_filter( 'woocommerce_default_address_fields' , 'flatsome_override_existing_checkout_fields' );


function flatsome_move_checkout_fields( $fields ) {
	// Move email to top
	if ( get_theme_mod( 'checkout_fields_email_first', 0 ) ) {
		$fields['billing']['billing_email']['priority'] = -1;

		$billing_email = $fields['billing']['billing_email'];
		unset( $fields['billing']['billing_email'] );
		$fields['billing'] = array( 'billing_email' => $billing_email ) + $fields['billing'];
	}

	// Fix auto scrolling
	if ( isset( $fields['billing'] ) ) $fields['billing']['billing_first_name']['autofocus'] = false;
	if ( isset( $fields['shipping'] ) ) $fields['shipping']['shipping_first_name']['autofocus'] = false;

	return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'flatsome_move_checkout_fields' );


/* Floating labels option */

function flatsome_checkout_scripts() {
  if(is_checkout() && get_theme_mod('checkout_floating_labels', 0)) {
    wp_enqueue_script( 'flatsome-woocommerce-floating-labels', get_template_directory_uri() .'/assets/libs/float-labels.min.js', array( 'flatsome-theme-woocommerce-js' ), '3.5', true );
    wp_dequeue_style( 'selectWoo' );
    wp_deregister_style( 'selectWoo' );
    wp_dequeue_script( 'selectWoo' );
    wp_deregister_script( 'selectWoo' );
  }
}

add_action( 'wp_enqueue_scripts', 'flatsome_checkout_scripts', 100 );

function flatsome_checkout_body_classes( $classes ) {
  if( is_checkout() && get_theme_mod('checkout_floating_labels', 0))  {
    $classes[] = 'fl-labels';
  }
  return $classes;
}
add_filter( 'body_class', 'flatsome_checkout_body_classes' );
