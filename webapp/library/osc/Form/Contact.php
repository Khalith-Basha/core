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
	public function primary_input_hidden() 
	{
		parent::generic_input_hidden("id", osc_item_id());
	}
	public function page_hidden() 
	{
		parent::generic_input_hidden("page", 'item');
	}
	public function action_hidden() 
	{
		parent::generic_input_hidden("action", 'contact');
	}
	public function your_name() 
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
	}
	public function your_email() 
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
	}
	public function your_phone_number() 
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
	}
	public function the_subject() 
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
	}
	public function your_message() 
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
	}
	public function your_attachment() 
	{
		echo '<input type="file" name="attachment" />';
	}
}

