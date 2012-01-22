<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
class CWebItem extends Controller
{
	private $itemManager;
	private $user;
	private $userId;
	function __construct() 
	{
		parent::__construct();
		$this->itemManager = $this->getClassLoader()->getClassInstance( 'Model_Item' );
		if (osc_is_web_user_logged_in()) 
		{
			$this->userId = osc_logged_user_id();
			$this->user = $this->getClassLoader()->getClassInstance( 'Model_User' )->findByPrimaryKey($this->userId);
		}
		else
		{
			$this->userId = null;
			$this->user = null;
		}
	}
	function doModel() 
	{
		$locales = $this->getClassLoader()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		if (osc_reg_user_post() && $this->user == null) 
		{
			osc_add_flash_warning_message(_m('Only registered users are allowed to post items'));
			$this->redirectTo(osc_user_login_url());
		}
		$countries = $this->getClassLoader()->getClassInstance( 'Model_Country' )->listAll();
		$regions = array();
		if (isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code'] != '') 
		{
			$regions = $this->getClassLoader()->getClassInstance( 'Model_Region' )->findByCountry($this->user['fk_c_country_code']);
		}
		else if (count($countries) > 0) 
		{
			$regions = $this->getClassLoader()->getClassInstance( 'Model_Region' )->findByCountry($countries[0]['pk_c_code']);
		}
		$cities = array();
		if (isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id'] != '') 
		{
			$cities = City::newInstance()->findByRegion($this->user['fk_i_region_id']);
		}
		else if (count($regions) > 0) 
		{
			$cities = City::newInstance()->findByRegion($regions[0]['pk_i_id']);
		}
		$this->getView()->assign('countries', $countries);
		$this->getView()->assign('regions', $regions);
		$this->getView()->assign('cities', $cities);
		$form = count($this->getSession()->_getForm());
		$keepForm = count($this->getSession()->_getKeepForm());
		if ($form == 0 || $form == $keepForm) 
		{
			$this->getSession()->_dropKeepForm();
		}
		if ($this->getSession()->_getForm('countryId') != "") 
		{
			$countryId = $this->getSession()->_getForm('countryId');
			$regions = $this->getClassLoader()->getClassInstance( 'Model_Region' )->findByCountry($countryId);
			$this->getView()->assign('regions', $regions);
			if ($this->getSession()->_getForm('regionId') != "") 
			{
				$regionId = $this->getSession()->_getForm('regionId');
				$cities = City::newInstance()->findByRegion($regionId);
				$this->getView()->assign('cities', $cities);
			}
		}
		$this->getView()->assign('user', $this->user);
		osc_run_hook('post_item');
		$this->doView('item/post.php');
	}
	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		$this->getSession()->_clearVariables();
		osc_run_hook("after_html");
	}
}
