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
class CAdminLanguage extends AdministrationController
{
	private $localeManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->localeManager = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' );
	}
	function doModel() 
	{
		switch ($this->action) 
		{
		case 'edit': // editing a language
			$sLocale = Params::getParam('id');
			if (!preg_match('/.{2}_.{2}/', $sLocale)) 
			{
				osc_add_flash_error_message(_m('Language id isn\'t in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$aLocale = $this->localeManager->findByPrimaryKey($sLocale);
			if (count($aLocale) == 0) 
			{
				osc_add_flash_error_message(_m('Language id doesn\'t exist'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$this->getView()->assign("aLocale", $aLocale);
			$this->doView('languages/frm.php');
			break;

		case 'edit_post': // edit language post
			$iUpdated = 0;
			$languageCode = Params::getParam('pk_c_code');
			$enabledWebstie = Params::getParam('b_enabled');
			$enabledBackoffice = Params::getParam('b_enabled_bo');
			$languageName = Params::getParam('s_name');
			$languageShortName = Params::getParam('s_short_name');
			$languageDescription = Params::getParam('s_description');
			$languageCurrencyFormat = Params::getParam('s_currency_format');
			$languageDecPoint = Params::getParam('s_dec_point');
			$languageNumDec = Params::getParam('i_num_dec');
			$languageThousandsSep = Params::getParam('s_thousands_sep');
			$languageDateFormat = Params::getParam('s_date_format');
			$languageStopWords = Params::getParam('s_stop_words');
			// formatting variables
			if (!preg_match('/.{2}_.{2}/', $languageCode)) 
			{
				osc_add_flash_error_message(_m('Language id isn\'t in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$enabledWebstie = ($enabledWebstie != '' ? true : false);
			$enabledBackoffice = ($enabledBackoffice != '' ? true : false);
			$languageName = strip_tags($languageName);
			$languageName = trim($languageName);
			if ($languageName == '') 
			{
				osc_add_flash_error_message(_m('Language name can\'t be empty'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$languageShortName = strip_tags($languageShortName);
			$languageShortName = trim($languageShortName);
			if ($languageShortName == '') 
			{
				osc_add_flash_error_message(_m('Language short name can\'t be empty'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$languageDescription = strip_tags($languageDescription);
			$languageDescription = trim($languageDescription);
			if ($languageDescription == '') 
			{
				osc_add_flash_error_message(_m('Language description can\'t be empty'), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			}
			$languageCurrencyFormat = strip_tags($languageCurrencyFormat);
			$languageCurrencyFormat = trim($languageCurrencyFormat);
			$languageDateFormat = strip_tags($languageDateFormat);
			$languageDateFormat = trim($languageDateFormat);
			$languageStopWords = strip_tags($languageStopWords);
			$languageStopWords = trim($languageStopWords);
			$array = array('b_enabled' => $enabledWebstie, 'b_enabled_bo' => $enabledBackoffice, 's_name' => $languageName, 's_short_name' => $languageShortName, 's_description' => $languageDescription, 's_currency_format' => $languageCurrencyFormat, 's_dec_point' => $languageDecPoint, 'i_num_dec' => $languageNumDec, 's_thousands_sep' => $languageThousandsSep, 's_date_format' => $languageDateFormat, 's_stop_words' => $languageStopWords);
			$iUpdated = $this->localeManager->update($array, array('pk_c_code' => $languageCode));
			if ($iUpdated > 0) 
			{
				osc_add_flash_ok_message(sprintf(_m('%s has been updated'), $languageShortName), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'enable':
		case 'enable_bo':
			$default_lang = osc_language();
			$id = Params::getParam('id');
			$enabled = Params::getParam('enabled');
			if ($id) 
			{
				if ($action == 'enable' && $default_lang == $id && $enabled == 0) 
				{
					osc_add_flash_error_message(sprintf(_m('The language can\'t be disabled because it\'s the default language. You can change modify it in General Settings'), $i), 'admin');
				}
				else
				{
					$msg = ($enabled == 1) ? _m('The language has been enabled for the public website') : _m('The language has been disabled for the public website');
					$aValues = array('b_enabled' => $enabled);
					$this->localeManager->update($aValues, array('pk_c_code' => $id));
				}
				if ($action == 'enable_bo') 
				{
					$msg = ($enabled == 1) ? _m('The language has been enabled for the backoffice (administration)') : _m('The language has been disabled for the backoffice (oc-admin)');
					$aValues = array('b_enabled_bo' => $enabled);
					$this->localeManager->update($aValues, array('pk_c_code' => $id));
				}
				osc_add_flash_ok_message($msg, 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('There was a problem updating the language. The language id was lost'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'enable_selected':
			$msg = _m('Selected languages have been enabled for the website');
			$aValues = array('b_enabled' => 1);
			$id = Params::getParam('id');
			if ($id != '') 
			{;
				foreach ($id as $i) 
				{
					$this->localeManager->update($aValues, array('pk_c_code' => $i));
				}
				osc_add_flash_ok_message($msg, 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('There was a problem updating the languages. The language ids were lost'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'disable_selected':
			$msg = _m('Selected languages have been disabled for the website');
			$aValues = array('b_enabled' => 0);
			$id = Params::getParam('id');
			if ($id != '') 
			{
				$default_lang = osc_language();
				foreach ($id as $i) 
				{
					if ($default_lang == $i) 
					{
						$msg = _m('The language can\'t be disabled because it\'s the default language. You can change the default language under General Settings in order to disable it');
					}
					else
					{
						$this->localeManager->update($aValues, array('pk_c_code' => $i));
					}
				}
				osc_add_flash_ok_message($msg, 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('There was a problem updating the languages. The language ids were lost'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'enable_bo_selected':
			$msg = _m('Selected languages have been enabled for the backoffice (administration)');
			$aValues = array('b_enabled_bo' => 1);
			$id = Params::getParam('id');
			if ($id != '') 
			{
				foreach ($id as $i) 
				{
					$this->localeManager->update($aValues, array('pk_c_code' => $i));
				}
				osc_add_flash_ok_message($msg, 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('There was a problem updating the languages. The language ids were lost'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'disable_bo_selected':
			$msg = _m('Selected languages have been disabled for the backoffice (administration)');
			$aValues = array('b_enabled_bo' => 0);
			$id = Params::getParam('id');
			if ($id != '') 
			{
				foreach ($id as $i) 
				{
					$this->localeManager->update($aValues, array('pk_c_code' => $i));
				}
				osc_add_flash_ok_message($msg, 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('There was a problem updating the languages. The language ids were lost'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		case 'delete':
			if (is_array(Params::getParam('id'))) 
			{
				$default_lang = osc_language();
				foreach (Params::getParam('id') as $code) 
				{
					if ($default_lang != $code) 
					{
						$this->localeManager->deleteLocale($code);
						if (!osc_deleteDir(osc_translations_path() . $code)) 
						{
							osc_add_flash_error_message(sprintf(_m('Directory "%s" couldn\'t be removed'), $code), 'admin');
						}
						else
						{
							osc_add_flash_ok_message(sprintf(_m('Directory "%s" has been successfully removed'), $code), 'admin');
						}
					}
					else
					{
						osc_add_flash_error_message(sprintf(_m('Directory "%s" couldn\'t be removed because it\'s the default language. Set another language as default first and try again'), $code), 'admin');
					}
				}
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
			break;

		default:
			$locales = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAll();
			$this->getView()->assign("locales", $locales);
			$this->doView('languages/index.php');
			break;
		}
	}
}

