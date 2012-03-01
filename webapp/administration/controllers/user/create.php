<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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
		$aCountries = array();
		$aRegions = array();
		$aCities = array();
		$aCountries = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->listAll();
		if (isset($aCountries[0]['pk_c_code'])) 
		{
			$aRegions = Region::newInstance()->findByCountry($aCountries[0]['pk_c_code']);
		}
		if (isset($aRegions[0]['pk_i_id'])) 
		{
			$aCities = City::newInstance()->findByRegion($aRegions[0]['pk_i_id']);
		}
		$this->getView()->assign('user', null);
		$this->getView()->assign('countries', $aCountries);
		$this->getView()->assign('regions', $aRegions);
		$this->getView()->assign('cities', $aCities);
		$this->getView()->assign('locales', ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled());
		$this->doView("users/frm.php");
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$userActions = $this->getClassLoader()->getClassInstance( 'Manager_User', false, array( true ) );
		$success = $userActions->add();
		switch ($success) 
		{
		case 1:
			osc_add_flash_ok_message(_m('The user has been created. We\'ve sent an activation e-mail'), 'admin');
			break;

		case 2:
			osc_add_flash_ok_message(_m('The user has been created successfully'), 'admin');
			break;

		case 3:
			osc_add_flash_warning_message(_m('Sorry, but that e-mail is already in use'), 'admin');
			break;

		case 5:
			osc_add_flash_warning_message(_m('The specified e-mail is not valid'), 'admin');
			break;

		case 6:
			osc_add_flash_warning_message(_m('Sorry, the password cannot be empty'), 'admin');
			break;

		case 7:
			osc_add_flash_warning_message(_m("Sorry, passwords don't match"), 'admin');
			break;
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=user');
	}
}

