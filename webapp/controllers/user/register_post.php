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
		if (!osc_user_registration_enabled()) 
		{
			osc_add_flash_error_message(_m('User registration is not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}
	function doModel() 
	{
		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users are not enabled'));
			$this->redirectTo(osc_base_url());
		}
		osc_run_hook('before_user_register');
		require_once 'osc/UserActions.php';
		$userActions = new UserActions(false);
		$success = $userActions->add();
		switch ($success) 
		{
		case 1:
			osc_add_flash_ok_message(_m('The user has been created. An activation email has been sent'));
			$this->redirectTo(osc_base_url());
			break;

		case 2:
			osc_add_flash_ok_message(_m('Your account has been created successfully'));
			$this->doView('user-login.php');
			break;

		case 3:
			osc_add_flash_warning_message(_m('The specified e-mail is already in use'));
			$this->doView('user/register');
			break;

		case 4:
			osc_add_flash_error_message(_m('The reCAPTCHA was not introduced correctly'));
			$this->doView('user/register');
			break;

		case 5:
			osc_add_flash_warning_message(_m('The email is not valid'));
			$this->doView('user/register');
			break;

		case 6:
			osc_add_flash_warning_message(_m('The password cannot be empty'));
			$this->doView('user/register');
			break;

		case 7:
			osc_add_flash_warning_message(_m("Passwords don't match"));
			$this->doView('user/register');
			break;
		}
	}
	function doView($file) 
	{
		echo $this->getView()->render( $file );
	}
}

