    $(document).ready(function(){
        // Code for form validation
        $("form[name=comment_form]").validate({
            rules: {
                message: {
                    required: true,
                    minlength: 1
                },
                authorEmail: {
                    required: true,
                    email: true
                },
                authorName: {
                    required: true
                }
            },
            messages: {
                authorEmail: {
                    required: "Email: this field is required.",
                    email: "Invalid email address."
                },
                authorName: {
                    required: "Name: this field is required."
                },
                message: {
                    required: "Message: this field is required.",
                    minlength: "Message: this field is required."
                }
            },
            errorLabelContainer: "#comment_error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h2').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

