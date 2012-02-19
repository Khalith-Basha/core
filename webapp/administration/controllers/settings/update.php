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
		$preference = $this->getClassLoader()->getClassInstance( 'Model_Preference' );
		$iUpdated = 0;
		$sPageTitle = Params::getParam('pageTitle');
		$sPageDesc = Params::getParam('pageDesc');
		$sContactEmail = Params::getParam('contactEmail');
		$sLanguage = Params::getParam('language');
		$sDateFormat = Params::getParam('dateFormat');
		$sCurrency = Params::getParam('currency');
		$sWeekStart = Params::getParam('weekStart');
		$sTimeFormat = Params::getParam('timeFormat');
		$sTimezone = Params::getParam('timezone');
		$sNumRssItems = Params::getParam('num_rss_items');
		$maxLatestItems = Params::getParam('max_latest_items_at_home');
		$numItemsSearch = Params::getParam('default_results_per_page');
		// preparing parameters
		$sPageTitle = strip_tags($sPageTitle);
		$sPageDesc = strip_tags($sPageDesc);
		$sContactEmail = strip_tags($sContactEmail);
		$sLanguage = strip_tags($sLanguage);
		$sDateFormat = strip_tags($sDateFormat);
		$sCurrency = strip_tags($sCurrency);
		$sWeekStart = strip_tags($sWeekStart);
		$sTimeFormat = strip_tags($sTimeFormat);
		$sNumRssItems = (int)strip_tags($sNumRssItems);
		$maxLatestItems = (int)strip_tags($maxLatestItems);
		$numItemsSearch = (int)$numItemsSearch;
		$error = "";
		$iUpdated+= $preference->update(array('s_value' => $sPageTitle), array('s_section' => 'osclass', 's_name' => 'pageTitle'));
		$iUpdated+= $preference->update(array('s_value' => $sPageDesc), array('s_section' => 'osclass', 's_name' => 'pageDesc'));
		$iUpdated+= $preference->update(array('s_value' => $sContactEmail), array('s_section' => 'osclass', 's_name' => 'contactEmail'));
		$iUpdated+= $preference->update(array('s_value' => $sLanguage), array('s_section' => 'osclass', 's_name' => 'language'));
		$iUpdated+= $preference->update(array('s_value' => $sDateFormat), array('s_section' => 'osclass', 's_name' => 'dateFormat'));
		$iUpdated+= $preference->update(array('s_value' => $sCurrency), array('s_section' => 'osclass', 's_name' => 'currency'));
		$iUpdated+= $preference->update(array('s_value' => $sWeekStart), array('s_section' => 'osclass', 's_name' => 'weekStart'));
		$iUpdated+= $preference->update(array('s_value' => $sTimeFormat), array('s_section' => 'osclass', 's_name' => 'timeFormat'));
		$iUpdated+= $preference->update(array('s_value' => $sTimezone), array('s_section' => 'osclass', 's_name' => 'timezone'));
		if (is_int($sNumRssItems)) 
		{
			$iUpdated+= $preference->update(array('s_value' => $sNumRssItems), array('s_section' => 'osclass', 's_name' => 'num_rss_items'));
		}
		else
		{
			if ($error != '') $error.= "<br/>";
			$error.= _m('Number of items in the RSS must be integer');
		}
		if (is_int($maxLatestItems)) 
		{
			$iUpdated+= $preference->update(array('s_value' => $maxLatestItems), array('s_section' => 'osclass', 's_name' => 'maxLatestItems@home'));
		}
		else
		{
			if ($error != '') $error.= "<br/>";
			$error.= _m('Number of recent items displayed at home must be integer');
		}
		$iUpdated+= $preference->update(array('s_value' => $numItemsSearch), array('s_section' => 'osclass', 's_name' => 'defaultResultsPerPage@search'));
		if ($iUpdated > 0) 
		{
			if ($error != '') 
			{
				osc_add_flash_error_message($error . "<br/>" . _m('General settings have been updated'), 'admin');
			}
			else
			{
				osc_add_flash_ok_message(_m('General settings have been updated'), 'admin');
			}
		}
		else if ($error != '') 
		{
			osc_add_flash_error_message($error, 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings');
	}
}
