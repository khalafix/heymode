<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
$settings = get_option(VOORODAK_OPTION);
$gateway = $settings['gateway'] ?? 'melipayamak_pattern';
$gateway_username = $settings['gateway_username'] ?? '';
$gateway_password = $settings['gateway_password'] ?? '';
$gateway_from = $settings['gateway_from'] ?? '';
$gateway_pattern_otp = $settings['gateway_pattern_otp'] ?? '';
$gateway_message = $settings['gateway_message'] ?? '';
$template = $settings['template'] ?? 'default';
$logo = $settings['logo'] ?? '';
$cover = $settings['cover'] ?? '';
$bg_color = $settings['bg_color'] ?? '#ffffff';
$button_color = $settings['button_color'] ?? '#5498fa';
$button_color_hover = $settings['button_color_hover'] ?? '#2c61a6';
$login_page_id = $settings['login_page_id'] ?? '';
$backurl = $settings['backurl'] ?? 'prev';
$backurl_custom = $settings['backurl_custom'] ?? '';
$logouturl = $settings['logouturl'] ?? '';
$woocommerce_login = $settings['woocommerce_login'] ?? '';
$woocommerce_checkout = $settings['woocommerce_checkout'] ?? '';
$digits = $settings['digits'] ?? '';
$date_register = $settings['date_register'] ?? '';
$family_name = $settings['family_name'] ?? '';
$email_field = $settings['email_field'] ?? '';
$password_field = $settings['password_field'] ?? '';
$family_name_force = $settings['family_name_force'] ?? '';
$email_field_force = $settings['email_field_force'] ?? '';
$disable_admin_login = $settings['disable_admin_login'] ?? '';
$username_format = $settings['username_format'] ?? 'with-zero';
$user_field_meta = $settings['user_field_meta'] ?? 'billing_phone';
$otp_length = $settings['otp_length'] ?? '6';
$password_length = $settings['password_length'] ?? '8';
$login_type = $settings['login_type'] ?? 'mobile-email';
$form_name = $settings['form_name'] ?? 'ورود / ثبت نام';
$term_editor = $settings['term_editor'] ?? '';
$license_key = $settings['license_key'] ?? '';
?>
<div class="wrap voorodak">
    <h1></h1>
    <h2>تنظیمات ورودک</h2>
    <div class="voorodak__body">
        <div class="voorodak__body-tab">
            <a href="#gateway" class="active">
                <svg width="24px"  height="24px"  viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Iconly/Bulk/Message" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Group" transform="translate(1.999900, 2.999600)" fill="currentColor"  fill-rule="nonzero">
                            <path d="M20,12.9406 C20,15.7306 17.76,17.9906 14.97,18.0006 L14.96,18.0006 L5.05,18.0006 C2.27,18.0006 0,15.7506 0,12.9606 L0,12.9506 C0,12.9506 0.006,8.5246 0.014,6.2986 C0.015,5.8806 0.495,5.6466 0.822,5.9066 C3.198,7.7916 7.447,11.2286 7.5,11.2736 C8.21,11.8426 9.11,12.1636 10.03,12.1636 C10.95,12.1636 11.85,11.8426 12.56,11.2626 C12.613,11.2276 16.767,7.8936 19.179,5.9776 C19.507,5.7166 19.989,5.9506 19.99,6.3676 C20,8.5766 20,12.9406 20,12.9406" id="Fill-1" opacity="0.400000006"></path>
                            <path d="M19.4761,2.674 C18.6101,1.042 16.9061,3.55271368e-15 15.0301,3.55271368e-15 L5.0501,3.55271368e-15 C3.1741,3.55271368e-15 1.4701,1.042 0.6041,2.674 C0.4101,3.039 0.5021,3.494 0.8251,3.752 L8.2501,9.691 C8.7701,10.111 9.4001,10.32 10.0301,10.32 C10.0341,10.32 10.0371,10.32 10.0401,10.32 C10.0431,10.32 10.0471,10.32 10.0501,10.32 C10.6801,10.32 11.3101,10.111 11.8301,9.691 L19.2551,3.752 C19.5781,3.494 19.6701,3.039 19.4761,2.674" id="Fill-4"></path>
                        </g>
                    </g>
                </svg>
                سامانه پیامکی</a>
            <a href="#performance">
                <svg width="24px"  height="24px"  viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Iconly/Bulk/Setting" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Setting" transform="translate(2.499897, 2.000100)" fill="currentColor"  fill-rule="nonzero">
                            <path d="M9.51207539,12.83 C7.9076023,12.83 6.60971643,11.58 6.60971643,10.01 C6.60971643,8.44 7.9076023,7.18 9.51207539,7.18 C11.1165485,7.18 12.3837756,8.44 12.3837756,10.01 C12.3837756,11.58 11.1165485,12.83 9.51207539,12.83" id="Path"></path>
                            <path d="M18.730131,12.37 C18.5359591,12.07 18.2600306,11.77 17.9023455,11.58 C17.6161974,11.44 17.4322451,11.21 17.2687319,10.94 C16.7475337,10.08 17.0541209,8.95 17.9227847,8.44 C18.944742,7.87 19.2717684,6.6 18.6790331,5.61 L17.9943217,4.43 C17.411806,3.44 16.1343592,3.09 15.1226214,3.67 C14.2232989,4.15 13.0684871,3.83 12.5472888,2.98 C12.3837756,2.7 12.2917995,2.4 12.3122386,2.1 C12.3428973,1.71 12.2202625,1.34 12.0363101,1.04 C11.6581859,0.42 10.9734745,0 10.217226,0 L8.77626608,0 C8.03023719,0.02 7.34552574,0.42 6.96740151,1.04 C6.77322961,1.34 6.6608143,1.71 6.68125344,2.1 C6.70169259,2.4 6.60971643,2.7 6.44620325,2.98 C5.92500498,3.83 4.77019314,4.15 3.88109021,3.67 C2.85913283,3.09 1.59190568,3.44 0.999170395,4.43 L0.314458948,5.61 C-0.26805676,6.6 0.0589696023,7.87 1.07070741,8.44 C1.93937119,8.95 2.2459584,10.08 1.73497971,10.94 C1.56124696,11.21 1.37729463,11.44 1.09114656,11.58 C0.743681049,11.77 0.437093834,12.07 0.273580653,12.37 C-0.104543579,12.99 -0.0841044313,13.77 0.2940198,14.42 L0.999170395,15.62 C1.37729463,16.26 2.08244522,16.66 2.81825454,16.66 C3.16572005,16.66 3.574503,16.56 3.90152936,16.36 C4.15701871,16.19 4.46360592,16.13 4.80085186,16.13 C5.81258967,16.13 6.6608143,16.96 6.68125344,17.95 C6.68125344,19.1 7.62145424,20 8.8069248,20 L10.1967868,20 C11.3720378,20 12.3122386,19.1 12.3122386,17.95 C12.3428973,16.96 13.191122,16.13 14.2028598,16.13 C14.5298861,16.13 14.8364734,16.19 15.1021823,16.36 C15.4292086,16.56 15.827772,16.66 16.1854571,16.66 C16.9110468,16.66 17.6161974,16.26 17.9943217,15.62 L18.7096918,14.42 C19.0775965,13.75 19.1082552,12.99 18.730131,12.37" id="Path" opacity="0.400000006"></path>
                        </g>
                    </g>
                </svg>
                عملکرد
            </a>
            <a href="#display">
                <svg width="24px"  height="24px"  viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Iconly/Bulk/Image" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Image" transform="translate(2.000000, 2.000000)" fill="currentColor"  fill-rule="nonzero">
                            <path d="M14.3328156,20 L5.66618229,20 C2.27689532,20 0,17.6228892 0,14.0842812 L0,5.91672095 C0,2.37811294 2.27689532,0 5.66618229,0 L14.3338177,0 C17.7231047,0 20,2.37811294 20,5.91672095 L20,14.0842812 C20,17.6228892 17.7231047,20 14.3328156,20" id="Fill-1" opacity="0.400000006"></path>
                            <path d="M13.4284,11.0896 C13.6504,10.7986 14.4744,9.8886 15.5394,10.5366 C16.2184,10.9446 16.7894,11.4966 17.4004,12.0876 C17.6334,12.3136 17.8004,12.5716 17.9104,12.8466 C18.2434,13.6786 18.0704,14.6786 17.7144,15.5026 C17.2924,16.4836 16.4844,17.2246 15.4664,17.5486 C15.0144,17.6936 14.5404,17.7556 14.0674,17.7556 L14.0674,17.7556 L5.6864,17.7556 C4.8524,17.7556 4.1144,17.5616 3.5094,17.1976 C3.1304,16.9696 3.0634,16.4446 3.3444,16.1026 C3.8144,15.5326 4.2784,14.9606 4.7464,14.3836 C5.6384,13.2796 6.2394,12.9596 6.9074,13.2406 C7.1784,13.3566 7.4504,13.5316 7.7304,13.7156 C8.4764,14.2096 9.5134,14.8876 10.8794,14.1516 C11.8194,13.6376 12.3624,12.7556 12.8364,11.9916 C13.0304,11.6806 13.2144,11.3706 13.4284,11.0896 Z M6.76,4.189 C8.13,4.189 9.245,5.305 9.245,6.675 C9.245,8.045 8.13,9.16 6.76,9.16 C5.389,9.16 4.275,8.045 4.275,6.675 C4.275,5.305 5.389,4.189 6.76,4.189 Z" id="Combined-Shape"></path>
                        </g>
                    </g>
                </svg>
                ظاهری

            </a>
            <a href="#advance">
                <svg width="24px"  height="24px"  viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(2.000000, 3.000000)" fill="currentColor" fill-rule="nonzero">
                            <path d="M8.08328843,12.9579529 L1.5077694,12.9579529 C0.675551802,12.9579529 5.23038403e-14,13.6216572 5.23038403e-14,14.4392797 C5.23038403e-14,15.2558107 0.675551802,15.9206066 1.5077694,15.9206066 L8.08328843,15.9206066 C8.91550602,15.9206066 9.59105783,15.2558107 9.59105783,14.4392797 C9.59105783,13.6216572 8.91550602,12.9579529 8.08328843,12.9579529" id="Fill-1" opacity="0.400000006"></path>
                            <path d="M20,3.37856047 C20,2.56202954 19.3244482,1.89832525 18.4933417,1.89832525 L11.9178227,1.89832525 C11.0856051,1.89832525 10.4100533,2.56202954 10.4100533,3.37856047 C10.4100533,4.19618302 11.0856051,4.8598873 11.9178227,4.8598873 L18.4933417,4.8598873 C19.3244482,4.8598873 20,4.19618302 20,3.37856047" id="Fill-4" opacity="0.400000006"></path>
                            <path d="M6.87773957,3.37856047 C6.87773957,5.24522877 5.33885923,6.75821256 3.43886978,6.75821256 C1.53999144,6.75821256 4.39154885e-14,5.24522877 4.39154885e-14,3.37856047 C4.39154885e-14,1.51298378 1.53999144,-2.51650552e-14 3.43886978,-2.51650552e-14 C5.33885923,-2.51650552e-14 6.87773957,1.51298378 6.87773957,3.37856047" id="Fill-6"></path>
                            <path d="M20,14.3992173 C20,16.264794 18.4611197,17.7777778 16.5611302,17.7777778 C14.6622519,17.7777778 13.1222604,16.264794 13.1222604,14.3992173 C13.1222604,12.532549 14.6622519,11.0195652 16.5611302,11.0195652 C18.4611197,11.0195652 20,12.532549 20,14.3992173" id="Fill-9"></path>
                        </g>
                    </g>
                </svg>
                پیشرفته
            </a>
            <a href="#license">
                <svg width="24px"  height="24px"  viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Iconly/Bulk/Password" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Password" transform="translate(2.000400, 1.999800)" fill="currentColor"  fill-rule="nonzero">
                            <path d="M14.334,0 L5.665,0 C2.276,0 0,2.378 0,5.917 L0,14.084 C0,17.622 2.276,20 5.665,20 L14.333,20 C17.722,20 20,17.622 20,14.084 L20,5.917 C20,2.378 17.723,0 14.334,0" id="Fill-1" opacity="0.400000006"></path>
                            <path d="M6.8438,7.3987 C8.0138,7.3987 8.9938,8.1787 9.3138,9.2487 L9.3138,9.2487 L15.0138,9.2487 C15.4238,9.2487 15.7638,9.5887 15.7638,9.9987 L15.7638,9.9987 L15.7638,11.8487 C15.7638,12.2687 15.4238,12.5987 15.0138,12.5987 C14.5938,12.5987 14.2638,12.2687 14.2638,11.8487 L14.2638,11.8487 L14.2638,10.7487 L12.9338,10.7487 L12.9338,11.8487 C12.9338,12.2687 12.5938,12.5987 12.1838,12.5987 C11.7638,12.5987 11.4338,12.2687 11.4338,11.8487 L11.4338,11.8487 L11.4338,10.7487 L9.3138,10.7487 C8.9938,11.8187 8.0138,12.5987 6.8438,12.5987 C5.4038,12.5987 4.2338,11.4387 4.2338,9.9987 C4.2338,8.5687 5.4038,7.3987 6.8438,7.3987 Z M6.8438,8.8987 C6.2338,8.8987 5.7338,9.3887 5.7338,9.9987 C5.7338,10.6087 6.2338,11.0987 6.8438,11.0987 C7.4438,11.0987 7.9438,10.6087 7.9438,9.9987 C7.9438,9.3887 7.4438,8.8987 6.8438,8.8987 Z" id="Combined-Shape"></path>
                        </g>
                    </g>
                </svg>
                لایسنس
            </a>
        </div>
        <div class="voorodak__body-main">
            <form method="post" action="options.php" autocomplete="off">
                <?php settings_fields('voorodak-settings'); ?>
                <?php do_settings_sections('voorodak-settings'); ?>
                <div id="gateway" class="voorodak__body-main-box">
                    <table class="form-table">
                        <tr class="voorodak__gateway">
                            <th>سامانه پیامکی</th>
                            <td>
                                <select name="voorodak_options[gateway]">
                                    <?php
                                    $gateways = Voorodak_SMS::gateways();
                                    foreach ($gateways as $gateway_value => $gateway_name):
                                        $gateway_value_safe = esc_attr($gateway_value);
                                        $gateway_name_safe = esc_html($gateway_name);
                                        ?>
                                        <option value="<?php echo $gateway_value_safe; ?>" <?php selected($gateway, $gateway_value_safe); ?>><?php echo $gateway_name_safe; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="voorodak__username">
                            <th>نام کاربری سامانه</th>
                            <td><input type="text" name="voorodak_options[gateway_username]"
                                       value="<?php echo esc_attr($gateway_username); ?>"/></td>
                        </tr>
                        <tr class="voorodak__password">
                            <th>رمز عبور سامانه</th>
                            <td><input type="text" name="voorodak_options[gateway_password]"
                                       value="<?php echo esc_attr($gateway_password); ?>"/></td>
                        </tr>
                        <tr class="voorodak__from">
                            <th>خط ارسال کننده</th>
                            <td><input type="text" name="voorodak_options[gateway_from]"
                                       value="<?php echo esc_attr($gateway_from); ?>"/></td>
                        </tr>
                        <tr class="voorodak__pattern">
                            <th>
                                کد الگو (پترن)
                                <span class="hint">متغیر فراخوانی کد : otp</span>
                            </th>
                            <td><input type="text" name="voorodak_options[gateway_pattern_otp]"
                                       value="<?php echo esc_attr($gateway_pattern_otp); ?>"/></td>
                        </tr>
                        <tr class="voorodak__message">
                            <th>متن پیامک
                            <span class="hint">متغیر کد تایید در پیام: %otp%</span>
                            </th>
                            <td><textarea rows="10" placeholder="کد تایید شما: %otp%" name="voorodak_options[gateway_message]"><?php echo esc_attr($gateway_message); ?></textarea></td></td>
                        </tr>
                        <tr>
                            <th><h3>تست ارسال پیامک</h3>
                            <span class="hint">ابتدا اطلاعات پیامکی را وارد نمایید و ذخیره کنید سپس تست بگیرید.</span></th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>
                                <input type="text" id="test_phone_number" placeholder="شماره موبایل شما">
                            </th>
                            <td>
                                <span id="test_phone_submit" class="button">ارسال پیامک</span>
                            </td>
                        </tr>
                        <tr id="test_phone_result" style="display: none">
                            <th>پاسخ</th>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div id="performance" class="voorodak__body-main-box" style="display: none">
                    <table class="form-table">
                        <tr>
                            <th>صفحه ورود / ثبت نام</th>
                            <td>
                                <?php
                                $pages = get_pages();
                                ?>
                                <select name="voorodak_options[login_page_id]">
                                    <?php foreach ($pages as $page) : ?>
                                        <option value="<?php echo $page->ID; ?>" <?php selected($login_page_id, $page->ID); ?>><?php echo $page->post_title; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="voorodak__backurl">
                            <th>صفحه بعد از لاگین کاربر</th>
                            <td>
                                <label><input type="radio" name="voorodak_options[backurl]" value="prev" <?php checked($backurl, 'prev'); ?> />صفحه قبلی</label>
                                <label><input type="radio" name="voorodak_options[backurl]" value="home" <?php checked($backurl, 'home'); ?> />صفحه اصلی</label>
                                <label><input type="radio" name="voorodak_options[backurl]" value="custom" <?php checked($backurl, 'custom'); ?> />صفحه دلخواه</label>
                            </td>
                        </tr>
                        <tr class="voorodak__backurl-custom">
                            <th>لینک صفحه دلخواه</th>
                            <td><input placeholder="https:// لینک به صورت " type="text" name="voorodak_options[backurl_custom]"
                                       value="<?php echo esc_attr($backurl_custom); ?>"/></td>
                        </tr>
                        <tr class="voorodak__logouturl">
                            <th>لینک صفحه بعد از خروج</th>
                            <td><input placeholder="https:// لینک به صورت " type="text" name="voorodak_options[logouturl]"
                                       value="<?php echo esc_attr($logouturl); ?>"/></td>
                        </tr>
                        <tr>
                            <th>فرمت ذخیره نام کاربری</th>
                            <td>
                                <select name="voorodak_options[username_format]">
                                    <option value="with-zero" <?php selected($username_format, 'with-zero'); ?>>با صفر اول (مثلا 09191234567)</option>
                                    <option value="without-zero" <?php selected($username_format, 'without-zero'); ?>>بدون صفر اول (مثلا 9191234567)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>فرمت ورود کاربران</th>
                            <td>
                                <select name="voorodak_options[login_type]">
                                    <option value="mobile" <?php selected($login_type, 'mobile'); ?>>فقط با موبایل</option>
                                    <option value="mobile-email" <?php selected($login_type, 'mobile-email'); ?>>با موبایل و ایمیل</option>
                                    <option value="mobile-email-username" <?php selected($login_type, 'mobile-email-username'); ?>>با موبایل و ایمیل و نام کاربری</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>طول کد یکبار مصرف</th>
                            <td>
                                <select name="voorodak_options[otp_length]">
                                    <option value="4" <?php selected($otp_length, '4'); ?>>4 رقم</option>
                                    <option value="5" <?php selected($otp_length, '5'); ?>>5 رقم</option>
                                    <option value="6" <?php selected($otp_length, '6'); ?>>6 رقم (پیشنهادی)</option>
                                    <option value="7" <?php selected($otp_length, '7'); ?>>7 رقم</option>
                                    <option value="8" <?php selected($otp_length, '8'); ?>>8 رقم</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>حداقل طول رمز عبور مجاز
                                <span class="hint">
                                به جهت امنیت بیشتر توصیه میکنیم زیر 8 حرف قرار ندهید
                            </span>
                            </th>
                            <td>
                                <select name="voorodak_options[password_length]">
                                    <option value="4" <?php selected($password_length, '4'); ?>>4 حرف</option>
                                    <option value="6" <?php selected($password_length, '6'); ?>>6 حرف</option>
                                    <option value="8" <?php selected($password_length, '8'); ?>>8 حرف (پیشنهادی)</option>
                                    <option value="10" <?php selected($password_length, '10'); ?>>10 حرف</option>
                                    <option value="12" <?php selected($password_length, '12'); ?>>12 حرف</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__family-name">فیلد نام و نام خانوادگی در ثبت نام</label></th>
                            <td><input type="checkbox" id="voorodak__family-name" name="voorodak_options[family_name]" value="1" <?php checked($family_name, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__family-name-force">الزامی بودن نام و نام خانوادگی</label></th>
                            <td><input type="checkbox" id="voorodak__family-name-force" name="voorodak_options[family_name_force]" value="1" <?php checked($family_name_force, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__email-field">فیلد ایمیل در ثبت نام</label></th>
                            <td><input type="checkbox" id="voorodak__email-field" name="voorodak_options[email_field]" value="1" <?php checked($email_field, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__email-field-force">الزامی بودن ایمیل</label></th>
                            <td><input type="checkbox" id="voorodak__email-field-force" name="voorodak_options[email_field_force]" value="1" <?php checked($email_field_force, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__password-field">فیلد رمز عبور در ثبت نام</label><span class="hint">این فیلد در صورت فعال شدن پیشفرض الزامی خواهد بود</span></th>
                            <td><input type="checkbox" id="voorodak__password-field" name="voorodak_options[password_field]" value="1" <?php checked($password_field, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__disable-admin-login">بستن ورود نقش های ادمین</label>
                                <span class="hint">با فعال کردن این گزینه، از ورود ادمین ها با دسترسی مدیر کل توسط فرم ورودک جلوگیری میشود و فقط از طریق صفحه ورود وردپرس امکان ورود وجود دارد، برای تدابیر امنیتی بالا پیشنهاد میشود.</span>

                            </th>
                            <td><input type="checkbox" id="voorodak__disable-admin-login" name="voorodak_options[disable_admin_login]" value="1" <?php checked($disable_admin_login, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__digits">هماهنگی با کاربران قبلی دیجیتس</label></th>
                            <td><input type="checkbox" id="voorodak__digits" name="voorodak_options[digits]" value="1" <?php checked($digits, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th>
                                <h3>ووکامرس</h3>
                            </th>
                        </tr>
                        <tr>
                            <th><label for="voorodak__woocommerce-login">تغییر صفحه ورود/ثبت نام ووکامرس</label></th>
                            <td><input type="checkbox" id="voorodak__woocommerce-login" name="voorodak_options[woocommerce_login]" value="1" <?php checked($woocommerce_login, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th><label for="voorodak__woocommerce-checkout">کاربر در صفحه پرداخت، اول لاگین کند</label></th>
                            <td><input type="checkbox" id="voorodak__woocommerce-checkout" name="voorodak_options[woocommerce_checkout]" value="1" <?php checked($woocommerce_checkout, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th>فیلد سفارشی ذخیره موبایل کاربر
                            <span class="hint">
                                مقدار پیشفرض ووکامرس: billing_phone
                            </span>
                            </th>
                            <td><input type="text" name="voorodak_options[user_field_meta]"
                                       value="<?php echo esc_attr($user_field_meta); ?>"/></td>
                        </tr>
                    </table>
                </div>
                <div id="display" class="voorodak__body-main-box" style="display: none">
                    <table class="form-table">
                        <tr>
                            <th>قالب نمایش</th>
                            <td>
                                <select name="voorodak_options[template]">
                                    <option value="default" <?php selected($template, 'default'); ?>>پیشفرض</option>
                                    <option value="digikala" <?php selected($template, 'digikala'); ?>>دیجی کالا</option>
                                    <option value="zarinpal" <?php selected($template, 'zarinpal'); ?>>زرین پال</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>لوگو</th>
                            <td>
                                <input type="hidden" name="voorodak_options[logo]" id="voorodak__logo"
                                       value="<?php echo $logo; ?>"/>
                                <input type="button" id="voorodak__logo-upload-button" class="button" value="آپلود تصویر"/>
                                <input type="button" id="voorodak__logo-upload-remove" class="button"
                                       value="حذف تصویر" <?php echo empty($logo) ? 'style="display:none;"' : ''; ?> />
                                <div id="voorodak__logo-preview">
                                    <?php if ($logo) : ?>
                                        <img src="<?php echo $logo; ?>" style="max-width: 200px; max-height: 200px;"/>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>تصویر کنار فرم (برای قالب زرین پال)</th>
                            <td>
                                <input type="hidden" name="voorodak_options[cover]" id="voorodak__cover"
                                       value="<?php echo $cover; ?>"/>
                                <input type="button" id="voorodak__cover-upload-button" class="button" value="آپلود تصویر"/>
                                <input type="button" id="voorodak__cover-upload-remove" class="button"
                                       value="حذف تصویر" <?php echo empty($cover) ? 'style="display:none;"' : ''; ?> />
                                <div id="voorodak__cover-preview">
                                    <?php if ($cover) : ?>
                                        <img src="<?php echo $cover; ?>" style="max-width: 200px; max-height: 200px;"/>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>رنگ پس زمینه</th>
                            <td><input type="text" class="voorodak__color-picker" data-default-color="#ffffff"
                                       name="voorodak_options[bg_color]"
                                       value="<?php echo esc_attr($bg_color); ?>"/></td>
                        </tr>
                        <tr>
                            <th>رنگ دکمه</th>
                            <td><input type="text" class="voorodak__color-picker" data-default-color="#5498fa"
                                       name="voorodak_options[button_color]"
                                       value="<?php echo esc_attr($button_color); ?>"/></td>
                        </tr>
                        <tr>
                            <th>رنگ دکمه (هنگام هاور)</th>
                            <td><input type="text" class="voorodak__color-picker" data-default-color="#2c61a6"
                                       name="voorodak_options[button_color_hover]"
                                       value="<?php echo esc_attr($button_color_hover); ?>"/></td>
                        </tr>
                        <tr>
                            <th>متن اولیه فرم</th>
                            <td><input type="text" name="voorodak_options[form_name]"
                                       value="<?php echo esc_attr($form_name); ?>"/></td>
                        </tr>
                        <tr>
                            <th>
                                متن پذیرش قوانین و مقررات
                                <span class="hint">در صورت خالی بودن نمایش داده نخواهد شد</span>
                            </th>
                            <td>
                                <?php
                                wp_editor(
                                    $term_editor,
                                    'term_editor',
                                    array(
                                        'textarea_name' => 'voorodak_options[term_editor]',
                                        'media_buttons' => false,
                                        'teeny'         => true,
                                        'quicktags'     => false,
                                        'tinymce'       => array(
                                            'toolbar1' => 'bold,italic,underline,forecolor,link,unlink',
                                            'toolbar2' => '',
                                            'plugins'  => 'textcolor link'
                                        )
                                    )
                                );

                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="advance" class="voorodak__body-main-box" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th>نمایش تاریخ عضویت کاربران
                                <span class="hint">با فعال کردن این گزینه، تاریخ عضویت کاربران در صفحه لیست کاربران نمایش داده میشود.</span>
                            </th>
                            <td><input type="checkbox" name="voorodak_options[date_register]" value="1" <?php checked($date_register, '1'); ?> /></td>
                        </tr>
                        <tr>
                            <th>خروجی CSV کاربران</th>
                            <td><span id="download_list_users" class="button">دانلود لیست کاربران</span></td>
                        </tr>
                    </table>
                </div>
                <div id="license" class="voorodak__body-main-box" style="display: none">
                    <table class="form-table">
                        <tr class="voorodak__license">
                            <th>کلید لایسنس
                            <?php do_action('voorodak_before_sms_setting'); ?>
                            </th>
                            <td><input type="text" name="voorodak_options[license_key]" value="<?php echo esc_attr($license_key); ?>" /></td>
                        </tr>
                    </table>
                </div>
                <?php submit_button(); ?>
            </form>
            <div class="voorodak__body-main-hints">
                <div class="melipayamak">
                    <img src="<?php echo plugin_dir_url(__DIR__); ?>/assets/images/logo.svg" alt="">
                    <div class="melipayamak__main">
                        <h3>تخفیف ویژه ملی پیامک برای افزونه ورودک</h3>
                        <p>استفاده کنندگان افزونه ورودک میتوانند با کوپن زیر تا 10 درصد تخفیف از سامانه ملی پیامک خرید نمایند</p>
                    </div>
                    <div class="melipayamak__coupon">
                        <div class="melipayamak__coupon-main">
                            <div class="melipayamak__coupon-main-inner">MPQPTDF</div>
                        </div>
                    </div>
                </div>
                <div class="voorodak__body-main-hints-list">
                    <h3>راهنمای استفاده</h3>
                    <ol>
                        <li>یک سامانه پیامکی تهیه نمایید و اطلاعات حساب خود را در تنظیمات افزونه قرار دهید</li>
                        <li>یک برگه به طور خودکار در سایت شما با نام ورود ثبت نام ایجاد شده است ، که صفحه اختصاصی ورود ثبت نام شما میباشد
                            <a target="_blank" href="<?php echo esc_url(get_the_permalink($login_page_id)); ?>">مشاهده برگه</a> (باید در حالت غیر لاگین یعنی private یا incognito برگه را باز کنید تا ورود ثبت نام را مشاهده کنید) </li>
                        <li>لینک این صفحه را در تنظیمات سربرگ قالب خود یا المنتور در دکمه ورود ثبت نام قرار دهید تا در دسترس باشد</li>
                        <li>همچنین از تب ظاهری میتوانید برگه ورود ثبت نام را تغییر دهید و برگه دیگری را انتخاب نمایید</li>
                    </ol>
                </div>
                <div class="taktheme">
                    <img src="<?php echo plugin_dir_url(__DIR__); ?>/assets/images/taktheme.png" alt="">
                    <p>در صورت وجود هرگونه مشکل در افزونه یا دریافت پشتیبانی میتوانید از طریق تلگرام به پشتیبانی تک تم پیام دهید</p>
                    <a href="https://t.me/taktheme_support" rel="nofollow">پشتیبانی تلگرام</a>
                </div>
            </div>
        </div>
    </div>
</div>