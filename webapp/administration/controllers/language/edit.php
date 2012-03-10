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
class CAdminLanguage extends Controller_Administration
{
	private $localeManager;

	public function __construct() 
	{
		parent::__construct();
		$this->localeManager = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' );
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$sLocale = Params::getParam('id');
		if (!preg_match('/.{2}_.{2}/', $sLocale)) 
		{
			$this->getSession()->addFlashMessage( _m('Language id isn\'t in the correct format'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
		}
		$aLocale = $this->localeManager->findByPrimaryKey($sLocale);
		if (count($aLocale) == 0) 
		{
			$this->getSession()->addFlashMessage( _m('Language id doesn\'t exist'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
		}
		$this->getView()->assign("aLocale", $aLocale);
		$this->doView('languages/frm.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
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
			$this->getSession()->addFlashMessage( _m('Language id isn\'t in the correct format'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
		}
		$enabledWebstie = ($enabledWebstie != '' ? true : false);
		$enabledBackoffice = ($enabledBackoffice != '' ? true : false);
		$languageName = strip_tags($languageName);
		$languageName = trim($languageName);
		if ($languageName == '') 
		{
			$this->getSession()->addFlashMessage( _m('Language name can\'t be empty'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
		}
		$languageShortName = strip_tags($languageShortName);
		$languageShortName = trim($languageShortName);
		if ($languageShortName == '') 
		{
			$this->getSession()->addFlashMessage( _m('Language short name can\'t be empty'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=language');
		}
		$languageDescription = strip_tags($languageDescription);
		$languageDescription = trim($languageDescription);
		if ($languageDescription == '') 
		{
			$this->getSession()->addFlashMessage( _m('Language description can\'t be empty'), 'admin', 'ERROR' );
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
			$this->getSession()->addFlashMessage( sprintf(_m('%s has been updated'), $languageShortName), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=language');
	}
}

