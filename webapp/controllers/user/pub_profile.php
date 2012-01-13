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
	function doModel() 
	{
		$userID = Params::getParam('id');
		$user = User::newInstance()->findByPrimaryKey($userID);
		// user doesn't exist
		if (!$user) 
		{
			$this->redirectTo(osc_base_url());
		}
		View::newInstance()->_exportVariableToView('user', $user);
		$items = Item::newInstance()->findByUserIDEnabled($user['pk_i_id'], 0, 3);
		View::newInstance()->_exportVariableToView('items', $items);
		$this->doView('user-public-profile.php');
	}

	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		Session::newInstance()->_clearVariables();
		osc_run_hook("after_html");
	}
}
