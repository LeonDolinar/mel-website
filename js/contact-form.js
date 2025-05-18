(function ($, window, document, undefined) {
    'use strict';

    var $form = $('#contact-form');

    $form.submit(function (e) {
        e.preventDefault();

        $('.form-group').removeClass('has-error');
        $('.help-block').remove();

        var formData = {
            'name' : $('input[name="name"]').val(),
            'email' : $('input[name="email"]').val(),
            'phone' : $('input[name="phone"]').val(),
            'message' : $('textarea[name="message"]').val()
        };

        $.ajax({
            type : 'POST',
            url  : 'process.php',
            data : formData,
            dataType : 'json',
            encode : true
        }).done(function (data) {
            if (!data.success) {
                if (data.errors.name) {
                    $('#form-name').addClass('has-error');
                    $('#form-name').find('.col-lg-10').append('<span class="help-block">' + data.errors.name + '</span>');
                }
                if (data.errors.email) {
                    $('#form-email').addClass('has-error');
                    $('#form-email').find('.col-lg-10').append('<span class="help-block">' + data.errors.email + '</span>');
                }
                if (data.errors.message) {
                    $('#form-message').addClass('has-error');
                    $('#form-message').find('.col-lg-10').append('<span class="help-block">' + data.errors.message + '</span>');
                }
            } else {
                $form.html('<div class="alert alert-success">' + data.message + '</div>');
                $form[0].reset();
            }
        }).fail(function (data) {
            $form.prepend('<div class="alert alert-danger">Došlo je do pogreške prilikom obrade vašeg zahtjeva. Pokušajte ponovno kasnije.</div>');
        });
    });
}(jQuery, window, document));
