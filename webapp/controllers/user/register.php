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
		osc_run_hook('before_html');
		osc_current_web_theme_path( 'user/register.php' );
		Session::newInstance()->_clearVariables();
		osc_run_hook('after_html');
	}
}

