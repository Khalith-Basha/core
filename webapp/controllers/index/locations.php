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
		return 'page-locations';
	}

	public function getCacheExpiration()
	{
		return 1800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res )
	{
		$view = $this->getView();

		$classLoader = $this->getClassLoader();
		$regionModel = $classLoader->getClassInstance( 'Model_Region' );
		$regions = $regionModel->findAll();

		$cityModel = $classLoader->getClassInstance( 'Model_City' );
		foreach( $regions as &$region )
		{
			$region['cities'] = $cityModel->findByRegion( $region['pk_i_id'] );
		}

		$view->assign( 'regions', $regions );

		return $view->render( 'index/locations' );
	}
}

