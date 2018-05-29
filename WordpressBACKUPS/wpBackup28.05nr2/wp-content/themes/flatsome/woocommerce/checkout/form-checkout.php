<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$wrapper_classes = array();
$row_classes = array();
$main_classes = array();
$sidebar_classes = array();

$layout = get_theme_mod('checkout_layout');

if(!$layout){
  $sidebar_classes[] = 'has-border';
}

if($layout == 'simple'){
  $sidebar_classes[] = 'is-well';
}

$wrapper_classes = implode(" ", $wrapper_classes);
$row_classes = implode(" ", $row_classes);
$main_classes = implode(" ", $main_classes);
$sidebar_classes = implode(" ", $sidebar_classes);

wc_print_notices(); ?>

<?php

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() ); ?>

<?php
// Social login
if(flatsome_option('facebook_login_checkout') && get_option('woocommerce_enable_myaccount_registration')=='yes' && !is_user_logged_in()){
	wc_get_template('checkout/social-login.php');
} ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout <?php echo $wrapper_classes; ?>" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">

	<div class="row pt-0 <?php echo $row_classes; ?>">
  	<div class="large-7 col  <?php echo $main_classes; ?>">
    <?php if ( $checkout->checkout_fields ) : ?>

  		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

  		<div id="customer_details">
  			<div class="clear">
  				<?php do_action( 'woocommerce_checkout_billing' ); ?>
  			</div>
  			<div class="clear">
  				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
  			</div>
  		</div>

  		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

  	<?php endif; ?>
  	</div><!-- large-7 -->

  	<div class="large-5 col">
      <?php if(get_theme_mod('checkout_sticky_sidebar', 0)) { ?>
      <div class="is-sticky-column">
      <div class="is-sticky-column__inner">
      <?php } ?>

  		<div class="col-inner <?php echo $sidebar_classes; ?>">
  			<div class="checkout-sidebar sm-touch-scroll">
  				<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
  				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

  				<div id="order_review" class="woocommerce-checkout-review-order">
  					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
  				</div>
  				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
  			</div>
  		</div>

      <?php if(get_theme_mod('checkout_sticky_sidebar', 0)) { ?>
      </div>
      </div>
      <?php } ?>
  	</div><!-- large-5 -->

	</div><!-- row -->
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
