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
class CAdminSettings extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$currencies_action = Params::getParam('type');
		switch ($currencies_action) 
		{
		case ('add'): // calling add currency view
			$this->doView('settings/add_currency.php');
			break;

		case ('add_post'): // adding a new currency
			$currencyCode = Params::getParam('pk_c_code');
			$currencyName = Params::getParam('s_name');
			$currencyDescription = Params::getParam('s_description');
			// cleaning parameters
			$currencyName = strip_tags($currencyName);
			$currencyDescription = strip_tags($currencyDescription);
			$currencyCode = strip_tags($currencyCode);
			$currencyCode = trim($currencyCode);
			if (!preg_match('/^.{1,3}$/', $currencyCode)) 
			{
				osc_add_flash_error_message(_m('Error: the currency code is not in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			}
			$fields = array('pk_c_code' => $currencyCode, 's_name' => $currencyName, 's_description' => $currencyDescription);
			$isInserted = ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->insert($fields);
			if ($isInserted) 
			{
				osc_add_flash_ok_message(_m('New currency has been added'), 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('Error: currency couldn\'t be added'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			break;

		case ('edit'): // calling edit currency view
			$currencyCode = Params::getParam('code');
			$currencyCode = strip_tags($currencyCode);
			$currencyCode = trim($currencyCode);
			if ($currencyCode == '') 
			{
				osc_add_flash_error_message(_m('Error: the currency code is not in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			}
			$aCurrency = ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->findByPrimaryKey($currencyCode);
			if (count($aCurrency) == 0) 
			{
				osc_add_flash_error_message(_m('Error: the currency doesn\'t exist'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			}
			$this->getView()->assign('aCurrency', $aCurrency);
			$this->doView('settings/edit_currency.php');
			break;

		case ('edit_post'): // updating currency
			$currencyName = Params::getParam('s_name');
			$currencyDescription = Params::getParam('s_description');
			$currencyCode = Params::getParam('pk_c_code');
			// cleaning parameters
			$currencyName = strip_tags($currencyName);
			$currencyDescription = strip_tags($currencyDescription);
			$currencyCode = strip_tags($currencyCode);
			$currencyCode = trim($currencyCode);
			if (!preg_match('/.{1,3}/', $currencyCode)) 
			{
				osc_add_flash_error_message(_m('Error: the currency code is not in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			}
			$iUpdated = ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->update(array('s_name' => $currencyName, 's_description' => $currencyDescription), array('pk_c_code' => $currencyCode));
			if ($iUpdated == 1) 
			{
				osc_add_flash_ok_message(_m('Currency has been updated'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			break;

		case ('delete'): // deleting a currency
			$rowChanged = 0;
			$aCurrencyCode = Params::getParam('code');
			if (!is_array($aCurrencyCode)) 
			{
				osc_add_flash_error_message(_m('Error: the currency code is not in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			}
			$msg_current = '';
			foreach ($aCurrencyCode as $currencyCode) 
			{
				if (preg_match('/.{1,3}/', $currencyCode) && $currencyCode != osc_currency()) 
				{
					$rowChanged+= ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->delete(array('pk_c_code' => $currencyCode));
				}
				if ($currencyCode == osc_currency()) 
				{
					$msg_current = sprintf('. ' . _m("%s could not be deleted because it's the default currency"), $currencyCode);
				}
			}
			$msg = '';
			switch ($rowChanged) 
			{
			case ('0'):
				$msg = _m('No currencies have been deleted');
				osc_add_flash_error_message($msg . $msg_current, 'admin');
				break;

			case ('1'):
				$msg = _m('One currency has been deleted');
				osc_add_flash_ok_message($msg . $msg_current, 'admin');
				break;

			case ('-1'):
				$msg = sprintf(_m("%s could not be deleted because this currency still in use"), $currencyCode);
				osc_add_flash_error_message($msg . $msg_current, 'admin');
				break;

			default:
				$msg = sprintf(_m('%s currencies have been deleted'), $rowChanged);
				osc_add_flash_ok_message($msg . $msg_current, 'admin');
				break;
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
			break;

		default: // calling the currencies view
			$aCurrencies = ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->listAll();
			$this->getView()->assign('aCurrencies', $aCurrencies);
			$this->doView('settings/currencies.php');
			break;
		}
	}
}
