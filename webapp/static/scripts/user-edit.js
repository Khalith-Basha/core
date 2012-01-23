$(document).ready(function(){
    $('#s_name').focus(function(){
        $('#s_name').css('border', '');
    });

    $('#s_email').focus(function(){
        $('#s_email').css('border', '');
    });

    $('#s_password').focus(function(){
        $('#s_password').css('border', '');
        $('#password-error').css('display', 'none');
    });

    $('#s_password2').focus(function(){
        $('#s_password2').css('border', '');
        $('#password-error').css('display', 'none');
    });
});

function checkForm() {
    var num_errors = 0;
    if( $('#s_name').val() == '' ) {
        $('#s_name').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_email').val() == '' ) {
        $('#s_email').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() != $('#s_password2').val() ) {
        $('#password-error').css('display', 'block');
        num_errors = num_errors + 1;
    }
    if(num_errors > 0) {
        return false;
    }

    return true;
}

