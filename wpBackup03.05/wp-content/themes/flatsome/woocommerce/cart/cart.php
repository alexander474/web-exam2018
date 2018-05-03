<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! fl_woocommerce_version_check( '3.3.0' ) ) {
  wc_get_template( 'back-comp/cart/w32-cart.php' );
  return;
}

$row_classes = array();
$main_classes = array();
$sidebar_classes = array();

$layout = get_theme_mod('checkout_layout');
$row_classes[] = 'row-large';

if(!$layout){
  $row_classes[] = 'row-divided';
}

if($layout == 'simple'){
  $sidebar_classes[] = 'is-well';
}


$row_classes = implode(" ", $row_classes);
$main_classes = implode(" ", $main_classes);
$sidebar_classes = implode(" ", $sidebar_classes);


do_action( 'woocommerce_before_cart' ); ?>
<div class="woocommerce row <?php echo $row_classes; ?>">
<div class="col large-7 pb-0 <?php echo $main_classes; ?>">

<?php wc_print_notices(); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
<div class="cart-wrapper sm-touch-scroll">

  <?php do_action( 'woocommerce_before_cart_table' ); ?>

  <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
    <thead>
      <tr>
        <th class="product-name" colspan="3"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
        <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
        <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
        <th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php do_action( 'woocommerce_before_cart_contents' ); ?>

      <?php
      foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
          $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
          ?>
          <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

            <td class="product-remove">
              <?php
                // @codingStandardsIgnoreLine
                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                  '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                  esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                  __( 'Remove this item', 'woocommerce' ),
                  esc_attr( $product_id ),
                  esc_attr( $_product->get_sku() )
                ), $cart_item_key );
              ?>
            </td>

            <td class="product-thumbnail"><?php
            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

            if ( ! $product_permalink ) {
              echo $thumbnail;
            } else {
              printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
            }
            ?></td>

            <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>"><?php
            if ( ! $product_permalink ) {
              echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
            } else {
              echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
            }

            // Meta data.
            echo wc_get_formatted_cart_item_data( $cart_item );

            // Backorder notification.
            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
              echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
            }

            //Mobile price
            ?>
              <p class="show-for-small mobile-product-price">
              <span class="mobile-product-price__qty"><?php echo $cart_item['quantity']; ?> x </span>
               <?php
                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                ?>
              </p>
            </td>

            <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
              <?php
                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
              ?>
            </td>

            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>"><?php
            if ( $_product->is_sold_individually() ) {
              $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
            } else {
              $product_quantity = woocommerce_quantity_input( array(
                'input_name'    => "cart[{$cart_item_key}][qty]",
                'input_value'   => $cart_item['quantity'],
                'max_value'     => $_product->get_max_purchase_quantity(),
                'min_value'     => '0',
                'product_name'  => $_product->get_name(),
              ), $_product, false );
            }

            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
            ?></td>

            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
              <?php
                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
              ?>
            </td>
          </tr>
          <?php
        }
      }
      ?>

      <?php do_action( 'woocommerce_cart_contents' ); ?>

      <tr>
        <td colspan="6" class="actions clear">

          <?php do_action( 'woocommerce_cart_actions' ); ?>

          <button type="submit" class="button primary mt-0 pull-left small" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

          <?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </td>
      </tr>

      <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    </tbody>
  </table>
  <?php do_action( 'woocommerce_after_cart_table' ); ?>
</div>
</form>
</div>

<div class="cart-collaterals large-5 col pb-0">
<?php if(get_theme_mod('cart_sticky_sidebar')) { ?>
  <div class="is-sticky-column">
  <div class="is-sticky-column__inner">
<?php } ?>

	<div class="cart-sidebar col-inner <?php echo $sidebar_classes; ?>">
		<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
		?>
		<?php if ( wc_coupons_enabled() ) { ?>
		<form class="checkout_coupon mb-0" method="post">
			<div class="coupon">
				<h3 class="widget-title"><?php echo get_flatsome_icon('icon-tag'); ?> <?php esc_html_e( 'Coupon', 'woocommerce' ); ?></h3><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="is-form expand" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		</form>
		<?php } ?>
		<?php do_action( 'flatsome_cart_sidebar' ); ?>
	</div>
<?php if(get_theme_mod('cart_sticky_sidebar')) { ?>
  </div>
  </div>
<?php } ?>
</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
