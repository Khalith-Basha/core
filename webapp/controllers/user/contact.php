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
		if (!osc_users_enabled() && ($this->action != 'activate_alert' && $this->action != 'unsub_alert')) 
		{
			osc_add_flash_error_message(_m('Users not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$user = User::newInstance()->findByPrimaryKey(Params::getParam('id'));
		View::newInstance()->_exportVariableToView('user', $user);
		if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
		{
			if (!osc_check_recaptcha()) 
			{
				osc_add_flash_error_message(_m('The Recaptcha code is wrong'));
				Session::newInstance()->_setForm("yourEmail", Params::getParam('yourEmail'));
				Session::newInstance()->_setForm("yourName", Params::getParam('yourName'));
				Session::newInstance()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
				Session::newInstance()->_setForm("message_body", Params::getParam('message'));
				$this->redirectTo(osc_user_public_profile_url());
				return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG				
			}
		}
		osc_run_hook('hook_email_contact_user', Params::getParam('id'), Params::getParam('yourEmail'), Params::getParam('yourName'), Params::getParam('phoneNumber'), Params::getParam('message'));
		$this->redirectTo(osc_user_public_profile_url());
	}
}
