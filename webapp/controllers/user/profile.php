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
class CWebUser extends WebSecBaseModel
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
		$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($this->getSession()->_get('userId'));
		$aCountries = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->listAll();
		$aRegions = array();
		if ($user['fk_c_country_code'] != '') 
		{
			$aRegions = Region::newInstance()->findByCountry($user['fk_c_country_code']);
		}
		elseif (count($aCountries) > 0) 
		{
			$aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']);
		}
		$aCities = array();
		if ($user['fk_i_region_id'] != '') 
		{
			$aCities = City::newInstance()->findByRegion($user['fk_i_region_id']);
		}
		else if (count($aRegions) > 0) 
		{
			$aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
		}
		//calling the view...
		$this->getView()->_exportVariableToView('countries', $aCountries);
		$this->getView()->_exportVariableToView('regions', $aRegions);
		$this->getView()->_exportVariableToView('cities', $aCities);
		$this->getView()->_exportVariableToView('user', $user);
		$this->doView('user/profile.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$userId = $this->getSession()->_get('userId');
		require_once 'osc/UserActions.php';
		$userActions = new UserActions(false);
		$success = $userActions->edit($userId);
		osc_add_flash_ok_message(_m('Your profile has been updated successfully'));
		$this->redirectTo(osc_user_profile_url());
	}
	
	public function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		$this->getSession()->_clearVariables();
		osc_run_hook("after_html");
	}
}
