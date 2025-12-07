jQuery(document).ready(function () {
    jQuery('#download_list_users').on('click', function(e) {
        var btnText = jQuery('#download_list_users').html();
        e.preventDefault();
        jQuery.ajax({
            url: voorodak_admin_ajax.ajax_url,
            type: 'POST',
            data: { action: 'get_users_list_voorodak' },
            beforeSend: function() {
                jQuery('#download_list_users').html('در حال دریافت...').toggleClass('disabled');
            },
            success: function(response) {
                jQuery('#download_list_users').html(btnText).toggleClass('disabled');
                if (response.success) {
                    let blob = new Blob([response.data], { type: 'text/csv;charset=utf-8;' });
                    let link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'users_list.csv';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    alert('خطا در دریافت لیست کاربران');
                }
            },
            error: function() {
                alert('خطا در ارسال درخواست');
                jQuery('#download_list_users').html(btnText).toggleClass('disabled');
            }
        });
    });
    jQuery('#test_phone_submit').on('click', function(e) {
        var btn = jQuery('#test_phone_submit').html();
        e.preventDefault();
        var phone = jQuery('#test_phone_number').val();
        jQuery.ajax({
            url: voorodak_admin_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'submit_test_phone',
                phone: phone
            },
            beforeSend: function (){
                jQuery('#test_phone_submit').html('در حال ارسال').toggleClass('disabled');
            },
            success: function(response) {
                console.log(response.data);
                jQuery('#test_phone_submit').html(btn).toggleClass('disabled');
                jQuery("#test_phone_result").fadeIn();
                jQuery("#test_phone_result td").html(response.data);
            }
        });
    });

    function checkFieldsSmart(){
        var selected_value = jQuery('.voorodak__gateway').find(":selected").val();
        if (selected_value.indexOf('pattern') >= 0){
            jQuery(".voorodak__message").hide();
            jQuery(".voorodak__pattern").fadeIn();
        }else {
            jQuery(".voorodak__message").fadeIn();
            jQuery(".voorodak__pattern").hide();
        }

        if (selected_value.indexOf('kavenegar_pattern') >= 0 || selected_value.indexOf('ghasedak_pattern') >= 0 || selected_value.indexOf('smsir_pattern') >= 0){
            jQuery(".voorodak__username").find('th').text('کلید API');
            jQuery(".voorodak__password").hide();
            jQuery(".voorodak__from").hide();
        }else if(selected_value.indexOf('farapayamak_pattern') >= 0 || selected_value.indexOf('payamito_pattern') >= 0 || selected_value.indexOf('melipayamak_pattern') >= 0){
            jQuery(".voorodak__from").hide();
        }else {
            jQuery(".voorodak__username").find('th').text('نام کاربری سامانه');
            jQuery(".voorodak__password").fadeIn();
            jQuery(".voorodak__from").fadeIn();
        }


        var redirect_checked = jQuery('.voorodak__backurl input[type=radio]:checked').val();
        if (redirect_checked == 'custom'){
            jQuery(".voorodak__backurl-custom").fadeIn();
        }else {
            jQuery(".voorodak__backurl-custom").hide();
        }
    }
    checkFieldsSmart();
    jQuery('.voorodak__gateway,.voorodak__backurl input[type=radio]').on('change', function () {
        checkFieldsSmart();
    });
    jQuery(".voorodak__body-tab a").click(function (e) {
        e.preventDefault();
        jQuery(".voorodak__body-tab a").removeClass('active');
        jQuery(this).addClass('active');
        var target = jQuery(this).attr('href');
        jQuery(".voorodak__body-main-box").hide();
        jQuery(target).fadeIn();
    });
    jQuery('.voorodak__color-picker').wpColorPicker();
    var mediaUploader;
    function initMediaUploader(buttonSelector, previewSelector, inputSelector, removeButtonSelector) {
        jQuery(buttonSelector).click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'انتخاب تصویر پس زمینه',
                button: {
                    text: 'انتخاب تصویر'
                },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                jQuery(previewSelector).html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 200px;" />');
                jQuery(inputSelector).val(attachment.url);
                jQuery(removeButtonSelector).show();
            });
            mediaUploader.open();
        });

        jQuery(removeButtonSelector).click(function() {
            jQuery(previewSelector).html('');
            jQuery(inputSelector).val('');
            jQuery(this).hide();
        });
    }
    initMediaUploader('#voorodak__logo-upload-button', '#voorodak__logo-preview', 'input[name="voorodak_options[logo]"]', '#voorodak__logo-upload-remove');
    initMediaUploader('#voorodak__cover-upload-button', '#voorodak__cover-preview', 'input[name="voorodak_options[cover]"]', '#voorodak__cover-upload-remove');
});