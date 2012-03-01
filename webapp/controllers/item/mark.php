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
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = ClassLoader::getInstance();
		$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
		$locales = $classLoader->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$mItem = $classLoader->getClassInstance( 'Manager_Item', false, array( false ) );
		$id = Params::getParam('id');
		$as = Params::getParam('as');
		$item = $classLoader->getClassInstance( 'Model_Item' )->findByPrimaryKey($id);
		$mItem->mark($id, $as);
		osc_add_flash_ok_message(_m('Thanks! That\'s very helpful'));
		$this->redirectTo( $itemUrls->getDetailsUrl( $item ) );
	}
}

