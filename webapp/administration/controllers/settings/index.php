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
class CAdminSettings extends AdministrationController
{
	public function doModel() 
	{
		switch ($this->action) 
		{
		case ('update'): // update index view
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
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sPageTitle), array('s_section' => 'osclass', 's_name' => 'pageTitle'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sPageDesc), array('s_section' => 'osclass', 's_name' => 'pageDesc'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sContactEmail), array('s_section' => 'osclass', 's_name' => 'contactEmail'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sLanguage), array('s_section' => 'osclass', 's_name' => 'language'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sDateFormat), array('s_section' => 'osclass', 's_name' => 'dateFormat'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sCurrency), array('s_section' => 'osclass', 's_name' => 'currency'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sWeekStart), array('s_section' => 'osclass', 's_name' => 'weekStart'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sTimeFormat), array('s_section' => 'osclass', 's_name' => 'timeFormat'));
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $sTimezone), array('s_section' => 'osclass', 's_name' => 'timezone'));
			if (is_int($sNumRssItems)) 
			{
				$iUpdated+= Preference::newInstance()->update(array('s_value' => $sNumRssItems), array('s_section' => 'osclass', 's_name' => 'num_rss_items'));
			}
			else
			{
				if ($error != '') $error.= "<br/>";
				$error.= _m('Number of items in the RSS must be integer');
			}
			if (is_int($maxLatestItems)) 
			{
				$iUpdated+= Preference::newInstance()->update(array('s_value' => $maxLatestItems), array('s_section' => 'osclass', 's_name' => 'maxLatestItems@home'));
			}
			else
			{
				if ($error != '') $error.= "<br/>";
				$error.= _m('Number of recent items displayed at home must be integer');
			}
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $numItemsSearch), array('s_section' => 'osclass', 's_name' => 'defaultResultsPerPage@search'));
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
			break;

		default: // calling the view
			$aLanguages = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled();
			$aCurrencies = ClassLoader::getInstance()->getClassInstance( 'Model_Currency' )->listAll();
			$this->getView()->assign('aLanguages', $aLanguages);
			$this->getView()->assign('aCurrencies', $aCurrencies);
			$this->doView('settings/index.php');
			break;
		}
	}

	function install_location_by_country() 
	{
		$country_code = Params::getParam('c_country');
		$aCountryCode[] = trim($country_code);
		$manager_country = new Country();
		$countries_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=country_id&term=' . urlencode(implode(',', $aCountryCode)));
		$countries = json_decode($countries_json);
		if (isset($countries->error)) 
		{
			osc_add_flash_error_message(sprintf(_m('%s cannot be added'), $country), 'admin');
			return false;
		}
		foreach ($countries as $c) 
		{
			$exists = $manager_country->findByCode($c->id);
			if (isset($exists['s_name'])) 
			{
				osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $exists['s_name']), 'admin');
				return false;
			}
			$manager_country->insert(array("pk_c_code" => $c->id, "fk_c_locale_code" => $c->locale_code, "s_name" => $c->name));
		}
		$manager_region = new Region();
		$regions_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=region&country_id=' . urlencode(implode(',', $aCountryCode)) . '&term=all');
		$regions = json_decode($regions_json);
		foreach ($regions as $r) 
		{
			$manager_region->insert(array("fk_c_country_code" => $r->country_code, "s_name" => $r->name));
		}
		unset($regions);
		unset($regions_json);
		$manager_city = new City();
		foreach ($countries as $c) 
		{
			$regions = $manager_region->finbByCountry($c->id);
			foreach ($regions as $region) 
			{
				$cities_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&region=' . urlencode($region['s_name']) . '&term=all');
				$cities = json_decode($cities_json);
				if (!isset($cities->error)) 
				{
					foreach ($cities as $ci) 
					{
						$manager_city->insert(array("fk_i_region_id" => $region['pk_i_id'], "s_name" => $ci->name, "fk_c_country_code" => $ci->country_code));
					}
				}
				unset($cities);
				unset($cities_json);
			}
		}
		osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $country), 'admin');
	}
	function install_location_by_region() 
	{
		$countryParent = Params::getParam('country_c_parent');
		$region = Params::getParam('region');
		if ($countryParent == '') 
		{
			return false;
		}
		if ($region == '') 
		{
			return false;
		}
		$manager_country = new Country();
		$country = $manager_country->findByCode($countryParent);
		$aCountry = array();
		$aRegion = array();
		$aCountry[] = $country['s_name'];
		$aRegion[] = $region;
		$manager_region = new Region();
		$regions_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=region&country=' . urlencode(implode(',', $aCountry)) . '&term=' . urlencode(implode(',', $aRegion)));
		$regions = json_decode($regions_json);
		if (isset($regions->error)) 
		{
			osc_add_flash_error_message(sprintf(_m('%s cannot be added'), $region), 'admin');
			return false;
		}
		foreach ($regions as $r) 
		{
			$exists = $manager_region->findByName($r->name, $r->country_code);
			if (isset($exists['s_name'])) 
			{
				osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $c_exists['s_name']), 'admin');
				return false;
			}
			$manager_region->insert(array("fk_c_country_code" => $r->country_code, "s_name" => $r->name));
		}
		unset($regions);
		unset($regions_json);
		$manager_city = new City();
		foreach ($country as $c) 
		{
			$regions = $manager_region->findByName($region, $country['pk_c_code']);
			$cities_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c) . '&region=' . urlencode($regions['s_name']) . '&term=all');
			$cities = json_decode($cities_json);
			if (!isset($cities->error)) 
			{
				foreach ($cities as $ci) 
				{
					$manager_city->insert(array("fk_i_region_id" => $regions['pk_i_id'], "s_name" => $ci->name, "fk_c_country_code" => $ci->country_code));
				}
			}
			unset($cities);
			unset($cities_json);
		}
		osc_add_flash_ok_message(sprintf(_m('%s has been added as a region of %s'), $region, $country['s_name']), 'admin');
	}
}
