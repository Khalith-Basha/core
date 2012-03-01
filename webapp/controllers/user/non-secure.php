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
class CWebUserNonSecure extends Controller
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

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		switch ($this->action) 
		{
		case 'change_email_confirm': //change email confirm
			if (Params::getParam('userId') && Params::getParam('code')) 
			{
				$userManager = new User();
				$user = $userManager->findByPrimaryKey(Params::getParam('userId'));
				if ($user['s_pass_code'] == Params::getParam('code') && $user['b_enabled'] == 1) 
				{
					$userEmailTmp = UserEmailTmp::newInstance()->findByPk(Params::getParam('userId'));
					$code = osc_genRandomPassword(50);
					$userManager->update(array('s_email' => $userEmailTmp['s_new_email']), array('pk_i_id' => $userEmailTmp['fk_i_user_id']));
					ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->update(array('s_contact_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
					ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->update(array('s_author_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
					Alerts::newInstance()->update(array('s_email' => $userEmailTmp['s_new_email']), array('fk_i_user_id' => $userEmailTmp['fk_i_user_id']));
					$this->getSession()->_set('userEmail', $userEmailTmp['s_new_email']);
					UserEmailTmp::newInstance()->delete(array('s_new_email' => $userEmailTmp['s_new_email']));
					osc_add_flash_ok_message(_m('Your email has been changed successfully'));
					$this->redirectTo(osc_user_profile_url());
				}
				else
				{
					osc_add_flash_error_message(_m('Sorry, the link is not valid'));
					$this->redirectToBaseUrl();
				}
			}
			else
			{
				osc_add_flash_error_message(_m('Sorry, the link is not valid'));
				$this->redirectToBaseUrl();
			}
			break;

		case 'activate_alert':
			$email = Params::getParam('email');
			$secret = Params::getParam('secret');
			$result = 0;
			if ($email != '' && $secret != '') 
			{
				$result = Alerts::newInstance()->activate($email, $secret);
			}
			if ($result == 1) 
			{
				osc_add_flash_ok_message(_m('Alert activated'));
			}
			else
			{
				osc_add_flash_error_message(_m('Ops! There was a problem trying to activate alert. Please contact the administrator'));
			}
			$this->redirectTo(osc_base_url(true));
			break;

		case 'unsub_alert':
			$email = Params::getParam('email');
			$secret = Params::getParam('secret');
			if ($email != '' && $secret != '') 
			{
				Alerts::newInstance()->delete(array('s_email' => $email, 'S_secret' => $secret));
				osc_add_flash_ok_message(_m('Unsubscribed correctly'));
			}
			else
			{
				osc_add_flash_error_message(_m('Ops! There was a problem trying to unsubscribe you. Please contact the administrator'));
			}
			$this->redirectToBaseUrl();
			break;

		default:
			$this->redirectTo(osc_user_login_url());
			break;
		}
	}
}
