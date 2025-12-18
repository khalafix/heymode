<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
$urls = [
    'dashboard' => wc_get_page_permalink( 'myaccount' ),
    'orders' => wc_get_account_endpoint_url( 'orders' ),
    'edit-account' => wc_get_account_endpoint_url( 'edit-account' ),
    'edit-address' => wc_get_account_endpoint_url( 'edit-address' ),
    'payment-methods' => wc_get_account_endpoint_url( 'payment-methods' ),
    'logout' => wc_logout_url(),
];

?>
<nav class="myaccount-nav">
    <ul>
        <?php foreach ( myaccount_menu_items() as $key => $item ) : ?>
            <li class="<?= myaccount_is_active( $key ) ? 'is-active' : ''; ?>">
                <a href="<?= esc_url( $item['url'] ); ?>">
                    <span class="icon icon-<?= esc_attr( $item['icon'] ); ?>"></span>
                    <span class="text"><?= esc_html( $item['label'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>


<?php do_action( 'woocommerce_after_account_navigation' ); ?>
