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

class CWebUser extends Controller_Default
{
	function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			$this->getSession()->addFlashMessage( _m('Users not enabled'), 'ERROR' );
			$this->redirectTo(osc_base_url(true));
		}
		if (!osc_user_registration_enabled()) 
		{
			$this->getSession()->addFlashMessage( _m('User registration is not enabled'), 'ERROR' );
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		if (!osc_users_enabled()) 
		{
			$this->getSession()->addFlashMessage( _m('Users are not enabled'), 'ERROR' );
			$this->redirectToBaseUrl();
		}
		osc_run_hook('before_user_register');
		$userActions = $this->getClassLoader()->getClassInstance( 'Manager_User', false, array( false ) );
		$success = $userActions->add();
		$session = $this->getSession();
		switch ($success) 
		{
		case 1:
			$session->addFlashMessage(_m('The user has been created. An activation email has been sent'));
			$this->redirectToBaseUrl();
			break;

		case 2:
			$session->addFlashMessage(_m('Your account has been created successfully'));
			$template = 'user-login.php';
			break;

		case 3:
			$this->getSession()->addFlashMessage( _m('The specified e-mail is already in use'), 'WARNING' );
			$template = 'user/register';
			break;

		case 4:
			$this->getSession()->addFlashMessage( _m('The reCAPTCHA was not introduced correctly'), 'ERROR' );
			$template = 'user/register';
			break;

		case 5:
			$this->getSession()->addFlashMessage( _m('The email is not valid'), 'WARNING' );
			$template = 'user/register';
			break;

		case 6:
			$this->getSession()->addFlashMessage( _m('The password cannot be empty'), 'WARNING' );
			$template = 'user/register';
			break;

		case 7:
			$session->addFlashMessage(_m("Passwords don't match"), 'WARNING' );
			$template = 'user/register';
			break;
		}
		
		echo $this->getView()->render( $template );
	}
}

