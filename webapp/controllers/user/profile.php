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
			$this->getSession()->addFlashMessage( _m('Users not enabled'), 'ERROR' );
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$classLoader = ClassLoader::getInstance();
		$userModel = new \Osc\Model\User;
		$user = $userModel->findByPrimaryKey($this->getSession()->_get('userId'));

		$countryModel = new \Osc\Model\Country;
		$aCountries = $countryModel->listAll();
		$aRegions = array();

		$regionModel = new \Osc\Model\Region;
		$cityModel = new \Osc\Model\City;
		if ($user['fk_c_country_code'] != '') 
		{
			$aRegions = $regionModel->findByCountry($user['fk_c_country_code']);
		}
		elseif (count($aCountries) > 0) 
		{
			$aRegions = $regionModel->findByCountry($aCountries[0]['pk_c_code']);
		}
		$aCities = array();
		if ($user['fk_i_region_id'] != '') 
		{
			$aCities = $cityModel->findByRegion($user['fk_i_region_id']);
		}
		else if (count($aRegions) > 0) 
		{
			$aCities = $cityModel->findByRegion($aRegions[0]['pk_i_id']);
		}
		$classLoader->loadFile( 'helpers/locations' );
		$view = $this->getView();
		$this->getView()->assign('countries', $aCountries);
		$this->getView()->assign('regions', $aRegions);
		$this->getView()->assign('cities', $aCities);
		$this->getView()->assign('user', $user);
		$view->setTitle( __('Update my profile', 'modern') . ' - ' . osc_page_title() );
		echo $this->getView()->render( 'user/profile' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$userId = $this->getSession()->_get('userId');
		$userActions = $this->getClassLoader()
			->getClassInstance( 'Manager_User', false, array( false ) );
		$userUrls = $this->getClassLoader()
			->getClassInstance( 'Url_User' );
		$success = $userActions->edit($userId);
		$this->getSession()->addFlashMessage( _m('Your profile has been updated successfully') );
		$this->redirectTo( $userUrls->osc_user_profile_url() );
	}
}
