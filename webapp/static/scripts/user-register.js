$( document ).ready(
	function()
	{
		$("form[name=register]").validate({
		rules: {
		s_name: {
		required: true,
		minlength: 3,
		maxlength: 50
		},
		s_email: {
		required: true,
		email: true
		},
		s_password: {
		required: true,
		minlength: 5
		},
		s_password2: {
		required: true,
		minlength: 5,
		equalTo: "#s_password"
		}
		},
		messages: {
		s_name: {
		minlength: "Name: enter at least 3 characters.",
		maxlength: "Name: no more than 50 characters."
		},
		s_email: {
		required: "Email: this field is required.",
		email: "Invalid email address."
		},
		s_password: {
		required: "Password: this field is required.",
		minlength: "Password: enter at least 5 characters."
		},
		s_password2: {
		equalTo: "Passwords don't match."
		}
		},
		errorLabelContainer: "#error_list",
		wrapper: "li",
		invalidHandler: function(form, validator) {
		$('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
		}
		});
	}
);

