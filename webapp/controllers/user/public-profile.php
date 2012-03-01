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
class CWebUser extends Controller_Default
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
		$classLoader = $this->getClassLoader();
		$userID = Params::getParam('id');
		$user = $this->getClassLoader()->getClassInstance( 'Model_User' )->findByPrimaryKey($userID);
		// user doesn't exist
		if (!$user) 
		{
			$this->redirectToBaseUrl();
		}
		$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
		$classLoader->loadFile( 'helpers/security' );
		$view = $this->getView();
		$view->setTitle( $user['s_name'] );
		$this->getView()->addJavaScript( '/static/scrips/contact-form.js' );
		$this->getView()->assign( 'itemUrls', $itemUrls );
		$this->getView()->assign('user', $user);
		$items = $this->getClassLoader()->getClassInstance( 'Model_Item' )->findByUserIDEnabled($user['pk_i_id'], 0, 3);
		$this->getView()->assign('items', $items);
		echo $this->getView()->render('user/public-profile');
	}
}

