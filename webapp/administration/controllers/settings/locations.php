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
		$location_action = Params::getParam('type');
		$mCountries = $this->getClassLoader()->getClassInstance( 'Model_Country' );
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
			$has_items = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->listWhere('l.fk_c_country_code = \'%s\' LIMIT 1', $countryId);
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
					ClassLoader::getInstance()->getClassInstance( 'Model_ItemLocation' )->update(array('s_region' => $newRegion), array('fk_i_region_id' => $regionId));
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
				ClassLoader::getInstance()->getClassInstance( 'Model_ItemLocation' )->update(array('s_city' => $newCity), array('fk_i_city_id' => $cityId));
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
		$this->getView()->assign('aCountries', $aCountries);
		$this->doView('settings/locations.php');
	}
}
