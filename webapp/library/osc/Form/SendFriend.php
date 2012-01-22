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
class Form_SendFriend extends Form
{
	public function your_name() 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("yourName") != "") 
		{
			$yourName = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("yourName");
			parent::generic_input_text("yourName", $yourName, null, false);
		}
		else
		{
			parent::generic_input_text("yourName", "", null, false);
		}
		return true;
	}
	public function your_email() 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("yourEmail") != "") 
		{
			$yourEmail = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("yourEmail");
			parent::generic_input_text("yourEmail", $yourEmail, null, false);
		}
		else
		{
			parent::generic_input_text("yourEmail", "", null, false);
		}
		return true;
	}
	public function friend_name() 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("friendName") != "") 
		{
			$friendName = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("friendName");
			parent::generic_input_text("friendName", $friendName, null, false);
		}
		else
		{
			parent::generic_input_text("friendName", "", null, false);
		}
		return true;
	}
	public function friend_email() 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("friendEmail") != "") 
		{
			$friendEmail = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("friendEmail");
			parent::generic_input_text("friendEmail", $friendEmail, null, false);
		}
		else
		{
			parent::generic_input_text("friendEmail", "", null, false);
		}
		return true;
	}
	public function your_message() 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("message_body") != "") 
		{
			$message_body = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm("message_body");
			parent::generic_textarea("message", $message_body, null, false);
		}
		else
		{
			parent::generic_textarea("message", "");
		}
		return true;
	}
	public function js_validation() 
	{
?>
<script type="text/javascript">
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
                    required: "<?php
		_e("Your name: this field is required"); ?>."
                },
                yourEmail: {
                    email: "<?php
		_e("Invalid email address"); ?>.",
                    required: "<?php
		_e("Email: this field is required"); ?>."
                },
                friendName: {
                    required: "<?php
		_e("Friend's name: this field is required"); ?>."
                },
                friendEmail: {
                    required: "<?php
		_e("Friend's email: this field is required"); ?>.",
                    email: "<?php
		_e("Invalid friend's email address"); ?>."
                },
                message: "<?php
		_e("Message: this field is required"); ?>."
                
            },
            //onfocusout: function(element) { $(element).valid(); },
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
