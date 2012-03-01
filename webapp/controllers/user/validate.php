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
class CWebRegister extends Controller
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

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = intval(Params::getParam('id'));
		$code = Params::getParam('code');
		$userManager = new User();
		$user = $userManager->findByIdSecret($id, $code);
		if (!$user) 
		{
			osc_add_flash_error_message(_m('The link is not valid anymore. Sorry for the inconvenience!'));
			$this->redirectToBaseUrl();
		}
		if ($user['b_active'] == 1) 
		{
			osc_add_flash_error_message(_m('Your account has already been validated'));
			$this->redirectToBaseUrl();
		}
		$userManager = new User();
		$userManager->update(array('b_active' => '1'), array('pk_i_id' => $id, 's_secret' => $code));
		osc_run_hook('hook_email_user_registration', $user);
		osc_run_hook('validate_user', $user);
		// Auto-login
		$this->getSession()->_set('userId', $user['pk_i_id']);
		$this->getSession()->_set('userName', $user['s_name']);
		$this->getSession()->_set('userEmail', $user['s_email']);
		$phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
		$this->getSession()->_set('userPhone', $phone);
		osc_add_flash_ok_message(_m('Your account has been validated'));
		$this->redirectToBaseUrl();
	}
}

