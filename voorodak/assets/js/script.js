jQuery(document).ready(function () {

    let voorodak_ajax = true;
    let voorodak_error = true;
    const voorodak_messages = jQuery(".voorodak__wrapper-messages");
    const voorodak_security = voorodak_data.security;
    const voorodak_otp_length = voorodak_data.otp_length;
    const voorodak_password_length = voorodak_data.password_length;
    const voorodak_login_url = voorodak_data.login_url;
    const voorodak_backurl = voorodak_data.backurl;
    const voorodak_login_type = voorodak_data.login_type;
    const voorodak_mobile_regex = /^09[0-9]{9}$/;
    const voorodak_email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const voorodak_number_regex = /^-?\d+$/;

    jQuery('.voorodak__wrapper-main-box-field input').bind("input", function () {
        var pn = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
        var en = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        var cache = jQuery(this).val();
        for (var i = 0; i < 10; i++) {
            var regex_fa = new RegExp(pn[i], 'g');
            cache = cache.replace(regex_fa, en[i]);
        }
        jQuery(this).val(cache);
    });

    jQuery(".voorodak__wrapper-main-box-action a").click(function (e) {
        e.preventDefault();
        var target = jQuery(this).attr('href');
        jQuery(".voorodak__wrapper-main-box").hide();
        jQuery(target).fadeIn();
        voorodak_messages.html('');
    });

    jQuery(".voorodak__wrapper-main-head svg").click(function (){
        jQuery(this).hide();
        jQuery(".voorodak__wrapper-main-box").hide();
        jQuery("#voorodak__wrapper-main-username").fadeIn();
    });

    function removeValidationMessages() {
        jQuery(".voorodak__wrapper-main-box-field-invalid").next('span').remove();
        jQuery(".voorodak__wrapper-main-box-field-invalid").removeClass('voorodak__wrapper-main-box-field-invalid');
    }

    function addValidationMessage(element, message) {
        element.addClass('voorodak__wrapper-main-box-field-invalid').after('<span>' + message + '</span>');
    }

    function validateUsername(element) {
        var value = element.val().trim();
        removeValidationMessages();
        if (value.length < 1) {
            addValidationMessage(element, 'لطفا این قسمت را خالی نگذارید');
            voorodak_error = true;
            return;
        }
        if (voorodak_login_type === 'mobile' && !voorodak_mobile_regex.test(value)) {
            addValidationMessage(element, 'شماره موبایل صحیح نمی‌باشد.');
            voorodak_error = true;
            return;
        } else if (voorodak_login_type === 'mobile-email' && !voorodak_mobile_regex.test(value) && !voorodak_email_regex.test(value)) {
            addValidationMessage(element, 'شماره موبایل یا ایمیل صحیح نمی‌باشد.');
            voorodak_error = true;
            return;
        } else {
            voorodak_error = false;
        }
    }

    function validateOTP(element) {
        var value = element.val().trim();
        removeValidationMessages();
        if (value.length !== parseInt(voorodak_otp_length)){
            addValidationMessage(element, 'کد تایید باید ' + voorodak_otp_length + ' رقم باشد');
            voorodak_error = true;
        } else if (!voorodak_number_regex.test(value)) {
            addValidationMessage(element, 'کد تایید باید عددی باشد');
            voorodak_error = true;
        } else {
            voorodak_error = false;
        }
    }

    function validatePassword(element) {
        var value = element.val().trim();
        removeValidationMessages();
        if (value.length < voorodak_password_length) {
            addValidationMessage(element, 'رمز عبور باید حداقل ' + voorodak_password_length + ' حرف باشد');
            voorodak_error = true;
        } else {
            voorodak_error = false;
        }
    }

    jQuery("input[name=voorodak__username],input[name=voorodak__username-forget]").focusout(function(){
        validateUsername(jQuery(this));
    });

    jQuery("input[name=voorodak__otp],input[name=voorodak__otp-reset]").focusout(function(){
        validateOTP(jQuery(this));
    });

    jQuery("input[name=voorodak__otp],input[name=voorodak__otp-reset]").on('input', function () {
        var value = jQuery(this).val().trim();
        if (value.length >= parseInt(voorodak_otp_length)) {
            jQuery(jQuery(this)).parent().nextAll('button').trigger('click');
        }
    });

    jQuery("input[name=voorodak__password],input[name=voorodak__new-password]").focusout(function(){
        validatePassword(jQuery(this));
    });

    var intervalId;
    let duration = 120;
    const voorodak_timer = jQuery(".voorodak__wrapper-main-box-timer-countdown span");
    const timerKey = 'savedTimer';
    const startTimeKey = 'startTime';
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        intervalId = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.text(minutes + ":" + seconds);
            if (--timer < 0) {
                clearInterval(intervalId);
                localStorage.removeItem(timerKey);
                localStorage.removeItem(startTimeKey);
                jQuery('.voorodak__wrapper-main-box-timer-resend').fadeIn();
                jQuery('.voorodak__wrapper-main-box-timer-countdown').hide();
            }else {
                jQuery('.voorodak__wrapper-main-box-timer-resend').hide();
                jQuery('.voorodak__wrapper-main-box-timer-countdown').fadeIn();
            }
        }, 1000);
    }

    function resumeTimer() {
        const savedTimer = localStorage.getItem(timerKey);
        const savedStartTime = localStorage.getItem(startTimeKey);
        if (savedTimer && savedStartTime) {
            const elapsedTime = Math.floor((Date.now() - savedStartTime) / 1000);
            const remainingTime = savedTimer - elapsedTime;
            if (remainingTime > 0) {
                startTimer(remainingTime, voorodak_timer);
            } else {
                localStorage.removeItem(timerKey);
                localStorage.removeItem(startTimeKey);
            }
        }
    }
    resumeTimer();

    function ajaxRequest(data, beforeSendCallback, successCallback, errorCallback) {
        if (voorodak_ajax && !voorodak_error) {
            jQuery.ajax({
                url: voorodak_data.ajax_url,
                data: data,
                dataType: 'json',
                type: 'post',
                timeout: 20000,
                beforeSend: beforeSendCallback,
                error: errorCallback,
                success: successCallback
            });
        }
    }

    jQuery(document).on('click', '#voorodak__submit-username', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var username_element = jQuery("input[name=voorodak__username]");
        var username = username_element.val();
        validateUsername(username_element);
        ajaxRequest(
            {
                'action': action,
                'username': username,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    jQuery("#voorodak__wrapper-main-username").hide();
                    jQuery(".voorodak__wrapper-main-head svg").fadeIn();
                    if (voorodak_mobile_regex.test(username)){
                        if (response.data.sent) {
                            if (intervalId) {
                                clearInterval(intervalId);
                            }
                            voorodak_timer.text("02:00");
                            localStorage.setItem(startTimeKey, Date.now());
                            localStorage.setItem(timerKey, duration);
                            startTimer(duration, voorodak_timer);
                        }
                        jQuery('#voorodak__wrapper-main-otp .voorodak__wrapper-main-box-description').html(response.data.description);
                        if (~response.data.description.indexOf("حساب کاربری")){
                            jQuery(".voorodak__wrapper-main-box-action").css('display','none');
                            jQuery("input[name=voorodak__first_name],input[name=voorodak__last_name],input[name=voorodak__email],input[name=voorodak__password_register]").css('display','block');
                        }else {
                            jQuery("input[name=voorodak__first_name],input[name=voorodak__last_name],input[name=voorodak__email],input[name=voorodak__password_register]").css('display','none');
                            jQuery(".voorodak__wrapper-main-box-action").css('display','flex');
                            jQuery("a[href='#voorodak__wrapper-main-otp']").css('display','inline-flex');
                        }
                        jQuery("#voorodak__wrapper-main-otp").fadeIn();
                    }else {
                        jQuery("a[href='#voorodak__wrapper-main-otp']").css('display','none');
                        jQuery("#voorodak__wrapper-main-password").fadeIn();
                    }
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    jQuery(document).on('click', '#voorodak__submit-otp', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var username = jQuery("input[name=voorodak__username]").val();
        var first_name = jQuery("input[name=voorodak__first_name]").val();
        var last_name = jQuery("input[name=voorodak__last_name]").val();
        var email = jQuery("input[name=voorodak__email]").val();
        var password = jQuery("input[name=voorodak__password_register]").val();
        var otp_element = jQuery("input[name=voorodak__otp]");
        var otp = otp_element.val();
        validateOTP(otp_element);
        ajaxRequest(
            {
                'action': action,
                'username': username,
                'first_name': first_name,
                'last_name': last_name,
                'email': email,
                'password': password,
                'otp': otp,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    voorodak_ajax = false;
                    window.location = voorodak_backurl;
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    jQuery(document).on('click', '#voorodak__submit-password', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var username = jQuery("input[name=voorodak__username]").val();
        var password_element = jQuery("input[name=voorodak__password]");
        var password = password_element.val();
        validatePassword(password_element);
        ajaxRequest(
            {
                'action': action,
                'username': username,
                'password': password,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    voorodak_ajax = false;
                    window.location = voorodak_backurl;
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    jQuery(document).on('click', '#voorodak__submit-forget', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var username_element = jQuery("input[name=voorodak__username-forget]");
        var username = username_element.val();
        validateUsername(username_element);
        ajaxRequest(
            {
                'action': action,
                'username': username,
                'login_url': voorodak_login_url,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    if (voorodak_mobile_regex.test(username)){
                        if (response.data.sent) {
                            if (intervalId) {
                                clearInterval(intervalId);
                            }
                            voorodak_timer.text("02:00");
                            localStorage.setItem(startTimeKey, Date.now());
                            localStorage.setItem(timerKey, duration);
                            startTimer(duration, voorodak_timer);
                        }
                        jQuery("#voorodak__wrapper-main-otp-reset").fadeIn();
                        jQuery("#voorodak__wrapper-main-forget").hide();
                    }
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    jQuery(document).on('click', '#voorodak__submit-otp-reset', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var username = jQuery("input[name=voorodak__username-forget]").val();
        var otp_element = jQuery("input[name=voorodak__otp-reset]");
        var otp = otp_element.val();
        validateOTP(otp_element);
        ajaxRequest(
            {
                'action': action,
                'username': username,
                'otp': otp,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    if (voorodak_mobile_regex.test(username)){
                        jQuery("#voorodak__wrapper-main-otp-reset").hide();
                        jQuery("#voorodak__wrapper-main-reset").fadeIn();
                        jQuery("input[name=voorodak__reset-token]").val(response.data.reset_token);
                    }
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    jQuery(document).on('click', '#voorodak__submit-reset', function () {
        var button = jQuery(this);
        var button_init = button.html();
        var action = jQuery(this).attr('id');
        var new_password_element = jQuery("input[name=voorodak__new-password]");
        var new_password = new_password_element.val();
        var new_password2 = jQuery("input[name=voorodak__new-password2]").val();
        var reset_token = jQuery("input[name=voorodak__reset-token]").val();
        validatePassword(new_password_element);
        ajaxRequest(
            {
                'action': action,
                'new_password': new_password,
                'new_password2': new_password2,
                'reset_token': reset_token,
                'security': voorodak_security,
            },
            function () {
                voorodak_ajax = false;
                voorodak_messages.html('');
                button.html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            },
            function (response) {
                voorodak_ajax = true;
                button.html(button_init);
                voorodak_messages.html(response.data.message);
                if (response.success) {
                    voorodak_ajax = false;
                    window.location = voorodak_backurl;
                }
            },
            function () {
                voorodak_ajax = true;
                button.html(button_init);
            }
        );

    });

    if(jQuery('.voorodak__wrapper-main-box-timer').length) {
        jQuery('.voorodak__wrapper-main-box-timer-resend').on('click', function () {
            jQuery(".voorodak__wrapper-main-box-timer-countdown").fadeIn();
            jQuery(this).hide();
        });
        jQuery('#voorodak__wrapper-main-otp .voorodak__wrapper-main-box-timer-resend').on('click', function () {
            jQuery("#voorodak__submit-username").trigger("click");
        });
        jQuery('#voorodak__wrapper-main-otp-reset .voorodak__wrapper-main-box-timer-resend').on('click', function () {
            jQuery("#voorodak__submit-forget").trigger("click");
        });
    }

});