<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Increase loop count
$woocommerce_loop['loop'] ++; ?>

<div class="grid__item">
	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
		<article class="card  card--product  card--product-cat">

			<?php do_action( 'woocommerce_before_subcategory', $category ); ?>

			<div class="card__content">
				<?php
				/**
				 * woocommerce_before_subcategory_title hook
				 *
				 * @hooked woocommerce_subcategory_thumbnail - 10
				 */
				do_action( 'woocommerce_before_subcategory_title', $category );
				?>
				<h2 class="card__title">
					<?php
					echo $category->name;

					if ( $category->count > 0 )
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">(' . $category->count . ')</span>', $category );
					?>
				</h2>
				<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
				?>
			</div>

			<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

		</article>
	</a>
</div>