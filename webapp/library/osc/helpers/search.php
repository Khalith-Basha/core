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
/**
 * Helper Search
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets search object
 *
 * @return mixed
 */
function osc_search() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($view->varExists('search')) 
	{
		return $view->getVar('search');
	}
	else
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign( 'search', $searchModel );
		return $search;
	}
}
/**
 * Gets available search orders
 *
 * @return array
 */
function osc_list_orders() 
{
	return array(
		__('Newly listed') => array(
			'sOrder' => 'pub_date',
			'sOrderType' => 'desc',
			'iPage' => 0
		),
		__('Lower price first') => array(
			'sOrder' => 'price',
			'sOrderType' => 'asc',
			'iPage' => 0
		),
		__('Higher price first') => array(
			'sOrder' => 'price',
			'sOrderType' => 'desc',
			'iPage' => 0
		)
	);
}
/**
 * Gets if "has pic" option is enabled or not in the search
 *
 * @return boolean
 */
function osc_search_has_pic() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_has_pic');
}
/**
 * Gets current search order
 *
 * @return string
 */
function osc_search_order() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_order');
}
/**
 * Gets current search order type
 *
 * @return string
 */
function osc_search_order_type() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_order_type');
}
/**
 * Gets current search pattern
 *
 * @return string
 */
function osc_search_pattern() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('search_pattern')) 
	{
		return $view->getVar('search_pattern');
	}

	return null;
}
/**
 * Gets current search region
 *
 * @return string
 */
function osc_search_region() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_region');
}
/**
 * Gets current search city
 *
 * @return string
 */
function osc_search_city() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_city');
}
/**
 * Gets current search max price
 *
 * @return float
 */
function osc_search_price_max() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_price_max');
}
/**
 * Gets current search min price
 *
 * @return float
 */
function osc_search_price_min() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_price_min');
}
/**
 * Gets current search total items
 *
 * @return int
 */
function osc_search_total_items() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_total_items');
}
/**
 * Gets current search "show as" variable (show the items as a list or as a gallery)
 *
 * @return string
 */
function osc_search_show_as() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_show_as');
}
/**
 * Gets current search start item record
 *
 * @return int
 */
function osc_search_start() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_start');
}
/**
 * Gets current search end item record
 *
 * @return int
 */
function osc_search_end() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_end');
}
/**
 * Gets current search category
 *
 * @return array
 */
function osc_search_category() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('search_subcategories')) 
	{
		$category = $view->_current('search_subcategories');
	}
	elseif ($view->varExists('search_categories')) 
	{
		$category = $view->_current('search_categories');
	}
	else
	{
		$category = $view->getVar('search_category');
	}
	return ($category);
}
/**
 * Gets current search category id
 *
 * @return int
 */
function osc_search_category_id() 
{
	$categories = osc_search_category();
	$category = array();
	$where = array();
	foreach ($categories as $cat) 
	{
		if (is_numeric($cat)) 
		{
			$where[] = "a.pk_i_id = " . $cat;
		}
		else
		{
			$slug_cat = explode("/", trim($cat, "/"));
			$where[] = "b.s_slug = '" . $slug_cat[count($slug_cat) - 1] . "'";
		}
	}
	if (empty($where)) 
	{
		return null;
	}
	else
	{
		$categories = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->listWhere(implode(" OR ", $where));
		foreach ($categories as $cat) 
		{
			$category[] = $cat['pk_i_id'];
		}
		return $category;
	}
}
/**
 * Load the form for the alert subscription
 *
 * @return void
 */
function osc_alert_form() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('search_alert')) 
	{
		$search = osc_search();
		$search->order();
		$search->limit();
		$view->assign('search_alert', base64_encode(serialize($search)));
	}
	echo $view->render( 'alert-form' );
}
/**
 * Gets alert of current search
 *
 * @return string
 */
function osc_search_alert() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('search_alert');
}
/**
 * Gets list of countries with items
 *
 * @return array
 */
function osc_list_country() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('list_countries')) 
	{
		return $view->_current('list_countries');
	}
	else
	{
		return null;
	}
}
/**
 * Gets list of regions with items
 *
 * @return array
 */
function osc_list_region() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('list_regions')) 
	{
		return $view->_current('list_regions');
	}
	else
	{
		return null;
	}
}
/**
 * Gets list of cities with items
 *
 * @return array
 */
function osc_list_city() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('list_cities')) 
	{
		return $view->_current('list_cities');
	}
	else
	{
		return null;
	}
}
/**
 * Gets the next country in the list_countries list
 *
 * @return array
 */
function osc_has_list_countries() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_countries')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_countries', $searchModel->listCountries('>='));
	}
	return $view->_next('list_countries');
}
/**
 * Gets the next region in the list_regions list
 *
 * @param string $country
 * @return array
 */
function osc_has_list_regions($country = '%%%%') 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_regions')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_regions', $searchModel->listRegions($country, '>'));
	}
	return $view->_next('list_regions');
}
/**
 * Gets the next city in the list_cities list
 *
 * @param string $region
 * @return array
 */
function osc_has_list_cities($region = '%%%%') 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_cities')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_cities', $searchModel->listCities($region, '>='));
	}
	$result = $view->_next('list_cities');
	if (!$result) $view->_erase('list_cities');
	return $result;
}
/**
 * Gets the total number of countries in list_countries
 *
 * @return int
 */
function osc_count_list_countries() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_countries')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_countries', $searchModel->listCountries());
	}
	return $view->countVar('list_countries');
}
/**
 * Gets the total number of regions in list_regions
 *
 * @param string $country
 * @return int
 */
function osc_count_list_regions($country = '%%%%') 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_regions')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_regions', $searchModel->listRegions($country));
	}
	return $view->countVar('list_regions');
}
/**
 * Gets the total number of cities in list_cities
 *
 * @param string $region
 * @return int
 */
function osc_count_list_cities($region = '%%%%') 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('list_cities')) 
	{
		$searchModel = new \Osc\Model\Search;
		$view->assign('list_cities', $searchModel->listCities($region));
	}
	return $view->countVar('list_cities');
}
/**
 * Gets the the name of current "list region"
 *
 * @return string
 */
function osc_list_region_name( array $region )
{
	return osc_field( $region, 'region_name', '');
}
/**
 * Gets the number of items of current "list region"
 *
 * @return int
 */
function osc_list_region_items( array $region ) 
{
	return osc_field( $region, 'items', '' );
}
/**
 * Gets the the name of current "list city""
 *
 * @return string
 */
function osc_list_city_name() 
{
	return osc_field(osc_list_city(), 'city_name', '');
}
/**
 * Gets the number of items of current "list city"
 *
 * @return int
 */
function osc_list_city_items() 
{
	return osc_field(osc_list_city(), 'items', '');
}
/**
 * Gets the url of current "list country""
 *
 * @return string
 */
function osc_list_country_url() 
{
	return osc_search_url(array('sCountry' => osc_list_country_name()));
}
/**
 * Gets the url of current "list region""
 *
 * @return string
 */
function osc_list_region_url() 
{
	return osc_search_url(array('sRegion' => osc_list_region_name()));
}
/**
 * Gets the url of current "list city""
 *
 * @return string
 */
function osc_list_city_url() 
{
	return osc_search_url(array('sCity' => osc_list_city_name()));
}
/**********************
 ** LATEST SEARCHES **
 **********************/
/**
 * Gets the latest searches done in the website
 *
 * @param int $limit
 * @return array
 */
function osc_get_latest_searches($limit = 20) 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('latest_searches')) 
	{
		$view->assign('latest_searches', LatestSearches::newInstance()->getSearches($limit));
	}
	return $view->countVar('latest_searches');
}
/**
 * Gets the total number of latest searches done in the website
 *
 * @return int
 */
function osc_count_latest_searches() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('latest_searches')) 
	{
		$view->assign('latest_searches', LatestSearches::newInstance()->getSearches());
	}
	return $view->countVar('latest_searches');
}
/**
 * Gets the next latest search
 *
 * @return array
 */
function osc_has_latest_searches() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('latest_searches')) 
	{
		$view->assign('latest_searches', LatestSearches::newInstance()->getSearches());
	}
	return $view->_next('latest_searches');
}
/**
 * Gets the current latest search
 *
 * @return array
 */
function osc_latest_search() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->varExists('latest_searches')) 
	{
		return $view->_current('latest_searches');
	}
	return null;
}
/**
 * Gets the current latest search pattern
 *
 * @return string
 */
function osc_latest_search_text() 
{
	return osc_field(osc_latest_search(), 's_search', '');
}
/**
 * Gets the current latest search date
 *
 * @return string
 */
function osc_latest_search_date() 
{
	return osc_field(osc_latest_search(), 'd_date', '');
}
/**
 * Gets the current latest search total
 *
 * @return string
 */
function osc_latest_search_total() 
{
	return osc_field(osc_latest_search(), 'i_total', '');
}
