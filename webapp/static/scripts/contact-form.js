
$( document ).ready( function(){
        $("form[name=contact]").validate({
            rules: {
                message: {
                    required: true,
                    minlength: 1
                },
                yourEmail: {
                    required: true,
                    email: true
                }
            },
            messages: {
                yourEmail: {
                    required: "Email: this field is required.",
                    email: "Invalid email address."
                },
                message: {
                    required: "Message: this field is required.",
                    minlength: "Message: this field is required."
                }
            },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

