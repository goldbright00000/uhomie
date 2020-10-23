require('jquery-pinlogin/src/jquery.pinlogin');

$(function() {
    $('#token-code').pinlogin({
        fields: 4,
        hideinput: false,
        reset: false,
        complete: function(pin) {
            $('input[name=token]').val(pin);
        }
    });

    $('#btn-send-form').click(function(e) {
        e.preventDefault();
        $form = $('#form-phone-verification');
        let url = $form.attr('action');
        let data = $form.serialize();
        window.axios.post(url, data)
            .then(response => {
                if (response.data.status_code == 200) {
                    $('#modal-title').text('¡Felicitaciones!');
                    $('#clap').show();
                    $('#modal-text').text('Hemos validado tu número de teléfono. A continuación iniciaremos el proceso de creación de creación de tu perfil en UHOMIE.');
                    $('#btn-next-page').text('Empezar');
                    $('#btn-next-page').attr('href', response.data.page);
                    $('.modal-info').addClass('is-active');
                }else{
                    $('#modal-title').text('¡Ups!');
                    $('#clap').hide();
                    $('#modal-text').text('El código es incorrecto, intenta nuevamente o solicita uno nuevo');
                    $('#btn-next-page').text('Entendido');
                    $('#btn-next-page').attr('onclick', "$('.modal-info').hide();");
                    $('.modal-info').addClass('is-active');
                }
            });
    });

    $('#btn-retry').click(function() {
        if ($(this).is(':disabled')) {
            return;
        }

        let url = document.getElementById('url-retry-sms').value;
        window.axios.post(url, {
            code:  $('input[name=code]').val(),
            phone: $('input[name=phone]').val()
        }).then(() => {
            startCountdown();
        });
    });

    var countdown;

    function startCountdown() {
        $('#btn-retry').prop('disabled', true);
        $('#btn-change-phone').attr('disabled', 'disabled');

        var time = 20;
        countdown = setInterval(function() {
            if (time < 0) {
                enableRetry();
                return;
            }
            $('#timer-count').text(time);
            time = time - 1;
        }, 1000);
    }

    function enableRetry() {
        clearInterval(countdown);
        $('#btn-retry').prop('disabled', false);
        $('#btn-change-phone').removeAttr('disabled');
    }

    startCountdown();

});
