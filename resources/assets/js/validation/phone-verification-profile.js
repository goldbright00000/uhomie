require('jquery-pinlogin/src/jquery.pinlogin');

$(function() {
    $('#token-code').pinlogin({
        fields: 7,
        hideinput: false,
        reset: false,
        complete: function(pin) {
            $('input[name=token]').val(pin);
        }
    });
});
