<?php
/**
 * Single Product Sale Flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if ( $product->is_on_sale() ) : ?>
	<div class="onsale-wrap">
		<div class="onsale-inner">
	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale jv-woo-button">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
		</div>
	</div>
<?php endif; ?>