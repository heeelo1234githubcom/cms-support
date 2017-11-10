
(function($) {

    'use strict';

    /*
     Circle Slider
     */
    if ($.isFunction($.fn.flipshow)) {
        var circleContainer = $('#fcSlideshow');

        if (circleContainer.get(0)) {
            circleContainer.flipshow();

            setTimeout(function circleFlip() {
                circleContainer.data().flipshow._navigate(circleContainer.find('div.fc-right span:first'), 'right');
                setTimeout(circleFlip, 3000);
            }, 3000);
        }
    }

    var $isSubmitting = false;

    $.hideLoading = function () {
        $isSubmitting = false;
        $('button[type=submit]').button('reset');
    };

    $.showLoading = function (obj) {
        $isSubmitting = true;
        $(obj).find('button[type=submit]').button('loading');
    };

    $.ajaxErrorHandler = function($XMLHttpRequest, $textStatus)
    {
        $.hideLoading();

        if ($XMLHttpRequest.status === 422) {
            for (var name in $XMLHttpRequest['responseJSON']) {
                if (name === 'newsletter_email') {
                    $('[name="' +name+ '"]').parent().after('<label class="error">' +$XMLHttpRequest['responseJSON'][name][0]+ '</label>');
                } else {
                    $('[name="' +name+ '"]').parent().append('<label class="error">' +$XMLHttpRequest['responseJSON'][name][0]+ '</label>');
                }
            }
        }

        return false;
    };

    $.progressHandlingFunction = function( $e ) {};

    $( document ).on( "submit", "form.ajax-form-submit", function( $e ) {

        if ( $isSubmitting ) {
            return false;
        }

        $('label.error').remove();
        $('.alert').remove();

        $e.preventDefault();

        var $ajaxForm = $( this );

        // post login-form data
        var $formData = new FormData( $( this )[ 0 ] );

        // show loading
        $.showLoading(this);

        $.ajax( {
            url: $ajaxForm.attr( "action" ),
            type: "POST",

            // custom xhr
            xhr: function() {
                var $xhr = $.ajaxSettings.xhr();

                // check if upload property exists
                if ( $xhr.upload ) {

                    // for handling the progress of the upload
                    $xhr.upload.addEventListener("progress", $.progressHandlingFunction, false);
                }
                return $xhr;
            },

            //Ajax events
            success: function( $responseText ) {

                // hide loading
                $.hideLoading();

                $ajaxForm[0].reset();

                if (typeof $responseText.message !== 'undefined') {
                    $ajaxForm.before('<div class="alert alert-success mt-md">' +$responseText.message+ '</div>');
                }
            },
            error: $.ajaxErrorHandler,

            // Form data
            data: $formData,

            //Options to tell JQuery not to process data or worry about content-type
            cache: false,
            contentType: false,
            processData: false
        } );
    } );

}).apply(this, [jQuery]);
