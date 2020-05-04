/*######################################################
Devstratum JMM Feedback
Simple feedback form module

Version: 1.0
License: MIT License
Author: Sergey Osipov
Copyright: © 2020 Sergey Osipov
Website: https://devstratum.ru
Email: info@devstartum.ru
Repo: https://github.com/devstratum/Devstratum-JMM-Feedback

     _                _             _
  __| | _____   _____| |_ _ __ __ _| |_ _   _ _ __ ___
 / _` |/ _ \ \ / / __| __| '__/ _` | __| | | | '_ ` _ \
| (_| |  __/\ V /\__ \ |_| | | (_| | |_| |_| | | | | | |
 \__,_|\___| \_/ |___/\__|_|  \__,_|\__|\__,_|_| |_| |_|

########################################################*/

jQuery(document).ready(function($) {
    // Vars
    var feedback = $('.dvstr-feedback');
    var feedback_privacy = feedback.find('.dvstr-feedback__privacy');
    var feedback_submit = feedback.find('.dvstr-feedback__submit');
    var privacy_checkbox = feedback_privacy.find('.privacy-checkbox');
    var submit_button = feedback_submit.find('.submit-button');

    // Check privacy confirm
    privacy_checkbox.on('click', function() {
        var form_id = $(this).data('form-id');
        var form_submit = $('#dvstr_feedback_' + form_id).find('.submit-button');

        if ($(this).hasClass('checked')) {
            $(this).removeClass('checked');
            form_submit.addClass('disabled');
            form_submit.prop('disabled', true);
        } else {
            $(this).addClass('checked');
            form_submit.removeClass('disabled');
            form_submit.prop('disabled', false);
        }
    });

    // Submit event
    submit_button.on('click', function() {
        if (!$(this).hasClass('disabled')) {
            $(this).addClass('freeze');

            var form_id = $(this).data('form-id');
            var form_object = $('#dvstr_feedback_' + form_id);
            var form_progress = form_object.find('.dvstr-feedback__progress');
            var form_alert = form_object.find('.dvstr-feedback__alert');

            // Clear Alert
            form_alert.html('');

            // Check fields event
            var error = checkFields(form_object);

            if (error === '') {
                form_progress.addClass('active');
                setTimeout(function() {
                    AjaxQuery(form_object, form_id);
                }, 1000);
            } else {
                $(this).removeClass('freeze');
            }
        }
    });

    // Check fields
    function checkFields(form_object) {
        var error = '';
        form_object.find('.dvstr-field.required').removeClass('error');
        form_object.find('.dvstr-field.required').each(function() {
            if (!$(this).val() || $(this).val() === '' || $(this).val() === 'undefined') {
                $(this).addClass('error');
                error = 'error';
            }
        });

        return error;
    }

    // Clear fields
    function clearFields(form_object) {
        form_object.find('.dvstr-field').val('');
    }

    // AjaxQuery
    function AjaxQuery(form_object, form_id) {
        var form_submit = form_object.find('.submit-button');
        var form_progress = form_object.find('.dvstr-feedback__progress');
        var form_alert = form_object.find('.dvstr-feedback__alert');

        var form_data = form_object.find('form').serializeArray();

        var request = {
            'option': 'com_ajax',
            'module': 'dvstr_feedback',
            'format': 'json',
            'mod_id': form_id,
            'fields': form_data
        };

        $.ajax({
            type: 'POST',
            data: request,
        })
        .done(function(response) {
            form_submit.removeClass('freeze');
            form_progress.removeClass('active');

            if (response.success && response.data) {
                form_alert.html(response.data.info);
                if (response.data.clear) {
                    clearFields(form_object);
                }
            }

            if (response.success && !response.data) {
                form_alert.html('<div class="dvstr-alert dvstr-alert-warning">Form Data Empty</div>');
            }

            if (!response.success && response.message) {
                form_alert.html('<div class="dvstr-alert dvstr-alert-danger">' + response.message + '</div>');
            }

        })
        .fail(function() {
            form_submit.removeClass('freeze');
            form_progress.removeClass('active');
            form_alert.html('<div class="dvstr-alert dvstr-alert-danger">Server Error</div>');
        });
    }
});