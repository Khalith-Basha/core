    $(document).ready(function(){
        // Code for form validation
        $("form[name=sendfriend]").validate({
            rules: {
                yourName: {
                    required: true
                },
                yourEmail: {
                    required: true,
                    email: true
                },
                friendName: {
                    required: true
                },
                friendEmail: {
                    required: true,
                    email: true
                },
                message:  {
                    required: true
                }
            },
            messages: {
                yourName: {
                    required: "Your name: this field is required."
                },
                yourEmail: {
                    email: "Invalid email address.",
                    required: "Email: this field is required."
                },
                friendName: {
                    required: "Friend's name: this field is required."
                },
                friendEmail: {
                    required: "Friend's email: this field is required.",
                    email: "Invalid friend's email address."
                },
                message: "Message: this field is required."
                
            },
            //onfocusout: function(element) { $(element).valid(); },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

