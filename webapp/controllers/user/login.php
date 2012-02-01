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

class CWebUser extends Controller_Cacheable
{
	public function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function getCacheKey()
	{
		return 'page-user-login';
	}

	public function getCacheExpiration()
	{
		return 10800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res )
	{
		if (osc_logged_user_id() != '') 
		{
			$this->redirectTo(osc_user_dashboard_url());
		}

		$view = $this->getView();
		$view->setTitle( __('Login', 'modern') . ' - ' . osc_page_title() );
		return $view->render( 'user/login' );
	}
}

