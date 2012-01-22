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
                    required: "<?php
		_e("Email: this field is required"); ?>.",
                    email: "<?php
		_e("Invalid email address"); ?>."
                },
                authorName: {
                    required: "<?php
		_e("Name: this field is required"); ?>."
                },
                message: {
                    required: "<?php
		_e("Message: this field is required"); ?>.",
                    minlength: "<?php
		_e("Message: this field is required"); ?>."
                }
            },
            errorLabelContainer: "#comment_error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h2').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

