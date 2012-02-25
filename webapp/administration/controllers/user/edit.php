<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class CAdminUser extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$this->userManager = ClassLoader::getInstance()->getClassInstance( 'Model_User' );
		$aUser = array();
		$aCountries = array();
		$aRegions = array();
		$aCities = array();
		$aUser = $this->userManager->findByPrimaryKey(Params::getParam("id"));
		$aCountries = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->listAll();
		$aRegions = array();
		if ($aUser['fk_c_country_code'] != '') 
		{
			$aRegions = Region::newInstance()->findByCountry($aUser['fk_c_country_code']);
		}
		else if (count($aCountries) > 0) 
		{
			$aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']);
		}
		$aCities = array();
		if ($aUser['fk_i_region_id'] != '') 
		{
			$aCities = City::newInstance()->findByRegion($aUser['fk_i_region_id']);
		}
		else if (count($aRegions) > 0) 
		{
			$aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
		}
		$this->getView()->assign("user", $aUser);
		$this->getView()->assign("countries", $aCountries);
		$this->getView()->assign("regions", $aRegions);
		$this->getView()->assign("cities", $aCities);
		$this->getView()->assign("locales", ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled());
		$this->doView("users/frm.php");
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$userActions = $this->getClassLoader()->getClassInstance( 'Manager_User', false, array( true ) );
		$success = $userActions->edit(Params::getParam("id"));
		switch ($success) 
		{
		case (1):
			osc_add_flash_error_message(_m('Passwords don\'t match'), 'admin');
			break;

		case (2):
			osc_add_flash_ok_message(_m('The user has been updated and activated'), 'admin');
			break;

		default:
			osc_add_flash_ok_message(_m('The user has been updated'), 'admin');
			break;
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=user');
	}
}

