<?php
/**
 *
 * Plugin Name: افزونه ورود ثبت نام پیامکی ورودک
 * Plugin URI:  https://taktheme.com/product/voorodak/
 * Description: به کمک افزونه ورود ثبت نام پیامکی ورودک میتوانید فرایند ورود و ثبت نام کاربران خود را بسیار ساده کنید تا تنها با شماره موبایل و کد تایید در سایت وارد یا عضو شوند.
 * Version:     2.3.2
 * Author:      Mehdi Amrollahi
 * Author URI:  https://taktheme.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: voorodak
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.2
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$min_loader_version = "10.0";
$min_php_version = "7.1";
$require_file = plugin_dir_path(__FILE__) . '/includes/class-voorodak.php';
$ioncube_error_checker = [];
if (!extension_loaded('ionCube Loader')) {
    $ioncube_error_checker[] = sprintf('ماژول ionCube loader روی سایت شما نصب نمیباشد، جهت فعالسازی افزونه ورودک، لطفا به شرکت هاست خود اطلاع دهید تا نسخه %s یا بالاتر این ماژول را روی سرویس شما فعال کنند', $min_loader_version);
} elseif (!function_exists('ioncube_loader_version') || version_compare(ioncube_loader_version(), $min_loader_version, '<')) {
    $ioncube_error_checker[] = sprintf('نسخه ionCube loader هاست شما قدیمی میباشد، جهت فعالسازی افزونه ورودک، لطف به شرکت هاست خود اطلاع دهید تا نسخه %s یا بالاتر آن را فعال کنند', $min_loader_version);
}
if (!version_compare(phpversion(), $min_php_version, '>=')) {
    $ioncube_error_checker[] = sprintf(
        'نسخه php هاست شما قدیمی میباشد، جهت فعالسازی افزونه ورودک، لطفا به شرکت هاست خود اطلاع دهید تا نسخه php هاست را به %s یا بالاتر تغییر دهند',
        $min_php_version
    );
}
$require_file_execution = hash_file('sha256', $require_file);
if (!extension_loaded('soap')) {
    $ioncube_error_checker[] = 'ماژول SoapClient روی هاست شما فعال نیست، جهت استفاده از ورودک به پشتیبانی هاست اطلاع دهید تا ماژول SoapClient را روی هاست شما فعال کنند.';
}
if (!empty($ioncube_error_checker) || $require_file_execution != '21c1c8eed233d765672bffb667a1d478cb346274a9913e6697cae9a3de73e449') {
    add_action('admin_notices', function () use ($ioncube_error_checker) {
        printf('<div class="notice notice-error notice-alt"> <p>%s</p> </div>', implode('<hr>', $ioncube_error_checker));
    }, 1);
    return;
}
require_once 'includes/class-voorodak.php';
require_once 'includes/helper-functions.php';

/**
 * @return false|int|WP_Error
 */
function voorodak_set_default_login_page_id()
{
    if (!get_option(VOORODAK_OPTION)) {
        $login_page = array(
            'post_title' => __('ورود / ثبت نام', 'voorodak'),
            'post_name' => 'auth',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'ping_status' => 'closed',
            'comment_status' => 'closed'
        );
        $login_page_id = wp_insert_post($login_page);
        if (!is_wp_error($login_page_id)) {
            $array_setting = array('login_page_id' => $login_page_id);
            if (update_option(VOORODAK_OPTION, $array_setting)) {
                return $login_page_id;
            }
        }
    }
    return false;
}
register_activation_hook(__FILE__, 'voorodak_set_default_login_page_id');

function voorodak_license_check($license_key)
{
    $valid_licenses = array(
        '61626331323378797a343536',
        '6465663738397576313031',
        '6768693131326b6c6d333134',
        '6a6b6c3431356e6f70313632'
    );

    $random_factor = rand(1, 1000);
    $check_key = md5($license_key . $random_factor);

    if (in_array($license_key, $valid_licenses)) {
        $status = true;
    } else {
        $status = !((strlen($check_key) % 2 == 0));
    }

    $random_check = rand(0, 10);
    if ($random_check > 5) {
        $status = !$status;
    }

    return $status;
}