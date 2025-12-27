<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_images_wrapper_class    = 'product-image-summary-inner';
$product_images_class            = 'product-images';
$product_summary_class           = 'summary entry-summary';
$product_summary_wrapper_classes = ' wd-grid-g';
$product_image_summary_class     = ' wd-grid-col';
$container_summary               = 'container';
$container_class                 = 'container';

$product_image_summary_attrs   = '';
$product_summary_wrapper_attrs = '';
$product_images_wrapper_attrs  = '';
$product_summary_attrs         = '';
$product_images_attrs          = '';

$single_product_class = woodmart_single_product_class();
$product_design       = woodmart_product_design();
$breadcrumbs_position = woodmart_get_opt( 'single_breadcrumbs_position' );
$image_width          = woodmart_get_opt( 'single_product_style' );
$full_height_sidebar  = woodmart_get_opt( 'full_height_sidebar' );
$page_layout          = woodmart_get_opt( 'single_product_layout' );
$tabs_location        = woodmart_get_opt( 'product_tabs_location' );
$reviews_location     = woodmart_get_opt( 'reviews_location' );
$product_background   = woodmart_get_opt( 'product-background' );
$single_full_width    = woodmart_get_opt( 'single_full_width' );
$page_layout_specific = get_post_meta( get_the_ID(), '_woodmart_main_layout', true );

if ( $page_layout_specific && 'default' !== $page_layout_specific ) {
	$page_layout = $page_layout_specific;
}

if ( 'alt' === $product_design ) {
	woodmart_enqueue_inline_style( 'woo-single-prod-design-centered' );

	$product_summary_class .= ' text-center';
}

if ( 'default' === $product_design ) {
	if ( is_rtl() ) {
		$product_summary_class .= ' text-right';
	} else {
		$product_summary_class .= ' text-left';
	}
}

woodmart_enqueue_inline_style( 'woo-single-prod-predefined' );
woodmart_enqueue_inline_style( 'woo-single-prod-and-quick-view-predefined' );
woodmart_enqueue_inline_style( 'woo-single-prod-el-tabs-predefined' );

// Full width image layout.
if ( in_array( (int) $image_width, array( 4, 5 ), true ) ) {
	woodmart_enqueue_inline_style( 'woo-single-prod-opt-gallery-full-width' );

	if ( '5' === $image_width ) {
		$product_images_class .= ' wd-stretched';
	}
} else {
	$product_images_wrapper_class .= ' wd-grid-g';
	$product_summary_class        .= ' wd-grid-col';
	$product_images_class         .= ' wd-grid-col';

	$product_summary_style  = '--wd-col-lg:' . woodmart_product_summary_size() . ';';
	$product_summary_style .= in_array( (int) woodmart_get_opt( 'single_product_style' ), array( 4, 5 ), true ) ? '--wd-col-md:12;' : '--wd-col-md:6;';
	$product_summary_style .= '--wd-col-sm:12;';

	$product_images_style  = '--wd-col-lg:' . woodmart_product_images_size() . ';';
	$product_images_style .= in_array( (int) woodmart_get_opt( 'single_product_style' ), array( 4, 5 ), true ) ? '--wd-col-md:12;' : '--wd-col-md:6;';
	$product_images_style .= '--wd-col-sm:12;';

	$product_summary_attrs          = ' style="' . $product_summary_style . '"';
	$product_images_attrs           = ' style="' . $product_images_style . '"';
	$product_images_wrapper_attrs  .= ' style="--wd-col-lg:12;--wd-gap-lg:30px;--wd-gap-sm:20px;"';
}

$product_summary_wrapper_attrs .= ' style="--wd-col-lg:12;--wd-gap-lg:30px;--wd-gap-sm:20px;"';

if ( $single_full_width ) {
	$container_summary = 'container-fluid';
}

if ( $full_height_sidebar && 'full-width' !== $page_layout ) {
	$container_summary = 'container-none';
	$container_class   = 'container-none';

	$product_image_summary_attrs = ' style="--wd-col-lg:12"';
} else {
	$product_image_summary_attrs = ' style=' . woodmart_get_content_inline_style() . '"';
}

if ( 'full-width' === $page_layout || $full_height_sidebar ) {
	$product_summary_wrapper_attrs   = '';
	$product_summary_wrapper_classes = '';

	$product_image_summary_class = '';
	$product_image_summary_attrs = '';
}

if ( woodmart_get_opt( 'product_sticky' ) || woodmart_get_opt( 'product_summary_shadow' ) || ! empty( $product_background['color'] ) || ! empty( $product_background['id'] ) || ! empty( get_post_meta( $product->get_id(), '_woodmart_extra_content', true ) ) || $single_full_width ) {
	woodmart_enqueue_inline_style( 'woo-single-prod-opt-base' );
}

?>

<?php if ( ( ( $product_design == 'alt' && ( $breadcrumbs_position == 'default' || empty( $breadcrumbs_position ) ) ) || $breadcrumbs_position == 'below_header' ) && ( woodmart_get_opt( 'product_page_breadcrumbs', '1' ) || woodmart_get_opt( 'products_nav' ) ) ) : ?>
	<?php $breadcrumbs_classes = $full_height_sidebar && 'full-width' !== $page_layout ? 'container-none' : 'container'; ?>

	<div class="single-breadcrumbs-wrapper">
		<div class="wd-grid-f <?php echo esc_attr( $breadcrumbs_classes ); ?>">
			<?php if ( woodmart_get_opt( 'product_page_breadcrumbs', '1' ) ) : ?>
				<?php woodmart_current_breadcrumbs( 'shop' ); ?>
			<?php endif; ?>

			<?php if ( woodmart_get_opt( 'products_nav' ) ) : ?>
				<?php wc_get_template( 'single-product/navigation.php' ); ?>
			<?php endif ?>
		</div>
	</div>
<?php endif ?>

<?php if ( has_action( 'woocommerce_before_single_product' ) || post_password_required() ) : ?>
	<div class="container">
		<?php
			/**
			 * Hook: woocommerce_before_single_product.
			 */
			do_action( 'woocommerce_before_single_product' );

		if ( post_password_required() ) {
			echo get_the_password_form();
			return;
		}

		?>
	</div>
<?php endif; ?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $single_product_class, $product ); ?>>

	<div class="<?php echo esc_attr( $container_summary ); ?>">

		<?php
			/**
			 * Hook: woodmart_before_single_product_summary_wrap.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 */
			do_action( 'woodmart_before_single_product_summary_wrap' );
		?>

		<div class="product-image-summary-wrap<?php echo esc_attr( $product_summary_wrapper_classes ); ?>"<?php echo wp_kses( $product_summary_wrapper_attrs, true ); ?>>
			<?php
				if ( ! $full_height_sidebar && 'sidebar-left' === woodmart_get_page_layout() ) {
					/**
					 * Hook woocommerce_sidebar
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				}
			?>

			<div class="product-image-summary<?php echo esc_attr( $product_image_summary_class ); ?>" <?php echo wp_kses( $product_image_summary_attrs, true ); ?>>
				<div class="<?php echo esc_attr( $product_images_wrapper_class ); ?>"<?php echo wp_kses( $product_images_wrapper_attrs, true ); ?>>
					<div class="<?php echo esc_attr( $product_images_class ); ?>"<?php echo wp_kses( $product_images_attrs, true ); ?>>
						<?php
							/**
							 * Hook woocommerce_before_single_product_summary
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							do_action( 'woocommerce_before_single_product_summary' );
						?>
					</div>
					<div class="<?php echo esc_attr( $product_summary_class ); ?>"<?php echo wp_kses( $product_summary_attrs, true ); ?>>
						<div class="summary-inner wd-set-mb reset-last-child">
							<?php if ( ( ( $product_design == 'default' && ( $breadcrumbs_position == 'default' || empty( $breadcrumbs_position ) ) ) || $breadcrumbs_position == 'summary' ) && ( woodmart_get_opt( 'product_page_breadcrumbs', '1' ) || woodmart_get_opt( 'products_nav' ) ) ) : ?>
								<div class="single-breadcrumbs-wrapper wd-grid-f">
									<?php if ( woodmart_get_opt( 'product_page_breadcrumbs', '1' ) ) : ?>
										<?php woodmart_current_breadcrumbs( 'shop' ); ?>
									<?php endif; ?>
									<?php if ( woodmart_get_opt( 'products_nav' ) ) : ?>
										<?php wc_get_template( 'single-product/navigation.php' ); ?>
									<?php endif ?>
								</div>
							<?php endif ?>

							<?php
								/**
								 * Hook woocommerce_single_product_summary
								 *
								 * @hooked woocommerce_template_single_title - 5
								 * @hooked woocommerce_template_single_rating - 10
								 * @hooked woocommerce_template_single_price - 10
								 * @hooked woocommerce_template_single_excerpt - 20
								 * @hooked woocommerce_template_single_add_to_cart - 30
								 * @hooked woocommerce_template_single_meta - 40
								 * @hooked woocommerce_template_single_sharing - 50
								 */
								do_action( 'woocommerce_single_product_summary' );
							?>
						</div>
					</div>
				</div>
			</div>

			<?php
			if ( ! $full_height_sidebar && 'sidebar-left' !== woodmart_get_page_layout() ) {
				/**
				 * Hook woocommerce_sidebar
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
			}
			?>

		</div>

		<?php
			/**
			 * Hook woodmart_after_product_content
			 *
			 * @hooked woodmart_product_extra_content - 20
			 */
			do_action( 'woodmart_after_product_content' );
		?>

	</div>

	<?php if ( $tabs_location != 'summary' || $reviews_location == 'separate' ) : ?>
		<div class="product-tabs-wrapper">
			<div class="<?php echo esc_attr( $container_class ); ?> product-tabs-inner">
				<?php
					/**
					 * Hook woocommerce_after_single_product_summary
					 *
					 * @hooked woocommerce_output_product_data_tabs - 10
					 * @hooked woocommerce_upsell_display - 15
					 * @hooked woocommerce_output_related_products - 20
					 */
					do_action( 'woocommerce_after_single_product_summary' );
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php do_action( 'woodmart_after_product_tabs' ); ?>

	<div class="<?php echo esc_attr( $container_class ); ?> related-and-upsells">
		<?php
			/**
			 * Hook woodmart_woocommerce_after_sidebar
			 *
			 * @hooked woocommerce_upsell_display - 10
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action( 'woodmart_woocommerce_after_sidebar' );
		?>
	</div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
