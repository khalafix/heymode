<?php

// Exit if accessed directly.

defined('ABSPATH') || exit;

$template = $settings['template'] ?? 'default';

$login_page_id = $settings['login_page_id'] ?? '';

$bg_color = $settings['bg_color'] ?? '#ffffff';

$button_color = $settings['button_color'] ?? '#5498fa';

$button_color_hover = $settings['button_color_hover'] ?? '#2c61a6';

$logo = $settings['logo'] ?? '';

$cover = $settings['cover'] ?? '';

$otp_length = $settings['otp_length'] ?? '6';

$family_name = $settings['family_name'] ?? '';

$email_field = $settings['email_field'] ?? '';

$password_field = $settings['password_field'] ?? '';

$login_type = $settings['login_type'] ?? 'mobile-email';

if ($login_type == 'mobile') {

    $username_placeholder = 'شماره موبایل';
} elseif ($login_type == 'mobile-email') {

    $username_placeholder = 'شماره موبایل یا ایمیل';
} else {

    $username_placeholder = 'شماره موبایل یا ایمیل یا نام کاربری';
}

$reset_token = $_GET['reset_token'] ?? null;

$form_name = $settings['form_name'] ?? 'ورود / ثبت نام';

$term_editor = $settings['term_editor'] ?? '';

$button_style = "style=\"--voorodak-button-color: " . esc_attr($button_color) . "; --voorodak-button-color-hover: " . esc_attr($button_color_hover) . ";\"";

?>

<div class="voorodak voorodak-<?php echo esc_attr($template); ?>" style="background: <?php echo esc_attr($bg_color); ?>">

    <div class="voorodak__wrapper" <?php echo $button_style; ?>>

        <?php if ($template == 'zarinpal'): ?>

            <div class='voorodak__wrapper-main-right'>

            <?php endif; ?>

            <div class="voorodak__wrapper-main">



                <?php if (!$reset_token): ?>

                    <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-username">
                        <div class="voorodak__wrapper-main-head">

                            

                            <?php if ($logo): ?>

                                <a href="<?php echo home_url(); ?>">

                                    <img src="<?php echo esc_attr($logo); ?>"  alt="<?php bloginfo('name'); ?>">

                                </a>

                            <?php endif; ?>

                        </div>
                        <div class="voorodak__wrapper-main-box-title"><?php esc_html_e($form_name); ?></div>

                        <div class="voorodak__wrapper-main-box-description">

                            <p>سلام!</p>

                            <?php if ($login_type == 'mobile') {

                                $placeholder_inter = 'شماره موبایل';
                            } elseif ($login_type == 'mobile-email') {

                                $placeholder_inter = 'شماره موبایل یا ایمیل';
                            } else {

                                $placeholder_inter = 'اطلاعات کاربری';
                            } ?>

                            <p>لطفا <?php esc_html_e($placeholder_inter); ?> خود را وارد کنید</p>

                        </div>

                        <div class="voorodak__wrapper-main-box-field">

                            <input type="text" name="voorodak__username" placeholder="<?php echo esc_attr($username_placeholder); ?>" autocomplete="off" <?php if ($login_type == 'mobile') echo ' inputmode="numeric"'; ?>>

                        </div>

                        <button id="voorodak__submit-username">ورود</button>

                        <?php if ($term_editor): ?>

                            <div class="voorodak__terms">

                                <?php echo $term_editor; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-otp" style="display: none">

                        <div class="voorodak__wrapper-main-box-title">کد تایید</div>

                        <div class="voorodak__wrapper-main-box-description"></div>

                        <div class="voorodak__wrapper-main-box-field">

                            <?php if ($family_name): ?>

                                <input type="text" name="voorodak__first_name" placeholder="نام" autocomplete="off">

                                <input type="text" name="voorodak__last_name" placeholder="نام خانوادگی" autocomplete="off">

                                <div class="clear"></div>

                            <?php endif; ?>

                            <?php if ($email_field): ?>

                                <input type="text" name="voorodak__email" placeholder="ایمیل" autocomplete="off">

                            <?php endif; ?>

                            <?php if ($password_field): ?>

                                <input type="password" name="voorodak__password_register" placeholder="رمز عبور" autocomplete="off">

                            <?php endif; ?>

                            <input type="text" name="voorodak__otp" placeholder="کد تایید" inputmode="numeric" maxlength="<?php echo esc_attr($otp_length) ?>" autocomplete="off">

                        </div>

                        <?php if ($login_type != 'mobile'): ?>

                            <div class="voorodak__wrapper-main-box-action">

                                <a href="#voorodak__wrapper-main-password">ورود با رمز عبور

                                    <svg width="12" height="12" data-slot="icon" aria-hidden="true" fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                    </svg>

                                </a>

                            </div>

                        <?php endif; ?>

                        <div class="voorodak__wrapper-main-box-timer">

                            <div class="voorodak__wrapper-main-box-timer-countdown">

                                <span>02:00</span>

                                تا دریافت مجدد کد

                            </div>

                            <div class="voorodak__wrapper-main-box-timer-resend" style="display: none;">

                                دریافت کد

                                <svg width="12" height="12" data-slot="icon" aria-hidden="true" fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                    <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                </svg>

                            </div>

                        </div>

                        <button id="voorodak__submit-otp">تایید</button>

                    </div>

                    <?php if ($login_type != 'mobile'): ?>

                        <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-password" style="display: none">

                            <div class="voorodak__wrapper-main-box-title">رمز عبور را وارد کنید</div>

                            <div class="voorodak__wrapper-main-box-field">

                                <input type="password" name="voorodak__password" placeholder="رمز عبور" autocomplete="off">

                            </div>

                            <div class="voorodak__wrapper-main-box-action">

                                <a href="#voorodak__wrapper-main-otp">ورود با رمز یکبار مصرف

                                    <svg width="12" height="12" data-slot="icon" aria-hidden="true" fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                    </svg>

                                </a>

                                <a href="#voorodak__wrapper-main-forget">فراموشی رمز عبور

                                    <svg width="12" height="12" data-slot="icon" aria-hidden="true" fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                    </svg>

                                </a>

                            </div>

                            <button id="voorodak__submit-password">تایید</button>

                        </div>

                        <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-forget" style="display: none">

                            <div class="voorodak__wrapper-main-box-title">فراموشی رمز عبور</div>

                            <div class="voorodak__wrapper-main-box-field">

                                <input type="text" name="voorodak__username-forget" placeholder="شماره موبایل یا ایمیل" autocomplete="off">

                            </div>

                            <button id="voorodak__submit-forget">تایید</button>

                        </div>

                        <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-otp-reset" style="display: none">

                            <div class="voorodak__wrapper-main-box-title">کد تایید</div>

                            <div class="voorodak__wrapper-main-box-field">

                                <input type="text" name="voorodak__otp-reset" placeholder="کد تایید" inputmode="numeric" maxlength="<?php echo esc_attr($otp_length) ?>" autocomplete="off">

                            </div>

                            <div class="voorodak__wrapper-main-box-timer">

                                <div class="voorodak__wrapper-main-box-timer-countdown">

                                    <span>02:00</span>

                                    تا دریافت مجدد کد

                                </div>

                                <div class="voorodak__wrapper-main-box-timer-resend" style="display: none;">

                                    دریافت کد

                                    <svg width="12" height="12" data-slot="icon" aria-hidden="true" fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                    </svg>

                                </div>

                            </div>

                            <button id="voorodak__submit-otp-reset">تایید</button>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

                <?php if ($login_type != 'mobile'): ?>

                    <div class="voorodak__wrapper-main-box" id="voorodak__wrapper-main-reset" <?php echo !$reset_token ? ' style="display: none"' : ''; ?>>

                        <div class="voorodak__wrapper-main-box-title">تغییر رمز عبور</div>

                        <div class="voorodak__wrapper-main-box-field">

                            <input type="password" name="voorodak__new-password" placeholder="رمز عبور جدید" autocomplete="off">

                        </div>

                        <div class="voorodak__wrapper-main-box-field">

                            <input type="password" name="voorodak__new-password2" placeholder="تکرار رمز عبور جدید" autocomplete="off">

                        </div>

                        <input type="hidden" name="voorodak__reset-token" value="<?php echo esc_attr($reset_token); ?>">

                        <button id="voorodak__submit-reset">تایید</button>

                    </div>

                <?php endif; ?>

            </div>

            <div class="voorodak__wrapper-messages"></div>

            <?php if ($template == 'zarinpal'): ?>

            </div>
            <div class='voorodak__wrapper-main-left' style="background-color: <?php echo ($cover) ? 'transparent' : esc_attr($button_color); ?>">

                <?php if ($cover && !wp_is_mobile()): ?>

                    <img src="<?php echo esc_attr($cover); ?>" width="600" height="500" alt="<?php bloginfo('name'); ?>">

                <?php endif; ?>

            </div>
    </div>

<?php endif; ?>

</div>
<div class="khalafi-change-login">
    <img class="khalafi-change-login-banner" src="<?php echo plugin_dir_url(dirname(__FILE__, 1)) . 'assets/images/heymode-login.jpg'; ?>" />
</div>
</div>