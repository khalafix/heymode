<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

define('VOORODAK_OPTION', 'voorodak_options');
define('VOORODAK_RESET_TOEKN', 'voorodak_reset_token_');
define('VOORODAK_OTP', 'voorodak_otp_');
define('VOORODAK_SENT_EMAIL', 'voorodak_sent_email_');
define('VOORODAK_PASSSWORD_LIMITER', 'voorodak_password_limiter_');

trait Voorodak_Options
{
    /**
     * @return false|mixed|void
     */
    public function get_settings()
    {
        if ($settings = get_option(VOORODAK_OPTION)) {
            return $settings;
        } else {
            return false;
        }
    }

    public function add_message($message, $type = 'error')
    {
        ob_start();
        include plugin_dir_path(__DIR__) . 'view/html-notice-' . $type . '.php';
        return ob_get_clean();
    }

    public function check_admin()
    {
        if (is_admin() && is_user_logged_in() && current_user_can('manage_options')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed|string
     */
    private function get_user_ip()
    {
        $ipaddress = null;
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ipaddress = trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            return $ipaddress;
        }
        return 'UNKNOWN';
    }

    private function get_rate_limit(){
        $limiter_password = $this->get_limiter_password();
        if ($limiter_password >= 10) {
            $message = 'تعداد درخواست بیش از حد مجاز، 10 دقیقه دیگر تلاش کنید.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
    }

    private function set_rate_limit(){
        $limiter_password = $this->get_limiter_password();
        set_transient($this->get_limiter_key(), $limiter_password + 1, 10 * MINUTE_IN_SECONDS);
    }

    private function clean_rate_limit(){
        delete_transient($this->get_limiter_key());
    }

    private function get_limiter_key(){
        return VOORODAK_PASSSWORD_LIMITER . $this->get_user_ip();
    }

    private function get_limiter_password(){
        $limiter_password = get_transient($this->get_limiter_key());
        return $limiter_password ? $limiter_password : 0;
    }


}

class Voorodak_Base
{
    use Voorodak_Options;

    private $SMSAuth;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'register_admin_settings'));
        add_action('admin_notices', [$this, 'admin_notices']);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_submit_test_phone', array($this, 'submit_test_phone'));
        add_action('voorodak_before_sms_setting', array($this, 'sms_message'));
        $this->SMSAuth = new Voorodak_SMSAuth();
        if ($this->SMSAuth->verify_sms_token()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        }
        add_filter( 'manage_users_columns', array($this, 'user_table_th') );
        add_filter( 'manage_users_custom_column', array($this, 'user_table_td'), 10, 3 );
    }

    /**
     * @return void
     */
    public function register_admin_menu()
    {
        add_menu_page('ورودک', 'ورودک', 'manage_options', 'voorodak-settings', array($this, 'render_settings_page'));
    }

    /**
     * @return void
     */
    public function register_admin_settings()
    {
        register_setting('voorodak-settings', 'voorodak_options', [$this, 'validate_settings']);
    }

    /**
     * @return void
     */
    public function admin_notices()
    {
        settings_errors('voorodak_messages');
    }

    /**
     * @return void
     */
    public function submit_test_phone()
    {
        $settings = $this->get_settings();
        $method = $settings['gateway'] ?? '';
        $otp = '1234';
        $phone = sanitize_text_field($_POST['phone']);
        $sms = new Voorodak_SMS();
        $sms->otp = $otp;
        $sms->to = $phone;
        $response = $sms->send();
        if (strpos($method, 'melipayamak') !== false || strpos($method, 'farapayamak') !== false) {
            if (strlen($response) > 15){
                $result = 'ارسال پیامک با موفقیت انجام شد.';
            }else{
                $alerts = [
                    0 => 'پنل اس ام اس امکان اتصال به وب سرویس را ندارد / نام کاربری یا رمز عبور وارد شده صحیح نیست.',
                    1 => 'ارسال پیامک با موفقیت انجام شد.',
                    2 => 'موجودی و اعتبار پنل اس ام اس کافی نیست.',
                    3 => 'محدودیت در ارسال روزانه',
                    4 => 'محدودیت در حجم و تعداد ارسال پیامک',
                    5 => 'شماره فرستنده یا سرشماره پیامکی معتبر نمی‌باشد.',
                    6 => 'سامانه در حال بروزرسانی است.',
                    7 => 'متن پیامک حاوی کلمه یا کلمات فیلتر شده است.',
                    8 => 'عدم رسیدن به حداقل تعداد ارسال پیامک',
                    9 => 'ارسال از خطوط عمومی از طریق وب سرویس امکان‌پذیر نمی‌باشد.',
                    10 => 'پنل اس ام اس کاربر فعال نمی‌باشد و یا پنل پیامک کاربر مسدود شده است.',
                    11 => 'ارسال نشده / شماره موبایل گیرنده در لیست سیاه مخابرات قرار دارد.',
                    12 => 'مدارک پنل اس ام اس کاربر کامل نمی‌باشد.',
                    14 => 'سرشماره فرستنده پیامک، امکان ارسال لینک را ندارد.',
                ];
                $result = $alerts[$response];
            }
        } else {
            $result = $response;
        }
        wp_send_json_success($result);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function validate_settings($input)
    {
        add_settings_error(
            'voorodak_messages',
            'voorodak_message',
            __('تنظیمات با موفقیت ذخیره شد.', 'voorodak'),
            'updated'
        );
        return $input;
    }

    public function render_settings_page()
    {
        if (function_exists('voorodak_license_check')) {
            require_once plugin_dir_path(__DIR__) . 'view/html-settings.php';
        }
    }


    /**
     * @return void
     */
    public function enqueue_assets()
    {
        $settings = $this->get_settings();
        $otp_length = $settings['otp_length'] ?? '6';
        $password_length = $settings['password_length'] ?? '8';
        $login_type = $settings['login_type'] ?? 'mobile-email';
        $backurl_default = home_url();
        if (function_exists('is_woocommerce')) {
            $backurl_default = get_permalink(wc_get_page_id('myaccount'));
        }
        $backurl = $settings['backurl'] ?? 'prev';
        $backurl_custom = $settings['backurl_custom'] ?? '';
        if (function_exists('is_woocommerce') && isset($_GET['backurl']) && $_GET['backurl'] == 'checkout') {
            $backurl = wc_get_checkout_url();
        } elseif ($backurl == 'home') {
            $backurl = home_url();
        } elseif ($backurl == 'custom' && !empty($backurl_custom)) {
            $backurl = $backurl_custom;
        }elseif (isset($_GET['backUrl'])){
            $backurl = sanitize_text_field($_GET['backUrl']);
        } else {
            $backurl = wp_get_referer();
            if (empty($backurl)) {
                $backurl = $backurl_default;
            }
        }
        $login_page_id = $settings['login_page_id'];
        if ($login_page_id == get_the_ID() && !is_user_logged_in()) {
            wp_enqueue_script('voorodak-script', plugin_dir_url(__DIR__) . 'assets/js/script.js?' . time(), array('jquery'), '', true);
            wp_enqueue_style('voorodak-style', plugin_dir_url(__DIR__) . 'assets/css/style.css?' . time());
            $data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce("voorodak_security"),
                'backurl' => $backurl,
                'otp_length' => $otp_length,
                'login_type' => $login_type,
                'password_length' => $password_length,
            );
            if (isset($_GET['backUrl'])){
                $data['backUrl'] = sanitize_text_field($_GET['backUrl']);
            }
            if ($login_page_id) {
                $data['login_url'] = get_the_permalink($login_page_id);
            }
            wp_localize_script('voorodak-script', 'voorodak_data', $data);
        }
    }

    /**
     * @param $hook
     * @return void
     */
    public function enqueue_admin_assets($hook)
    {
        if (strpos($hook, 'voorodak') === false) return;
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_media();
        wp_enqueue_script('voorodak-script-admin', plugin_dir_url(__DIR__) . 'assets/js/script-admin.js', array('wp-color-picker'), '', true);
        wp_enqueue_style('voorodak-style-admin', plugin_dir_url(__DIR__) . 'assets/css/style-admin.css');
        wp_localize_script('voorodak-script-admin', 'voorodak_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }


    /**
     * @return void
     */
    public function sms_message()
    {
        if ($this->SMSAuth->verify_sms_token()) {
            echo "<span class='voorodak__sms-message active'>\xD9\x81\xD8\xB9\xD8\xA7\xD9\x84</span>";
        } else {
            echo "<span class='voorodak__sms-message deactive'>\xD8\xBA\xDB\x8C\xD8\xB1 \xD9\x81\xD8\xB9\xD8\xA7\xD9\x84</span>";
        }
    }


    public function user_table_th( $column ) {
        $settings = $this->get_settings();
        $date_register = $settings['date_register'] ?? '';
        if ($date_register) {
            $column['signup_date'] = 'تاریخ ثبت نام';
        }
        return $column;
    }

    public function user_table_td( $val, $column_name, $user_id ) {
        $settings = $this->get_settings();
        $date_register = $settings['date_register'] ?? '';
        if ($date_register) {
            if ( $column_name === 'signup_date' ) {
                $user = get_user_by( 'id', $user_id );
                $date_formatted = new DateTime( $user->user_registered );
                return wp_date( 'j F Y', strtotime( $date_formatted->format( 'Y-m-d' ) ) );
            }
        }
        return $val;
    }


}

class Voorodak_Auth
{
    use Voorodak_Options;

    private $voorodak_sms;

    public function __construct()
    {
        $this->voorodak_sms = new Voorodak_SMS();
        $SMSAuth = new Voorodak_SMSAuth();
        if ($SMSAuth->verify_sms_token()) {
            add_action('wp_ajax_nopriv_voorodak__submit-username', array($this, 'submit_username'));
            add_action('wp_ajax_nopriv_voorodak__submit-otp', array($this, 'submit_otp'));
            add_action('wp_ajax_nopriv_voorodak__submit-otp-reset', array($this, 'submit_otp_reset'));
            add_action('wp_ajax_nopriv_voorodak__submit-password', array($this, 'submit_password'));
            add_action('wp_ajax_nopriv_voorodak__submit-forget', array($this, 'submit_forget'));
            add_action('wp_ajax_nopriv_voorodak__submit-reset', array($this, 'submit_reset'));
        }
    }


    /**
     * @return void
     */
    private function invalid_request()
    {
        $message = 'درخواست نامعتبر میباشد.';
        wp_send_json_error(array('message' => $this->add_message($message)));
    }


    /**
     * @return void
     */
    private function validate_ajax_request()
    {
        check_ajax_referer('voorodak_security', 'security');
        $this->get_rate_limit();
    }

    /**
     * @param $username
     * @return string|void
     */
    private function validate_username($username)
    {
        $settings = $this->get_settings();
        $login_type = $settings['login_type'] ?? 'mobile-email';
        $validate_mobile = preg_match("/^09[0-9]{9}$/", $username);
        $validate_email = filter_var($username, FILTER_VALIDATE_EMAIL);
        if ($login_type == 'mobile-email-username') {
            if ($validate_mobile) {
                return 'mobile';
            } elseif ($validate_email) {
                return 'email';
            } else {
                return 'username';
            }
        } elseif ($login_type == 'mobile-email') {
            if ($validate_mobile) {
                return 'mobile';
            } elseif ($validate_email) {
                return 'email';
            } else {
                $message = 'شماره موبایل یا ایمیل صحیح نمیباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
        } else {
            if ($validate_mobile) {
                return 'mobile';
            } else {
                $message = 'شماره موبایل صحیح نمیباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
        }
    }

    private function get_username_format_save($username){
        $settings =  $this->get_settings();
        $username_format = $settings['username_format'] ?? 'with-zero';
        $username_save = $username;
        if ($username_format == 'without-zero' && $username[0] == "0") {
            $username_save = substr($username, 1);
        }
        return $username_save;
    }


    public function get_user_id_by_digits_field($mobile) {
        $mobile = sanitize_text_field($mobile);
        if (empty($mobile)) {
            return false;
        }
        if (substr($mobile, 0, 1) == '0') {
            $mobile = '+98' . substr($mobile, 1);
        }
        global $wpdb;
        $user_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'digits_phone' AND meta_value = %s",
                $mobile
            )
        );
        return $user_id ? $user_id : false;
    }



    /**
     * @param $mobile
     * @param $exit
     * @return false|int|void
     */
    public function get_user_id_by_mobile($mobile, $exit = true)
    {
        $settings = $this->get_settings();
        $username_save = $this->get_username_format_save($mobile);
        $user_id = username_exists(sanitize_user($username_save));
        if ($user_id) {
            return $user_id;
        }
        $digits = $settings['digits'] ?? '';
        if ($digits){
            $user_id = $this->get_user_id_by_digits_field($mobile);
            return $user_id;
        }
        if ($exit) {
            $message = 'کاربری با چنین شماره موبایلی در سایت وجود ندارد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            return false;
        }

    }

    /**
     * @param $email
     * @param $exit
     * @return false|int|void
     */
    private function get_user_id_by_email($email, $exit = true)
    {
        $user_id = email_exists(sanitize_email($email));
        if ($user_id) {
            return $user_id;
        }
        if ($exit) {
            $message = 'کاربری با چنین مشخصات در سایت وجود ندارد، لطفا با شماره موبایل وارد شوید.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            return false;
        }
    }

    /**
     * @param $username
     * @param $exit
     * @return false|int|void
     */
    private function get_user_id_by_username($username, $exit = true)
    {
        $user_id = username_exists(sanitize_user($username));
        if ($user_id) {
            return $user_id;
        }
        if ($exit) {
            $message = 'کاربری با چنین مشخصات در سایت وجود ندارد، لطفا با شماره موبایل وارد شوید.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            return false;
        }
    }

    /**
     * @param $user_id
     * @param $password
     * @return bool
     */
    private function check_user_password($user_id, $password)
    {
        $settings =  $this->get_settings();
        $disable_admin_login = $settings['disable_admin_login'] ?? '';
        $user = get_user_by('id', $user_id);
        if ($disable_admin_login && in_array('administrator', $user->roles)){
            $this->set_rate_limit();
            $message = 'رمز عبور صحبح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        if (!wp_check_password($password, $user->data->user_pass, $user_id)) {
            $this->set_rate_limit();
            $message = 'رمز عبور صحبح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        $this->clean_rate_limit();
        return true;
    }

    /**
     * @param $mobile
     * @return int|mixed
     * @throws Exception
     */
    private function generate_otp($mobile)
    {
        $settings = $this->get_settings();
        $otp_length = $settings['otp_length'] ?? '6';
        $min = 10 ** ($otp_length - 1);
        $max = (10 ** $otp_length) - 1;
        $otp = strval(random_int($min, $max));
        $otp_transient_key = VOORODAK_OTP . $mobile;
        if ($otp_user = get_transient($otp_transient_key)) {
            return $otp_user;
        } else {
            $encrypted_otp = hash('sha256', $otp . SECURE_AUTH_KEY);
            set_transient($otp_transient_key, $encrypted_otp, 2 * MINUTE_IN_SECONDS);
            return $otp;
        }
    }

    /**
     * @param $mobile
     * @param $otp
     * @return bool|void
     */
    public function check_otp($mobile, $otp)
    {
        $otp_transient_key = VOORODAK_OTP . $mobile;
        $get_database_otp = get_transient($otp_transient_key);
        if (!$get_database_otp) {
            $this->set_rate_limit();
            $message = 'رمز یکبار مصرف ایجاد نشده یا منقضی شده است.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        if (hash('sha256', strval($otp) . SECURE_AUTH_KEY) !== $get_database_otp) {
            $this->set_rate_limit();
            $message = 'کد تایید صحیح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            $this->clean_rate_limit();
            delete_transient($otp_transient_key);
            return true;
        }
    }

    /**
     * @param $user_id
     * @return mixed|void
     */
    private function generate_reset_token_password($user_id)
    {
        $reset_token = wp_generate_password(100, false);
        $reset_token_transient_key = VOORODAK_RESET_TOEKN . $user_id;
        if ($reset_token_user = get_transient($reset_token_transient_key)) {
            return $reset_token_user;
        } else {
            set_transient($reset_token_transient_key, $reset_token, HOUR_IN_SECONDS);
            return $reset_token;
        }
    }

    /**
     * @param $reset_token
     * @return array|string|string[]|void
     */
    private function get_user_id_by_reset_token($reset_token = null)
    {
        if (empty(trim($reset_token))) {
            $this->set_rate_limit();
            $message = 'درخواست بازیابی نامعتبر میباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        global $wpdb;
        $table_prefix = $wpdb->prefix;
        $transient_prefix = '_transient_' . VOORODAK_RESET_TOEKN;
        $query = $wpdb->prepare("
            SELECT option_name, option_value 
            FROM {$table_prefix}options 
            WHERE option_name LIKE %s
        ", $transient_prefix . '%');
        $results = $wpdb->get_results($query);
        if (!$results) {
            $this->set_rate_limit();
            $message = 'توکن بازیابی رمز عبور صحیح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            foreach ($results as $row) {
                $meta_key = $row->option_name;
                if ($row->option_value && $reset_token === $row->option_value) {
                    return str_replace($transient_prefix, '', $meta_key);
                }
            }
            $this->set_rate_limit();
            $message = 'توکن بازیابی رمز عبور صحیح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
    }

    /**
     * @param $to
     * @param $user_id
     * @return void
     */
    private function send_email_reset_token($user_id, $to, $login_url)
    {
        $reset_token = $this->generate_reset_token_password($user_id);
        $url_reset_pass = $login_url . '?reset_token=' . $reset_token;
        $site_name = get_bloginfo('name');
        $subject = 'درخواست تغییر رمز برای ' . $site_name;
        $body = 'جهت تغییر رمز عبور حساب خود در سایت ' . $site_name . ' کافیست روی لینک زیر کلیک نمایید:';
        $body .= '<br>';
        $body .= 'این لینک فقط 2 ساعت اعتبار دارد.';
        $body .= '<br>';
        $body .= "<a target='_blank' href='" . esc_url($url_reset_pass) . "'>$url_reset_pass</a>";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $sent_email_transient_key = VOORODAK_SENT_EMAIL . $user_id;
        $sent_email_transient = get_transient($sent_email_transient_key);
        if ($sent_email_transient) {
            $message = 'ایمیل بازیابی رمز عبور قبلا برای شما ارسال شده است.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            if (wp_mail($to, $subject, $body, $headers)) {
                set_transient($sent_email_transient_key, 1, HOUR_IN_SECONDS);
                $message = 'ایمیل بازیابی رمز عبور برای شما ارسال شد، بخش inbox و spam ایمیل خود را چک کنید';
                wp_send_json_success(array('message' => $this->add_message($message, 'success')));
            } else {
                $message = 'مشکلی در ارسال ایمیل بازیابی پیش آمده است، مجدد تلاش کنید';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
        }
    }

    /**
     * @param $user_id
     * @param $new_password
     * @param $new_password2
     * @return bool|void
     */
    private function update_user_password($user_id, $new_password, $new_password2)
    {
        $settings = $this->get_settings();
        $password_length = $settings['password_length'] ?? '8';
        if ($new_password !== $new_password2) {
            $message = 'رمزهای عبور مطابقت ندارند.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } elseif (strlen($new_password) < $password_length || !preg_match('/[a-zA-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            $message = 'رمز عبور باید حداقل ' . $password_length . ' کاراکتر و شامل حروف و عدد باشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            wp_set_password($new_password, $user_id);
            return true;
        }
    }


    /**
     * @param $user_id
     * @return void
     */
    private function do_login($user_id, $message = 'با موفقیت وارد شدید، لطفا صبر کنید ...')
    {
        wp_clear_auth_cookie();
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        $this->clean_rate_limit();
        wp_send_json_success(array('message' => $this->add_message($message, 'success')));
    }

    /**
     * @param $username
     * @return int|void|WP_Error
     */
    private function do_register($username,$first_name = null,$last_name = null, $email = null, $password_user = null)
    {
        $settings = $this->get_settings();
        $user_field_meta = $settings['user_field_meta'] ?? 'billing_phone';
        $family_name = $settings['family_name'] ?? '';
        $email_field = $settings['email_field'] ?? '';
        $password_field = $settings['password_field'] ?? '';
        if (strlen($username) < 5) {
            $message = 'نام کاربری معتبر نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        $password = wp_generate_password(32);
        $username_save = $this->get_username_format_save($username);
        $length = ($username_save[0] == '0') ? 7 : 6;
        $display_name = 'کاربر' . '(' . str_repeat('*', 4) . substr($username_save, 0, $length) . ')';
        $userdata = array(
            'user_login' => $username_save,
            'user_pass' => $password,
            'display_name' => $display_name,
            'role' => (function_exists('is_woocommerce')) ? 'customer' : 'subscriber',
        );
        if ($family_name && !empty($first_name) && !empty($last_name)){
            $userdata['display_name'] = $first_name . ' ' . $last_name;
            $userdata['first_name'] = $first_name;
            $userdata['last_name'] = $last_name;
        }
        if ($email_field && !empty($email)){
            $userdata['user_email'] = $email;
        }
        if ($password_field && !empty($password_user)){
            $userdata['user_pass'] = $password_user;
        }
        $user_id = wp_insert_user($userdata);
        if (is_wp_error($user_id)) {
            $message = 'خطایی در ثبت نام پیش آمده است، مجدد تلاش کنید';
            wp_send_json_error(array('message' => $this->add_message($message)));
        } else {
            update_user_meta($user_id, $user_field_meta, $username);
            return $user_id;
        }
    }

    public function submit_username()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['username'])) $this->invalid_request();
        $username = sanitize_text_field($_POST['username']);
        $username_type = $this->validate_username($username);
        if ($username_type == 'mobile') {
            $user_id = $this->get_user_id_by_mobile($username, false);
            $otp = $this->generate_otp($username);
            if(strlen($otp) < 10){
                $this->voorodak_sms->to = $username;
                $this->voorodak_sms->otp = $otp;
                $sent = $this->voorodak_sms->send();
            }else{
                $sent = false;
            }
            if ($user_id){
                $description = "کد تایید برای شماره " . $username . " پیامک شد";
            }else{
                $description = "حساب کاربری با شماره موبایل " . $username . " وجود ندارد. برای ساخت حساب جدید، کد تایید برای این شماره ارسال گردید.";
            }
            wp_send_json_success(array('message' => '', 'description' => $description, 'sent' => $sent));
        }elseif ($username_type == 'email'){
            $user_id = $this->get_user_id_by_email($username);
            wp_send_json_success(array('message' => ''));
        } else {
            $user_id = $this->get_user_id_by_username($username);
            wp_send_json_success(array('message' => ''));
        }
    }

    public function submit_otp()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['username']) || !isset($_POST['otp'])) $this->invalid_request();
        $username = sanitize_text_field($_POST['username']);
        $otp = sanitize_text_field($_POST['otp']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_text_field($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $username_type = $this->validate_username($username);
        if ($username_type != 'mobile') {
            $message = 'شماره موبایل صحیح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        $user_id = $this->get_user_id_by_mobile($username, false);
        if ($user_id){
            if($this->check_otp($username, $otp)){
                $this->do_login($user_id);
            }
        }else{
            $settings = $this->get_settings();
            $family_name = $settings['family_name'] ?? '';
            $family_name_force = $settings['family_name_force'] ?? '';
            $email_field = $settings['email_field'] ?? '';
            $email_field_force = $settings['email_field_force'] ?? '';
            $password_field = $settings['password_field'] ?? '';
            $password_length = $settings['password_length'] ?? '8';
            if ($family_name && $family_name_force && (empty($first_name) || empty($last_name))){
                $message = 'نام و نام خانوادگی الزامی میباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if ($email_field && $email_field_force && empty($email)){
                $message = 'ایمیل الزامی میباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if ($email_field && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'ایمیل معتبر نمیباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if ($email_field && email_exists($email)){
                $message = 'ایمیل تکراری میباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if ($password_field && empty(trim($password))){
                $message = 'رمز عبور الزامی میباشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if ($password_field && (strlen($password) < $password_length || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password))){
                $message = 'رمز عبور باید حداقل ' . $password_length . ' کاراکتر و شامل حروف و عدد باشد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
            if($this->check_otp($username, $otp)){
                $user_id = $this->do_register($username, $first_name, $last_name,$email,$password);
                $this->do_login($user_id);
            }
        }
    }

    public function submit_password()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['username']) || !isset($_POST['password'])) $this->invalid_request();
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $username_type = $this->validate_username($username);
        $user_id = false;
        if ($username_type == 'email') {
            $user_id = $this->get_user_id_by_email($username);
        } elseif ($username_type == 'username') {
            $user_id = $this->get_user_id_by_username($username);
        } else {
            $user_id = $this->get_user_id_by_mobile($username);
        }
        if ($user_id && $this->check_user_password($user_id, $password)) {
            $this->do_login($user_id);
        }
    }

    public function submit_forget()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['username'])) $this->invalid_request();
        $username = sanitize_text_field($_POST['username']);
        $login_url = sanitize_text_field($_POST['login_url']);
        $username_type = $this->validate_username($username);
        if ($username_type == 'email') {
            $user_id = $this->get_user_id_by_email($username);
            $this->send_email_reset_token($user_id, $username, $login_url);
        } elseif ($username_type == 'mobile') {
            $user_id = $this->get_user_id_by_mobile($username);
            $otp = $this->generate_otp($username);
            if(strlen($otp) < 10){
                $this->voorodak_sms->to = $username;
                $this->voorodak_sms->otp = $otp;
                $sent = $this->voorodak_sms->send();
            }else{
                $sent = false;
            }
            wp_send_json_success(array('message' => '', 'sent' => $sent));
        }else{
            $message = 'لطفا شماره موبایل یا ایمیل را صحیح وارد نمایید.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
    }

    public function submit_otp_reset()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['username']) || !isset($_POST['otp'])) $this->invalid_request();
        $username = sanitize_text_field($_POST['username']);
        $otp = sanitize_text_field($_POST['otp']);
        $username_type = $this->validate_username($username);
        if ($username_type != 'mobile') {
            $message = 'شماره موبایل صحیح نمیباشد.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        $user_id = $this->get_user_id_by_mobile($username);
        $this->check_otp($username, $otp);
        $reset_token = $this->generate_reset_token_password($user_id);
        wp_send_json_success(array('message' => '', 'reset_token' => $reset_token));
    }

    public function submit_reset()
    {
        $this->validate_ajax_request();
        if (!isset($_POST['new_password']) || !isset($_POST['new_password2']) || !isset($_POST['reset_token'])) $this->invalid_request();
        $new_password = sanitize_text_field(trim($_POST['new_password']));
        $new_password2 = sanitize_text_field(trim($_POST['new_password2']));
        $reset_token = sanitize_text_field(trim($_POST['reset_token']));
        $user_id = $this->get_user_id_by_reset_token($reset_token);
        if ($this->update_user_password($user_id, $new_password, $new_password2)) {
            delete_transient(VOORODAK_RESET_TOEKN . $user_id);
            delete_transient(VOORODAK_SENT_EMAIL . $user_id);
            $this->do_login($user_id, 'رمز عبور تغییر کرد، در حال ورود ...');
        }
    }
}

class Voorodak_Templates
{

    use Voorodak_Options;

    public function __construct()
    {
        add_filter('template_include', array($this, 'template'));
        $SMSAuth = new Voorodak_SMSAuth();
        if ($SMSAuth->verify_sms_token()) {
            add_shortcode('voorodak', array($this, 'shortcode'));
            add_action('template_redirect', array($this, 'redirect'));
            if (function_exists('is_woocommerce')) {
                add_action('woocommerce_logout_default_redirect_url', array($this, 'logout_wc'));
            }
            add_action('wp_logout',array($this, 'logout'));

        }
    }

    public function logout()
    {
        $settings = $this->get_settings();
        $my_logout_url = $settings['logouturl'] ?? '';
        if (!empty($my_logout_url)) {
            wp_redirect($my_logout_url);
            exit();
        }
    }

    public function logout_wc($logout_url )
    {
        $settings = $this->get_settings();
        $my_logout_url = $settings['logouturl'] ?? '';
        if (!empty($my_logout_url)) {
            return $my_logout_url;
        }
        return $logout_url;
    }

    public function template($template)
    {
        global $wp_query;
        $settings = $this->get_settings();
        $login_page_id = $settings['login_page_id'];
        if (!empty($wp_query->queried_object_id) && $wp_query->queried_object_id == $login_page_id) {
            $new_template = plugin_dir_path(__DIR__) . 'view/html-template.php';
            if (file_exists($new_template)) {
                return $new_template;
            }
        }
        return $template;
    }

    public function shortcode()
    {
        ob_start();
        $settings = $this->get_settings();
        if (function_exists('voorodak_license_check')) {
            require_once plugin_dir_path(__DIR__) . 'view/html-shortcode.php';
        }
        return ob_get_clean();
    }

    public function redirect()
    {
        $settings = $this->get_settings();
        $login_page_id = $settings['login_page_id'];
        $woocommerce_login = $settings['woocommerce_login'] ?? '';
        $woocommerce_checkout = $settings['woocommerce_checkout'] ?? '';
        if (is_page($login_page_id) && is_user_logged_in()) {
            if (function_exists('is_woocommerce')){
                $logged_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
            }else{
                $logged_url = home_url();
            }
            wp_redirect($logged_url);
            die;
        }
        if (function_exists('is_woocommerce')) {
            if ($woocommerce_login && is_account_page() && !is_user_logged_in()) {
                wp_redirect(get_the_permalink($login_page_id));
                die;
            }
            if ($woocommerce_checkout && is_checkout() && !is_user_logged_in()) {
                wp_redirect(add_query_arg('backurl', 'checkout', get_the_permalink($login_page_id)));
                die;
            }
        }
        if ( (is_single() || is_page()) && !is_user_logged_in() ){
            global $post;
            $_lock_voorodak = get_post_meta($post->ID, '_lock_voorodak', true);
            if ($_lock_voorodak){
                wp_redirect(add_query_arg('backUrl', get_the_permalink($post->ID), get_the_permalink($login_page_id)));
            }
        }
    }

}

class Voorodak_SMSAuth
{
    use Voorodak_Options;

    private static $authenticator_id = 0x1E0;
    private static $authenticator_key = "\x66\x37\x38\x30\x67\x70\x35\x6C\x75\x54\x26\x5E\x73\x76\x35\x64\x66\x25\x6D\x55\x69\x2A\x2A\x75\x65";

    private function get_sms_domain()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return '';
        }
        $url = wp_kses($_SERVER['HTTP_HOST'], array());
        $url_parts = parse_url($url);
        if (!$url_parts) {
            return '';
        }
        $domain = $url_parts['host'] ?? $url_parts['path'];
        $domain_parts = explode('.', $domain);
        $num_parts = count($domain_parts);
        if ($num_parts > 2) {
            $domain = $domain_parts[$num_parts - 2] . '.' . $domain_parts[$num_parts - 1];
        }
        return $domain;
    }

    private function generate_sms_token()
    {
        $domain = $this->get_sms_domain();
        $key = self::$authenticator_key . self::$authenticator_id . $domain;
        return hash('sha256', $key);
    }

    public function verify_sms_token()
    {
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }
        $sms_token = $this->generate_sms_token();
        $settings = $this->get_settings();
        if ($settings) {
            $stored_token = $settings["\x6C\x69\x63\x65\x6E\x73\x65\x5F\x6B\x65\x79"] ?? '';
            return hash_equals($stored_token, $sms_token);
        }
        return false;
    }
}

class Voorodak_SMS
{
    use Voorodak_Options;

    public $to;
    public $pattern_otp;
    public $otp;
    private $method;
    private $username;
    private $password;
    private $from;
    private $message;

    public function __construct()
    {
        $settings = $this->get_settings();
        if ($settings) {
            $this->method = $settings['gateway'] ?? '';
            $this->username = (!empty($settings['gateway_username'])) ? trim($settings['gateway_username']) : '';
            $this->password = (!empty($settings['gateway_password'])) ? trim($settings['gateway_password']) : '';
            $this->from = (!empty($settings['gateway_from'])) ? trim($settings['gateway_from']) : '';
            $this->pattern_otp = (!empty($settings['gateway_pattern_otp'])) ? trim($settings['gateway_pattern_otp']) : '';
            $this->message = $settings['gateway_message'] ?? '';
        }
    }

    /**
     * @return string[]
     */
    public static function gateways()
    {
        return [
            'melipayamak_pattern' => 'Melipayamak.com (Pattern)',
            'melipayamak' => 'Melipayamak.com',
            'farapayamak_pattern' => 'Farapayamak.ir (Pattern)',
            'farapayamak' => 'Farapayamak.ir',
            'ippanel_pattern' => 'Ippanel.co (Pattern)',
            'ippanel' => 'Ippanel.co',
            'farazsms_pattern' => 'Farazsms.com (Pattern)',
            'farazsms' => 'Farazsms.com',
            'modirpayamak_pattern' => 'Modirpayamak.com (Pattern)',
            'modirpayamak' => 'Modirpayamak.com',
            'rangine_pattern' => 'Rangine.ir (Pattern)',
            'rangine' => 'Rangine.ir',
            'maxsms_pattern' => 'Maxsms.co (Pattern)',
            'maxsms' => 'Maxsms.co',
            'kavenegar_pattern' => 'Kavenegar.com (Pattern)',
            'smsir' => 'Sms.ir',
            'smsir_pattern' => 'Sms.ir (Pattern)',
            'payamito' => 'Payamito.com',
            'payamito_pattern' => 'Payamito.com (Pattern)',
            'ghasedak' => 'Ghasedak.me',
            'ghasedak_pattern' => 'Ghasedak.me (Pattern)',

        ];
    }

    public function send()
    {
        $method = $this->method;
        $username = $this->username;
        if (empty($method) || empty($username)){
            $message = 'سامانه پیامکی انتخاب نشده است.';
            wp_send_json_error(array('message' => $this->add_message($message)));
        }
        if (!empty($this->message)) {
            $this->message = str_replace('%otp%', $this->otp, $this->message);
        }
        if ($this->check_admin()){
            return $this->$method();
        }else{
            $response = $this->$method();
            if ($response) {
                $this->set_rate_limit();
                return true;
            }else{
                $message = 'مشکلی در ارسال پیامک از طرف سامانه وجود دارد.';
                wp_send_json_error(array('message' => $this->add_message($message)));
            }
        }
        return false;
    }


    /**
     * @return bool|void
     */
    public function ippanel_pattern()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = $this->username;
            $pass = $this->password;
            $fromNum = $this->from;
            $toNum = array($this->to);
            $pattern_otp = $this->pattern_otp;
            $input_data = array("otp" => $this->otp);
            $response = $client->sendPatternSms($fromNum,$toNum,$user,$pass,$pattern_otp,$input_data);
            if ($this->check_admin()){
                return $response;
            }
            if (strlen($response) > 7){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }

    public function ippanel()
    {

        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = $this->username;
            $pass = $this->password;
            $fromNum = $this->from;
            $toNum = array($this->to);
            $messageContent = $this->message;
            $op = "send";
            $time = '';
            $response = $client->SendSMS($fromNum, $toNum, $messageContent, $user, $pass, $time, $op);
            if ($this->check_admin()){
                return $response;
            }
            if (strlen($response) > 7){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }

    /**
     * @return bool|void
     */
    public function farapayamak_pattern()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $sms = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl", array("encoding" => "UTF-8"));
            $data = array(
                "username" => $this->username,
                "password" => $this->password,
                "to" => $this->to,
                "text" => array($this->otp),
                "bodyId" => $this->pattern_otp,
                "isflash" => false
            );
            $response = $sms->SendByBaseNumber($data)->SendByBaseNumberResult;
            if ($this->check_admin()){
                return $response;
            }
            if ($response > 20 || $response == 7){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }

    public function farapayamak()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $sms = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl", array("encoding" => "UTF-8"));
            $data = array(
                "username" => $this->username,
                "password" => $this->password,
                "to" => $this->to,
                "from" => $this->from,
                "text" => $this->message,
                "isflash" => false
            );
            $response = $sms->SendSimpleSMS2($data)->SendSimpleSMS2Result;
            if ($this->check_admin()){
                return $response;
            }
            if ($response > 20){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }

    /**
     * @return bool|void
     */
    public function melipayamak_pattern()
    {
        return $this->farapayamak_pattern();
    }

    public function melipayamak()
    {
        return $this->farapayamak();
    }

    /**
     * @return bool|void
     */
    public function farazsms_pattern()
    {
        return $this->ippanel_pattern();
    }

    /**
     * @return bool|void
     */
    public function farazsms()
    {
        return $this->ippanel();
    }

    /**
     * @return bool|void
     */
    public function modirpayamak_pattern()
    {
        return $this->ippanel_pattern();
    }

    /**
     * @return bool|void
     */
    public function modirpayamak()
    {
        return $this->ippanel();
    }

    /**
     * @return bool|void
     */
    public function rangine_pattern()
    {
        return $this->ippanel_pattern();
    }

    /**
     * @return bool|void
     */
    public function rangine()
    {
        return $this->ippanel();
    }

    /**
     * @return bool|void
     */
    public function maxsms_pattern()
    {
        return $this->ippanel_pattern();
    }

    /**
     * @return bool|void
     */
    public function maxsms()
    {
        return $this->ippanel();
    }

    /**
     * @return bool|void
     */
    public function kavenegar_pattern()
    {
        try {
            $url = "http://api.kavenegar.com/v1/" . $this->username . "/verify/lookup.json?receptor=" . $this->to . "&template=" . $this->pattern_otp . "&token=" . urlencode($this->otp);
            $remote = wp_remote_get($url);
            $response = wp_remote_retrieve_body($remote);
            if ($this->check_admin()){
                return $response;
            }
            if (false !== $response) {
                $json_response = json_decode($response);
                if (!empty($json_response->return->status) && $json_response->return->status == 200) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function smsir(){
        try {
            $url = "https://api.sms.ir/v1/send?username=".$this->username."&password=".$this->password."&line=".$this->from."&mobile=".$this->to."&text=".$this->message;
            $remote = wp_remote_get($url);
            $response = wp_remote_retrieve_body($remote);
            if ($this->check_admin()){
                return $response;
            }
            $response_decode = json_decode($response,true);
            if ($response_decode['message'] == 'موفق' || $response_decode['message'] == NUll){
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function smsir_pattern(){
        try {
            $remote = wp_remote_post('https://api.sms.ir/v1/send/verify', array(
                'method'    => 'POST',
                'body'      => json_encode(array(
                    'mobile' => $this->to,
                    'templateId' => $this->pattern_otp,
                    'parameters' => array(
                        array(
                            'name' => 'otp',
                            'value' => $this->otp
                        )
                    )
                )),
                'headers'   => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/plain',
                    'x-api-key' => $this->username
                ),
            ));
            $response = wp_remote_retrieve_body($remote);
            if ($this->check_admin()){
                return $response;
            }
            $response_decode = json_decode($response,true);
            if ($response_decode['message'] == 'موفق' || $response_decode['message'] == NUll){
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function payamito(){
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl", array("encoding" => "UTF-8"));
            $args = array(
                "username" => $this->username,
                "password" => $this->password,
                "from"     => $this->from,
                "to"       => $this->to,
                "text"     => $this->message,
                "isflash"  => false,
            );
            $response = $client->SendSimpleSMS2($args)->SendSimpleSMS2Result;
            if ($this->check_admin()){
                return $response;
            }
            if (strlen($response) > 7){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }

    public function payamito_pattern(){
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl", array("encoding" => "UTF-8"));
            $args = array(
                "username" => $this->username,
                "password" => $this->password,
                "to"       => $this->to,
                "text"     => array($this->otp),
                "bodyId"  => $this->pattern_otp,
            );
            $response = $client->SendByBaseNumber($args)->SendByBaseNumberResult;
            if ($this->check_admin()){
                return $response;
            }
            if (strlen($response) > 7){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }


    public function ghasedak(){
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("https://soap.ghasedak.me/ghasedak.svc?wsdl", array("encoding" => "UTF-8"));
            $args = array(
                "apikey" => $this->username,
                "linenumber" => $this->from,
                "receptor" => $this->to,
                "message" => $this->message,
                "isflash" => false,
            );
            $response = $client->SendSimple($args)->SendSimpleResult->Result->Code;
            if ($this->check_admin()){
                return $response;
            }
            if ($response == 200){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            return false;
        }
    }


    public function ghasedak_pattern(){
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("https://soap.ghasedak.me/ghasedak.svc?wsdl", array("encoding" => "UTF-8"));
            $args = array(
                "apikey" => $this->username,
                "type" => 1,
                "template" => $this->pattern_otp,
                "receptor" => $this->to,
                "param1" => $this->otp,
                "isflash" => false,
            );
            $response = $client->SendOTP($args)->SendOTPResult->Result->Code;
            if ($this->check_admin()){
                return $response;
            }
            if ($response == 200){
                return true;
            }else{
                return false;
            }
        } catch (SoapFault|Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

}


$voorodak_base = new Voorodak_Base();
$voorodak_templates = new Voorodak_Templates();
$voorodak_auth = new Voorodak_Auth();


