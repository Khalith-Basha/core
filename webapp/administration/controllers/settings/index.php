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
class CAdminSettings extends AdminSecBaseModel
{
	public function doModel() 
	{
		switch ($this->action) 
		{
		case ('locations'): // calling the locations settings view
			$location_action = Params::getParam('type');
			$mCountries = new Country();
			switch ($location_action) 
			{
			case ('add_country'): // add country
				$countryCode = strtoupper(Params::getParam('c_country'));
				$request = Params::getParam('country');
				foreach ($request as $k => $v) 
				{
					$countryName = $v;
					break;
				}
				$exists = $mCountries->findByCode($countryCode);
				if (isset($exists['s_name'])) 
				{
					osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $countryName), 'admin');
				}
				else
				{
					$countries_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=country_code&term=' . urlencode($countryCode));
					$countries = json_decode($countries_json);
					foreach ($request as $k => $v) 
					{
						$data = array('pk_c_code' => $countryCode, 'fk_c_locale_code' => $k, 's_name' => $v);
						$mCountries->insert($data);
					}
					if (isset($countries->error)) 
					{ // Country is not in our GEO database
						// We have no region for user-typed countries
						
					}
					else
					{ // Country is in our GEO database, add regions and cities
						$manager_region = new Region();
						$regions_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=region&country_code=' . urlencode($countryCode) . '&term=all');
						$regions = json_decode($regions_json);
						if (!isset($regions->error)) 
						{
							if (count($regions) > 0) 
							{
								foreach ($regions as $r) 
								{
									$manager_region->insert(array("fk_c_country_code" => $r->country_code, "s_name" => $r->name));
								}
							}
							unset($regions);
							unset($regions_json);
							$manager_city = new City();
							if (count($countries) > 0) 
							{
								foreach ($countries as $c) 
								{
									$regions = $manager_region->findByCountry($c->id);
									if (!isset($regions->error)) 
									{
										if (count($regions) > 0) 
										{
											foreach ($regions as $region) 
											{
												$cities_json = osc_file_get_contents('http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&region=' . urlencode($region['s_name']) . '&term=all');
												$cities = json_decode($cities_json);
												if (!isset($cities->error)) 
												{
													if (count($cities) > 0) 
													{
														foreach ($cities as $ci) 
														{
															$manager_city->insert(array("fk_i_region_id" => $region['pk_i_id'], "s_name" => $ci->name, "fk_c_country_code" => $ci->country_code));
														}
													}
												}
												unset($cities);
												unset($cities_json);
											}
										}
									}
								}
							}
						}
					}
					osc_add_flash_ok_message(sprintf(_m('%s has been added as a new country'), $countryName), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('edit_country'): // edit country
				$countryCode = Params::getParam('country_code');
				$request = Params::getParam('e_country');
				$ok = true;
				foreach ($request as $k => $v) 
				{
					$result = $mCountries->updateLocale($countryCode, $k, $v);
					if ($result === false) 
					{
						$ok = false;
					}
				}
				if ($ok) 
				{
					osc_add_flash_ok_message(_m('Country has been edited'), 'admin');
				}
				else
				{
					osc_add_flash_error_message(_m('There were some problems editing the country'), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('delete_country'): // delete country
				$countryId = Params::getParam('id');
				// HAS ITEMS?
				$has_items = Item::newInstance()->listWhere('l.fk_c_country_code = \'%s\' LIMIT 1', $countryId);
				if (!$has_items) 
				{
					$mRegions = new Region();
					$mCities = new City();
					$aCountries = $mCountries->findByCode($countryId);
					$aRegions = $mRegions->findByCountry($aCountries['pk_c_code']);
					foreach ($aRegions as $region) 
					{
						$mCities->delete(array('fk_i_region_id' => $region['pk_i_id']));
						$mRegions->delete(array('pk_i_id' => $region['pk_i_id']));
					}
					$mCountries->delete(array('pk_c_code' => $aCountries['pk_c_code']));
					osc_add_flash_ok_message(sprintf(_m('%s has been deleted'), $aCountries['s_name']), 'admin');
				}
				else
				{
					osc_add_flash_error_message(sprintf(_m('%s can not be deleted, some items are located in it'), $aCountries['s_name']), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('add_region'): // add region
				if (!Params::getParam('r_manual')) 
				{
					$this->install_location_by_region();
				}
				else
				{
					$mRegions = new Region();
					$regionName = Params::getParam('region');
					$countryCode = Params::getParam('country_c_parent');
					$exists = $mRegions->findByName($regionName, $countryCode);
					if (!isset($exists['s_name'])) 
					{
						$data = array('fk_c_country_code' => $countryCode, 's_name' => $regionName);
						$mRegions->insert($data);
						osc_add_flash_ok_message(sprintf(_m('%s has been added as a new region'), $regionName), 'admin');
					}
					else
					{
						osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $regionName), 'admin');
					}
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('edit_region'): // edit region
				$mRegions = new Region();
				$newRegion = Params::getParam('e_region');
				$regionId = Params::getParam('region_id');
				$exists = $mRegions->findByName($newRegion);
				if (!$exists['pk_i_id'] || $exists['pk_i_id'] == $regionId) 
				{
					if ($regionId != '') 
					{
						$mRegions->update(array('s_name' => $newRegion), array('pk_i_id' => $regionId));
						ItemLocation::newInstance()->update(array('s_region' => $newRegion), array('fk_i_region_id' => $regionId));
						osc_add_flash_ok_message(sprintf(_m('%s has been edited'), $newRegion), 'admin');
					}
				}
				else
				{
					osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newRegion), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('delete_region'): // delete region
				$mRegion = new Region();
				$mCities = new City();
				$regionId = Params::getParam('id');
				if ($regionId != '') 
				{
					$aRegion = $mRegion->findByPrimaryKey($regionId);
					$mCities->delete(array('fk_i_region_id' => $regionId));
					$mRegion->delete(array('pk_i_id' => $regionId));
					osc_add_flash_ok_message(sprintf(_m('%s has been deleted'), $aRegion['s_name']), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('add_city'): // add city
				$mCities = new City();
				$regionId = Params::getParam('region_parent');
				$countryCode = Params::getParam('country_c_parent');
				$newCity = Params::getParam('city');
				$exists = $mCities->findByName($newCity, $regionId);
				if (!isset($exists['s_name'])) 
				{
					$mCities->insert(array('fk_i_region_id' => $regionId, 's_name' => $newCity, 'fk_c_country_code' => $countryCode));
					osc_add_flash_ok_message(sprintf(_m('%s has been added as a new city'), $newCity), 'admin');
				}
				else
				{
					osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newCity), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('edit_city'): // edit city
				$mCities = new City();
				$newCity = Params::getParam('e_city');
				$cityId = Params::getParam('city_id');
				$exists = $mCities->findByName($newCity);
				if (!isset($exists['pk_i_id']) || $exists['pk_i_id'] == $cityId) 
				{
					$mCities->update(array('s_name' => $newCity), array('pk_i_id' => $cityId));
					ItemLocation::newInstance()->update(array('s_city' => $newCity), array('fk_i_city_id' => $cityId));
					osc_add_flash_ok_message(sprintf(_m('%s has been edited'), $newCity), 'admin');
				}
				else
				{
					osc_add_flash_error_message(sprintf(_m('%s already was in the database'), $newCity), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;

			case ('delete_city'): // delete city
				$mCities = new City();
				$cityId = Params::getParam('id');
				$aCity = $mCities->findByPrimaryKey($cityId);
				$mCities->delete(array('pk_i_id' => $cityId));
				osc_add_flash_ok_message(sprintf(_m('%s has been deleted'), $aCity['s_name']), 'admin');
				$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=locations');
				break;
			}
			$aCountries = $mCountries->listAllAdmin();
			$this->_exportVariableToView('aCountries', $aCountries);
			$this->doView('settings/locations.php');
			break;

		case ('currencies'): // currencies settings
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
				$isInserted = Currency::newInstance()->insert($fields);
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
				$aCurrency = Currency::newInstance()->findByPrimaryKey($currencyCode);
				if (count($aCurrency) == 0) 
				{
					osc_add_flash_error_message(_m('Error: the currency doesn\'t exist'), 'admin');
					$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
				}
				$this->_exportVariableToView('aCurrency', $aCurrency);
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
				$iUpdated = Currency::newInstance()->update(array('s_name' => $currencyName, 's_description' => $currencyDescription), array('pk_c_code' => $currencyCode));
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
						$rowChanged+= Currency::newInstance()->delete(array('pk_c_code' => $currencyCode));
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
				$aCurrencies = Currency::newInstance()->listAll();
				$this->_exportVariableToView('aCurrencies', $aCurrencies);
				$this->doView('settings/currencies.php');
				break;
			}
			break;

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
			$aLanguages = OSCLocale::newInstance()->listAllEnabled();
			$aCurrencies = Currency::newInstance()->listAll();
			$this->_exportVariableToView('aLanguages', $aLanguages);
			$this->_exportVariableToView('aCurrencies', $aCurrencies);
			$this->doView('settings/index.php');
			break;
		}
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
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
