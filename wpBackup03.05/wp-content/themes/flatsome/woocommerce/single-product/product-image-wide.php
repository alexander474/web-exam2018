<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . $placeholder,
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);

$slider_classes = array( 'product-gallery-slider', 'slider', 'slider-nav-circle', 'mb-half', 'slider-style-container', 'slider-nav-light', 'slider-load-first', 'no-overflow' );
$rtl = 'false';
if(is_rtl()) $rtl = 'true';

?>
<?php do_action( 'flatsome_before_product_images' ); ?>

<div class="product-images slider-wrapper relative mb-half has-hover <?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?> " data-columns="<?php echo esc_attr( $columns ); ?>">
	<div class="absolute left right">
		<div class="container relative">
			<?php do_action( 'flatsome_sale_flash' ); ?>
		</div>
	</div>

	<figure class="woocommerce-product-gallery__wrapper <?php echo implode( ' ', $slider_classes ); ?>"
			data-flickity-options='{
				"cellAlign": "center",
				"wrapAround": true,
				"autoPlay": false,
				"prevNextButtons":true,
				"adaptiveHeight": true,
				"imagesLoaded": true,
				"lazyLoad": 1,
				"dragThreshold" : 15,
				"pageDots": false,
				"rightToLeft": <?php echo $rtl; ?>
			}'
			style="background-color: #333;">
		<?php

    if ( has_post_thumbnail() ) {
      $html  = flatsome_wc_get_gallery_image_html( $post_thumbnail_id, true, 'full' );
    } else {
      $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
      $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
      $html .= '</div>';
    }

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

    do_action( 'woocommerce_product_thumbnails' );
		?>
	</figure>

	<div class="loading-spin centered dark"></div>

	<div class="absolute bottom left right">
		<div class="container relative image-tools">
			<div class="image-tools absolute bottom right z-3">
				<?php do_action( 'flatsome_product_image_tools_bottom' ); ?>
				<?php do_action( 'flatsome_product_image_tools_top' ); ?>
			</div>
		</div>
	</div>

</div>
<?php do_action( 'flatsome_after_product_images' ); ?>
