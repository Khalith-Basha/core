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
class CWebUser extends UserController
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
		echo $this->getView()->render( 'user/change-password' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($this->getSession()->_get('userId'));
		if ((Params::getParam('password') == '') || (Params::getParam('new_password') == '') || (Params::getParam('new_password2') == '')) 
		{
			osc_add_flash_warning_message(_m('Password cannot be blank'));
			$this->redirectTo(osc_change_user_password_url());
		}
		if ($user['s_password'] != sha1(Params::getParam('password'))) 
		{
			osc_add_flash_error_message(_m('Current password doesn\'t match'));
			$this->redirectTo(osc_change_user_password_url());
		}
		if (!Params::getParam('new_password')) 
		{
			osc_add_flash_error_message(_m('Passwords can\'t be empty'));
			$this->redirectTo(osc_change_user_password_url());
		}
		if (Params::getParam('new_password') != Params::getParam('new_password2')) 
		{
			osc_add_flash_error_message(_m('Passwords don\'t match'));
			$this->redirectTo(osc_change_user_password_url());
		}
		ClassLoader::getInstance()->getClassInstance( 'Model_User' )->update(array('s_password' => sha1(Params::getParam('new_password'))), array('pk_i_id' => $this->getSession()->_get('userId')));
		osc_add_flash_ok_message(_m('Password has been changed'));
		$this->redirectTo(osc_user_profile_url());
	}
}

