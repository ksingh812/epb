<?php
/**
 * In this file we will put every function or hook which is needed to provide woocommerce compatibility
 */

/**
 * First remove the woocommerce style. We'll provide one.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//mode the payment options on checkout after billing details
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20 );

function timber_woocommerce_remove_tabs_header_desc( $desc ){
	return '';
}
add_filter('woocommerce_product_description_heading', 'timber_woocommerce_remove_tabs_header_desc', 11);

// remove the title from woocommerce_single_product_summary because we are calling it a few lines before
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

// remove the breadcrumb from woocommerce_before_main_content because we are calling it after title
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// remove rating from woocommerce_single_product_summary, it doesn't apply on our design.
// if you really need this, override this file with a child theme
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

add_action ( 'woocommerce_after_cart_table', 'listable_woocommerce_proceed_to_checkout');

if ( ! function_exists( 'listable_woocommerce_proceed_to_checkout') ) :
	function listable_woocommerce_proceed_to_checkout() { ?>

		<div class="wc-proceed-to-checkout">

				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

		</div>

	<?php }
endif;

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_checkout_login_form', 10 );

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_checkout_coupon_form', 10 );

function listable_checkout_place_order_button() {
	wc_get_template( 'checkout/place-order-button.php', array(
			'order_button_text'  => apply_filters( 'woocommerce_order_button_text', esc_html__( 'Place order', 'listable' ) )
	) );
}

add_action( 'woocommerce_checkout_shipping', 'listable_checkout_place_order_button', 20 );


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
	function woocommerce_template_loop_product_thumbnail() {
		echo woocommerce_get_product_thumbnail();
	}
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $product, $post;
		$output = '<div class="card__image  card__image--product"';

		if ( has_post_thumbnail() ) {
			$image = wp_get_attachment_image_src ( get_post_thumbnail_id( $post->ID ), $size, true );
			$output .= ' style="background-image: url('. $image[0] .')" ';
		}

		$output .= '>';
		$output .= '<span class="product__price">' . $product->get_price_html() . '</span>';

		$posttags = wp_get_post_terms( get_the_ID($product->ID) , 'product_tag' , 'fields=names' );
		if($posttags) $output .= '<span class="product__tag">' . $posttags[0] . '</span>';

		$output .= '</div><!-- card__image -->';
		return $output;
	}
}
