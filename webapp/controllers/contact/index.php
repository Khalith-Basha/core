<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
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
				$this->getSession()->addFlashMessage( _m('The Recaptcha code is wrong'), 'ERROR' );
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
			$this->getSession()->addFlashMessage( _m('You have to introduce a correct e-mail'), 'ERROR' );
			$this->getSession()->_setForm("yourName", $yourName);
			$this->getSession()->_setForm("subject", $subject);
			$this->getSession()->_setForm("message_body", $message);
			$this->redirectTo( $indexUrls->osc_contact_url());
		}

		if (osc_contact_attachment()) 
		{
			$attachment = Params::getFiles('attachment');
			$resourceName = $attachment['name'];
			$tmpName = $attachment['tmp_name'];
			$resourceType = $attachment['type'];
			$path = osc_content_path() . '/uploads/' . time() . '_' . $resourceName;
			if (!is_writable(osc_content_path() . '/uploads/')) 
			{
				$this->getSession()->addFlashMessage( _m('There has been some errors sending the message'), 'ERROR' );
				$this->redirectToBaseUrl();
			}
			if (!move_uploaded_file($tmpName, $path)) 
			{
				unset($path);
			}
		}
		if (isset($path)) 
		{
			$params = array( 'attachment' => $path );
		}

		$emailSubject = '[' . osc_page_title() . '] ' . __('Contact form') . ': ' . $subject;

		try
		{
			$emailConfig = array(
				'smtp_host' => osc_mailserver_host(),
				'smtp_port' => osc_mailserver_port(),
				'smtp_user' => osc_mailserver_username(),
				'smtp_pass' => osc_mailserver_password(),
			);

			$email = new \Cuore\Email\Message;
			$email->setFrom( $yourEmail, $yourName );
			$email->setSubject( $emailSubject );
			$email->addRecipient( osc_contact_email(), __('Administrator') );
			$email->setBody( $message );
			$email->send( $emailConfig );

			$this->getSession()->addFlashMessage( _m('Your e-mail has been sent properly. Thank your for contacting us!') );
		}
		catch( \Exception $e )
		{
			trigger_error( $e->getMessage(), E_USER_WARNING );

			$this->getSession()->addFlashMessage( _m('There was a problem trying to send contact form.') );
		}
		$this->redirectToBaseUrl();
	}
}

