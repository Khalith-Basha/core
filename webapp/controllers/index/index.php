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

class CWebIndex extends Controller_Cacheable
{
	public function getCacheKey()
	{
		return 'page-main';
	}

	public function getCacheExpiration()
	{
		return 1800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res )
	{
		$view = $this->getView();

		$locale = osc_current_user_locale();

		$classLoader = $this->getClassLoader();
		$pagesModel = $classLoader->getClassInstance( 'Model_Page' );
		$pageUrl = $classLoader->getClassInstance( 'Url_Page' );
		$pages = $pagesModel->findUserPagesByLocale( $locale );
		foreach( $pages as &$page )
		{
			$page['url'] = $pageUrl->getUrl( $page );
		}
		$view->assign( 'pages', $pages );

		$category = $classLoader->getClassInstance( 'Model_Category' );
		$view->assign('categories', $category->toTree());

		$search = $classLoader->getClassInstance( 'Model_Search' );
		$search->limit( 0, osc_max_latest_items() );
		$view->assign( 'latestItems', $search->getLatestItems() );

		$country = '%%%%';
		$regions = $classLoader->getClassInstance( 'Model_Search' )->listRegions( $country, '>' );
		$view->assign( 'regions', $regions );

		return $view->render( 'main' );
	}
}

