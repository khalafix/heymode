<?php

add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 1000 );

function woodmart_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}

function myaccount_menu_items() {

    $user = wp_get_current_user();

    return [

        // پروفایل (نام نمایشی کاربر)
        'profile' => [
            'label' => $user->display_name,
            'url'   => wc_get_page_permalink( 'myaccount' ),
            'icon'  => 'user',
        ],

        // سفارش‌های من
        'orders' => [
            'label' => 'سفارش‌های من',
            'url'   => wc_get_account_endpoint_url( 'orders' ),
            'icon'  => 'orders',
        ],

        // علاقه‌مندی‌ها (WoodMart Wishlist)
        'wishlist' => [
            'label' => 'علاقه‌مندی‌ها',
            'url'   => function_exists( 'woodmart_get_wishlist_page_url' )
                ? woodmart_get_wishlist_page_url()
                : home_url( '/wishlist/' ),
            'icon'  => 'wishlist',
        ],

        // آدرس‌های من
        'edit-address' => [
            'label' => 'آدرس‌های من',
            'url'   => wc_get_account_endpoint_url( 'edit-address' ),
            'icon'  => 'address',
        ],

        // کیف پول (مثلاً TeraWallet یا مشابه)
        'wallet' => [
            'label' => 'کیف پول',
            'url'   => wc_get_account_endpoint_url( 'wallet' ),
            'icon'  => 'wallet',
        ],

        // خروج
        'logout' => [
            'label' => 'خروج',
            'url'   => wc_logout_url(),
            'icon'  => 'logout',
        ],
    ];
}
function myaccount_is_active( $endpoint ) {

    if ( $endpoint === 'dashboard' && is_account_page() && ! is_wc_endpoint_url() ) {
        return true;
    }

    return is_wc_endpoint_url( $endpoint );
}

