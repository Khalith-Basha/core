<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
class CWebContact extends Controller_Cacheable
{
	public function getCacheKey()
	{
		return 'page-contact';
	}

	public function getCacheExpiration()
	{
		return 10800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res ) 
	{
		$this->getClassLoader()->loadFile( 'helpers/security' );
		$view = $this->getView();
		$view->setTitle( __('Contact', 'modern') . ' - ' . osc_page_title() );
		$view->addJavaScript( osc_current_web_theme_js_url('jquery.validate.min.js') );
		$view->addJavaScript( '/static/scripts/contact-form.js' );
		return $view->render( 'contact' );
	}

	public function doPost(HttpRequest $req, HttpResponse $resp) 
	{
		$indexUrls = $this->getClassLoader()->getClassInstance( 'Url_Index' );
		$yourName = Params::getParam('yourName');
		$yourEmail = Params::getParam('yourEmail');
		$subject = Params::getParam('subject');
		$message = Params::getParam('message');
		if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
		{
			if (!osc_check_recaptcha()) 
			{
				osc_add_flash_error_message(_m('The Recaptcha code is wrong'));
				$this->getSession()->_setForm("yourName", $yourName);
				$this->getSession()->_setForm("yourEmail", $yourEmail);
				$this->getSession()->_setForm("subject", $subject);
				$this->getSession()->_setForm("message_body", $message);
				$this->redirectTo( $indexUrls->osc_contact_url());
				return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
				
			}
		}
		if (!preg_match('|.*?@.{2,}\..{2,}|', $yourEmail)) 
		{
			osc_add_flash_error_message(_m('You have to introduce a correct e-mail'));
			$this->getSession()->_setForm("yourName", $yourName);
			$this->getSession()->_setForm("subject", $subject);
			$this->getSession()->_setForm("message_body", $message);
			$this->redirectTo( $indexUrls->osc_contact_url());
		}
		$params = array('from' => $yourEmail, 'from_name' => $yourName, 'subject' => '[' . osc_page_title() . '] ' . __('Contact form') . ': ' . $subject, 'to' => osc_contact_email(), 'to_name' => __('Administrator'), 'body' => $message, 'alt_body' => $message);
		if (osc_contact_attachment()) 
		{
			$attachment = Params::getFiles('attachment');
			$resourceName = $attachment['name'];
			$tmpName = $attachment['tmp_name'];
			$resourceType = $attachment['type'];
			$path = osc_content_path() . '/uploads/' . time() . '_' . $resourceName;
			if (!is_writable(osc_content_path() . '/uploads/')) 
			{
				osc_add_flash_error_message(_m('There has been some errors sending the message'));
				$this->redirectToBaseUrl();
			}
			if (!move_uploaded_file($tmpName, $path)) 
			{
				unset($path);
			}
		}
		if (isset($path)) 
		{
			$params['attachment'] = $path;
		}

		try
		{
			osc_sendMail($params);
			osc_add_flash_ok_message(_m('Your e-mail has been sent properly. Thank your for contacting us!'));
		}
		catch( Exception $e )
		{
			osc_add_flash_ok_message(_m('There was a problem trying to send contact form.'));
		}
		$this->redirectToBaseUrl();
	}
}

