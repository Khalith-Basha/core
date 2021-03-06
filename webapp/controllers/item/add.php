<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
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
class CWebItem extends Controller_Default
{
	private $itemManager;
	private $user;
	private $userId;

	public function __construct() 
	{
		parent::__construct();
		$this->itemManager = new \Osc\Model\Item;
		if (osc_is_web_user_logged_in()) 
		{
			$this->userId = osc_logged_user_id();
			$userModel = new \Osc\Model\User;
			$this->user = $userModel->findByPrimaryKey($this->userId);
		}
		else
		{
			$this->userId = null;
			$this->user = null;
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$classLoader = $this->getClassLoader();
		$userUrls = $classLoader->getClassInstance( 'Url_User' );
		$classLoader->loadFile( 'helpers/locations' );
		$localeModel = new \Osc\Model\Locale;
		$locales = $localeModel->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		if (osc_reg_user_post() && $this->user == null) 
		{
			$this->getSession()->addFlashMessage( _m('Only registered users are allowed to post items'), 'WARNING' );
			$this->redirectTo( $userUrls->osc_user_login_url());
		}
		$countryModel = new \Osc\Model\Country;
		$countries = $countryModel->listAll();
		$regions = array();
		$regionModel = new \Osc\Model\Region;
		if (isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code'] != '') 
		{
			$regions = $regionModel->findByCountry($this->user['fk_c_country_code']);
		}
		else if (count($countries) > 0) 
		{
			$regions = $regionModel->findByCountry($countries[0]['pk_c_code']);
		}
		$cities = array();

		$cityModel = new \Osc\Model\City;
		if (isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id'] != '') 
		{
			$cities = $cityModel->findByRegion( $this->user['fk_i_region_id'] );
		}
		else if (count($regions) > 0) 
		{
			$cities = $cityModel->findByRegion( $regions[0]['pk_i_id'] );
		}

		$view = $this->getView();
		$view->addJavaScript( osc_current_web_theme_js_url('jquery.validate.min.js') );
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
			$regions = $regionModel->findByCountry($countryId);
			$this->getView()->assign('regions', $regions);
			if ($this->getSession()->_getForm('regionId') != "") 
			{
				$regionId = $this->getSession()->_getForm('regionId');
				$cities = $cityModel->findByRegion($regionId);
				$this->getView()->assign('cities', $cities);
			}
		}
		$this->getView()->assign('user', $this->user);
		osc_run_hook('post_item');

		if( osc_images_enabled_at_items())
			$this->getView()->addJavaScript( '/static/scripts/photos.js' );
		$this->getView()->addJavaScript( '/static/scripts/plugin-post-item.js' );

		$this->getView()->setTitle( __('Publish an item', 'modern') . ' - ' . osc_page_title() );
		echo $this->getView()->render( 'item/post' );
	}
}
