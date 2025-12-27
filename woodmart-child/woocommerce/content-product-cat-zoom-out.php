<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div <?php wc_product_cat_class( $args['classes'], $args['category'] ); ?> data-loop="<?php echo esc_attr( $args['woocommerce_loop'] ); ?>">
	<?php if ( woodmart_loop_prop( 'products_with_background' ) || woodmart_loop_prop( 'products_bordered_grid' ) && 'inside' === woodmart_loop_prop( 'products_bordered_grid_style' ) ) : ?>
		<div class="wd-cat-wrap">
	<?php endif; ?>

	<div class="wd-cat-inner">
		<div class="wd-cat-thumb">
			<?php do_action( 'woocommerce_after_subcategory', $args['category'] ); ?>
			<div class="wd-cat-image">
				<?php do_action( 'woocommerce_before_subcategory', $args['category'] ); ?>

				<?php
				/**
				 * woocommerce_before_subcategory_title hook
				 *
				 * @hooked woodmart_category_thumb_double_size - 10
				 */
				do_action( 'woocommerce_before_subcategory_title', $args['category'] );
				?>
			</div>
		</div>
		<div class="wd-cat-content wd-fill">
			<a class="wd-fill" href="<?php echo esc_url( get_term_link( $args['category']->slug, 'product_cat' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Product category %s', 'woodmart' ), $args['category']->slug ) ); ?>"></a>
			<div class="wd-cat-header">
				<h3 class="wd-entities-title<?php echo esc_attr( woodmart_get_old_classes( ' category-title' ) ); ?>">
					<a href="<?php echo esc_url( get_term_link( $args['category']->slug, 'product_cat' ) ); ?>">
						<?php
							echo esc_html( $args['category']->name );
						?>
					</a>
				</h3>

				<?php if ( ! woodmart_loop_prop( 'hide_categories_product_count' ) ) : ?>
					<div class="wd-cat-count">
						<?php
						$products_count = $args['category']->count;

						echo esc_html(
							sprintf(
								// translators: %s Products count.
								_n(
									'%s product',
									'%s products',
									$products_count,
									'woodmart'
								),
								$products_count
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( woodmart_loop_prop( 'products_with_background' ) || woodmart_loop_prop( 'products_bordered_grid' ) && 'inside' === woodmart_loop_prop( 'products_bordered_grid_style' ) ) : ?>
		</div>
	<?php endif; ?>
</div>
