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
class CWebUser extends Controller_User
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
		$view = $this->getView();
		$view->setTitle( __('Change my email', 'modern') . ' - ' . osc_page_title() );
		echo $view->render( 'user/change-email' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		if (!preg_match("/^[_a-z0-9-\+]+(\.[_a-z0-9-\+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", Params::getParam('new_email'))) 
		{
			osc_add_flash_error_message(_m('The specified e-mail is not valid'));
			$this->redirectTo(osc_change_user_email_url());
		}
		else
		{
			$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByEmail(Params::getParam('new_email'));
			if (!isset($user['pk_i_id'])) 
			{
				$userEmailTmp = array();
				$userEmailTmp['fk_i_user_id'] = $this->getSession()->_get('userId');
				$userEmailTmp['s_new_email'] = Params::getParam('new_email');
				UserEmailTmp::newInstance()->insertOrUpdate($userEmailTmp);
				$code = osc_genRandomPassword(30);
				$date = date('Y-m-d H:i:s');
				$userManager = new User();
				$userManager->update(array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR']), array('pk_i_id' => $this->getSession()->_get('userId')));
				$validation_url = osc_change_user_email_confirm_url($this->getSession()->_get('userId'), $code);
				osc_run_hook('hook_email_new_email', Params::getParam('new_email'), $validation_url);
				$this->redirectTo(osc_user_profile_url());
			}
			else
			{
				osc_add_flash_error_message(_m('The specified e-mail is already in use'));
				$this->redirectTo(osc_change_user_email_url());
			}
		}
	}
}

