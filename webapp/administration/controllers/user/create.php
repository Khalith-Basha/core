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
class CAdminUser extends AdminSecBaseModel
{
	private $userManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->userManager = ClassLoader::getInstance()->getClassInstance( 'Model_User' );
	}

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
		$this->getView()->_exportVariableToView('user', null);
		$this->getView()->_exportVariableToView('countries', $aCountries);
		$this->getView()->_exportVariableToView('regions', $aRegions);
		$this->getView()->_exportVariableToView('cities', $aCities);
		$this->getView()->_exportVariableToView('locales', ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled());
		$this->doView("users/frm.php");
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		require_once 'osc/UserActions.php';
		$userActions = new UserActions(true);
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
		$this->redirectTo(osc_admin_base_url(true) . '?page=users');
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		$this->getSession()->_clearVariables();
	}
}
