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
	public function doModel() 
	{
		$classLoader = ClassLoader::getInstance();
		$aAlerts = $classLoader->getClassInstance( 'Model_Alerts' )->findByUser($this->getSession()->_get('userId'));
		$user = $classLoader->getClassInstance( 'Model_User' )->findByPrimaryKey($this->getSession()->_get('userId'));
		foreach ($aAlerts as $k => $a) 
		{
			$search = osc_unserialize(base64_decode($a['s_search']));
			$search->limit(0, 3);
			$aAlerts[$k]['items'] = $search->doSearch();
		}

		$view = $this->getView();
		$view->assign('alerts', $aAlerts);
		$view->_reset('alerts');
		$view->assign('user', $user);
		$view->setTitle( __('Manage my alerts', 'modern') . ' - ' . osc_page_title() );
		echo $view->render( 'user/alerts' );
		$this->getSession()->_clearVariables();
	}
}

