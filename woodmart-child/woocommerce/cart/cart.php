<?php
defined('ABSPATH') || exit;
?>

<div class="cart-content-wrapper">

	<?php do_action('woocommerce_before_cart'); ?>

	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

		<?php do_action('woocommerce_before_cart_table'); ?>

		<div class="cart-items-wrapper">

			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :

				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0) :
					$product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
					$product_name      = $_product->get_name();
			?>

					<div class="cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">


						<!-- تصویر محصول -->
						<div class="cart-item-thumb">
							<?php
							$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

							if ($product_permalink) {
								echo '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';
							} else {
								echo $thumbnail;
							}
							?>
						</div>

						<!-- نام محصول -->
						<div class="cart-item-title">
							<div class="cart-item-title-content">
								<?php
								if ($product_permalink) {
									echo '<a href="' . esc_url($product_permalink) . '">' . esc_html($product_name) . '</a>';
								} else {
									echo esc_html($product_name);
								}

								// meta
								echo wc_get_formatted_cart_item_data($cart_item);

								// backorder
								if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
									echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
								}
								?>
							</div>
							<!-- تعداد -->
							<div class="cart-item-qty">
								<?php
								if ($_product->is_sold_individually()) {
									$min = 1;
									$max = 1;
								} else {
									$min = 0;
									$max = $_product->get_max_purchase_quantity();
								}

								echo apply_filters(
									'woocommerce_cart_item_quantity',
									woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max,
											'min_value'    => $min,
											'product_name' => $product_name,
										),
										$_product,
										false
									),
									$cart_item_key,
									$cart_item
								);
								?>
							</div>
							

						</div>
						<div class="cart-item-subtotal-row">
							
							<!-- حذف محصول -->
							<div class="cart-item-remove">
								<?php
								echo apply_filters(
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s">
									<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="44" height="44" rx="10" fill="#E80645" fill-opacity="0.06"/>
<path d="M31 15.98C27.67 15.65 24.32 15.48 20.98 15.48C19 15.48 17.02 15.58 15.04 15.78L13 15.98" stroke="#E80645" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M18.5 14.97L18.72 13.66C18.88 12.71 19 12 20.69 12H23.31C25 12 25.13 12.75 25.28 13.67L25.5 14.97" stroke="#E80645" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M28.8499 19.14L28.1999 29.21C28.0899 30.78 27.9999 32 25.2099 32H18.7899C15.9999 32 15.9099 30.78 15.7999 29.21L15.1499 19.14" stroke="#E80645" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20.3301 26.5H23.6601" stroke="#E80645" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19.5 22.5H24.5" stroke="#E80645" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
									</a>',
										esc_url(wc_get_cart_remove_url($cart_item_key)),
										esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name)))
									),
									$cart_item_key
								);
								?>
							</div>
							<!-- جمع جزء -->
							<div class="cart-item-subtotal">
								<?php
								echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
								?>
							</div>
						</div>

					</div><!-- .cart-item -->

			<?php endif;
			endforeach; ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>

			<div class="cart-actions">

				<?php if (wc_coupons_enabled()): ?>
					<div class="coupon">
						<label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
						<input type="text" name="coupon_code" id="coupon_code" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>">
						<button type="submit" class="button" name="apply_coupon"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
					</div>
				<?php endif; ?>

				<button type="submit" class="button update-cart" name="update_cart">
					<?php esc_html_e('Update cart', 'woocommerce'); ?>
				</button>

				<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>

			</div>

		</div><!-- .cart-items-wrapper -->

		<?php do_action('woocommerce_after_cart_table'); ?>

	</form>

	<?php do_action('woocommerce_cart_collaterals'); ?>

</div>