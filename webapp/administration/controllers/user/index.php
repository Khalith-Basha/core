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

	function doModel() 
	{
		parent::doModel();
		//specific things for this class
		switch ($this->action) 
		{
		case 'edit': // calling the edit view
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
			$this->getView()->_exportVariableToView("user", $aUser);
			$this->getView()->_exportVariableToView("countries", $aCountries);
			$this->getView()->_exportVariableToView("regions", $aRegions);
			$this->getView()->_exportVariableToView("cities", $aCities);
			$this->getView()->_exportVariableToView("locales", ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled());
			$this->doView("users/frm.php");
			break;

		case 'edit_post': // edit post
			require_once 'osc/UserActions.php';
			$userActions = new UserActions(true);
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
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		case 'activate': //activate
			require_once 'osc/UserActions.php';
			$iUpdated = 0;
			$userId = Params::getParam('id');
			if (!is_array($userId)) 
			{
				osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
			}
			$userActions = new UserActions(true);
			foreach ($userId as $id) 
			{
				$iUpdated+= $userActions->activate($id);
			}
			switch ($iUpdated) 
			{
			case (0):
				$msg = _m('No user has been activated');
				break;

			case (1):
				$msg = _m('One user has been activated');
				break;

			default:
				$msg = sprintf(_m('%s users have been activated'), $iUpdated);
				break;
			}
			osc_add_flash_ok_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		case 'deactivate': //deactivate
			require_once 'osc/UserActions.php';
			$iUpdated = 0;
			$userId = Params::getParam('id');
			if (!is_array($userId)) 
			{
				osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
			}
			$userActions = new UserActions(true);
			foreach ($userId as $id) 
			{
				$iUpdated+= $userActions->deactivate($id);
			}
			switch ($iUpdated) 
			{
			case (0):
				$msg = _m('No user has been deactivated');
				break;

			case (1):
				$msg = _m('One user has been deactivated');
				break;

			default:
				$msg = sprintf(_m('%s users have been deactivated'), $iUpdated);
				break;
			}
			osc_add_flash_ok_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		case 'enable':
			require_once 'osc/UserActions.php';
			$iUpdated = 0;
			$userId = Params::getParam('id');
			if (!is_array($userId)) 
			{
				osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
			}
			$userActions = new UserActions(true);
			foreach ($userId as $id) 
			{
				$iUpdated+= $userActions->enable($id);
			}
			switch ($iUpdated) 
			{
			case (0):
				$msg = _m('No user has been enabled');
				break;

			case (1):
				$msg = _m('One user has been enabled');
				break;

			default:
				$msg = sprintf(_m('%s users have been enabled'), $iUpdated);
				break;
			}
			osc_add_flash_ok_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		case 'disable':
			require_once 'osc/UserActions.php';
			$iUpdated = 0;
			$userId = Params::getParam('id');
			if (!is_array($userId)) 
			{
				osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
			}
			$userActions = new UserActions(true);
			foreach ($userId as $id) 
			{
				$iUpdated+= $userActions->disable($id);
			}
			switch ($iUpdated) 
			{
			case (0):
				$msg = _m('No user has been disabled');
				break;

			case (1):
				$msg = _m('One user has been disabled');
				break;

			default:
				$msg = sprintf(_m('%s users have been disabled'), $iUpdated);
				break;
			}
			osc_add_flash_ok_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		case 'delete': //delete
			$iDeleted = 0;
			$userId = Params::getParam('id');
			if (!is_array($userId)) 
			{
				osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
			}
			foreach ($userId as $id) 
			{
				$user = $this->userManager->findByPrimaryKey($id);
				Log::newInstance()->insertLog('user', 'delete', $id, $user['s_email'], 'admin', osc_logged_admin_id());
				if ($this->userManager->deleteUser($id)) 
				{
					$iDeleted++;
				}
			}
			switch ($iDeleted) 
			{
			case (0):
				$msg = _m('No user has been deleted');
				break;

			case (1):
				$msg = _m('One user has been deleted');
				break;

			default:
				$msg = sprintf(_m('%s users have been deleted'), $iDeleted);
				break;
			}
			osc_add_flash_ok_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . '?page=users');
			break;

		default: // manage users view
			$aUsers = $this->userManager->listAll();
			$this->getView()->_exportVariableToView("users", $aUsers);
			$this->doView("users/index.php");
			break;
		}
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		$this->getSession()->_clearVariables();
	}
}
