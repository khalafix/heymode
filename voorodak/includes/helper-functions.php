<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_ajax_get_users_list_voorodak', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('دسترسی غیرمجاز');
    }
    $users = get_users(['fields' => ['ID', 'user_login']]);
    $bom = "\xEF\xBB\xBF";
    $data = $bom . "نام کاربری,نام و نام خانوادگی,شماره تلفن\n";

    foreach ($users as $user) {
        $first_name = get_user_meta($user->ID, 'first_name', true);
        $last_name = get_user_meta($user->ID, 'last_name', true);
        $billing_phone = get_user_meta($user->ID, 'billing_phone', true);
        $data .= "{$user->user_login}," . ($first_name ? $first_name . ' ' : '') . $last_name . ",{$billing_phone}\n";
    }

    wp_send_json_success($data);
});


function add_lock_voorodak_meta_box() {
    global $post;
    $post_id = $post->ID;
    $voorodak_options = get_option(VOORODAK_OPTION);
    $illegals = [];
    if($voorodak_options && $voorodak_options['login_page_id'] != ''){
        $illegals[] = $voorodak_options['login_page_id'];
    }
    if (function_exists('is_woocommerce')){
        $illegals[] = wc_get_page_id('myaccount');
        $illegals[] = wc_get_page_id('checkout');
    }
    if (in_array($post_id, $illegals)) {
        return;
    }
    add_meta_box(
        'lock_voorodak_meta_box',
        'ورودک',
        'render_lock_voorodak_meta_box',
        ['post', 'page', 'product'],
        'side',
        'default'
    );
}

function render_lock_voorodak_meta_box($post) {
    $value = get_post_meta($post->ID, '_lock_voorodak', true);
    wp_nonce_field('save_lock_voorodak_meta_box', 'lock_voorodak_nonce');
    ?>
    <label for="lock_voorodak" style="margin-top: 10px;display: inline-block;">
        <input type="checkbox" name="lock_voorodak" id="lock_voorodak" value="1" <?php checked($value, '1'); ?> />
        <b>قفل کردن صفحه</b>
    </label>
    <div style="font-size: 12px;margin-top: 5px">با فعالسازی این گزینه، کاربران برای مشاهده این صفحه ابتدا باید در سایت ورود کنند، سپس به این صفحه بازمیگردن</div>
    <?php
}

function save_lock_voorodak_meta_box($post_id) {
    if (!isset($_POST['lock_voorodak_nonce']) || !wp_verify_nonce($_POST['lock_voorodak_nonce'], 'save_lock_voorodak_meta_box')) {
        return;
    }

    if (isset($_POST['lock_voorodak'])) {
        update_post_meta($post_id, '_lock_voorodak', '1');
    } else {
        delete_post_meta($post_id, '_lock_voorodak');
    }
}

add_action('add_meta_boxes', 'add_lock_voorodak_meta_box');
add_action('save_post', 'save_lock_voorodak_meta_box');
