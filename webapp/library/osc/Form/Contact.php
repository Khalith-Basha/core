<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class Form_Contact extends Form
{
	static public function primary_input_hidden() 
	{
		parent::generic_input_hidden("id", osc_item_id());
		return true;
	}
	static public function page_hidden() 
	{
		parent::generic_input_hidden("page", 'item');
		return true;
	}
	static public function action_hidden() 
	{
		parent::generic_input_hidden("action", 'contact');
		return true;
	}
	static public function your_name() 
	{
		$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
		if ($session->_getForm("yourName") != "") 
		{
			$name = $session->_getForm("yourName");
			parent::generic_input_text("yourName", $name, null, false);
		}
		else
		{
			parent::generic_input_text("yourName", osc_logged_user_name(), null, false);
		}
		return true;
	}
	static public function your_email() 
	{
		$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
		if ($session->_getForm("yourEmail") != "") 
		{
			$email = $session->_getForm("yourEmail");
			parent::generic_input_text("yourEmail", $email, null, false);
		}
		else
		{
			parent::generic_input_text("yourEmail", osc_logged_user_email(), null, false);
		}
		return true;
	}
	static public function your_phone_number() 
	{
		$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
		if ($session->_getForm("phoneNumber") != "") 
		{
			$phoneNumber = $session->_getForm("phoneNumber");
			parent::generic_input_text("phoneNumber", $phoneNumber, null, false);
		}
		else
		{
			parent::generic_input_text("phoneNumber", osc_logged_user_phone(), null, false);
		}
		return true;
	}
	static public function the_subject() 
	{
		$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
		if ($session->_getForm("subject") != "") 
		{
			$subject = $session->_getForm("subject");
			parent::generic_input_text("subject", $subject, null, false);
		}
		else
		{
			parent::generic_input_text("subject", "", null, false);
		}
		return true;
	}
	static public function your_message() 
	{
		$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
		if ($session->_getForm("message_body") != "") 
		{
			$message = $session->_getForm("message_body");
			parent::generic_textarea("message", $message);
		}
		else
		{
			parent::generic_textarea("message", "");
		}
		return true;
	}
	static public function your_attachment() 
	{
		echo '<input type="file" name="attachment" />';
	}
	static public function js_validation() 
	{
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
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
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                message: {
                    required: "<?php _e("Message: this field is required"); ?>.",
                    minlength: "<?php _e("Message: this field is required"); ?>."
                }
            },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });
</script>
<?php
	}
}
