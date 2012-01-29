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
class CWebUser extends Controller
{
	function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		echo $this->getView()->render( 'user/recover' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		require_once 'osc/UserActions.php';

		$email = $this->getInput()->getString( 's_email' );
		if (!preg_match('|^[a-z0-9\.\_\+\-]+@[a-z0-9\.\-]+\.[a-z]{2,3}$|i', $email )) 
		{
			osc_add_flash_error_message(_m('Invalid email address'));
			$this->redirectTo(osc_recover_user_password_url());
		}
		$userActions = new UserActions(false);
		$success = $userActions->recover_password();
		switch ($success) 
		{
		case (0): // recover ok
			osc_add_flash_ok_message(_m('We have sent you an email with the instructions to reset your password'));
			$this->redirectTo(osc_base_url());
			break;

		case (1): // e-mail does not exist
			osc_add_flash_error_message(_m('We were not able to identify you given the information provided'));
			$this->redirectTo(osc_recover_user_password_url());
			break;

		case (2): // recaptcha wrong
			osc_add_flash_error_message(_m('The recaptcha code is wrong'));
			$this->redirectTo(osc_recover_user_password_url());
			break;
		}
	}
}

