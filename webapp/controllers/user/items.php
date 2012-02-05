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
class CWebUser extends Controller_User
{
	function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$classLoader = $this->getClassLoader();
		$itemsModel = $classLoader->getClassInstance( 'Model_Item' );

		$itemsPerPage = $this->getInput()->getInteger( 'itemsPerPage', 5 );
		$page = $this->getInput()->getInteger( 'iPage', 0 );
		$total_items = $itemsModel->countByUserIDEnabled($_SESSION['userId']);
		$total_pages = ceil( $total_items / $itemsPerPage );
		$items = $itemsModel->findByUserIDEnabled($_SESSION['userId'], $page * $itemsPerPage, $itemsPerPage);

		$view = $this->getView();
		$view->assign('items', $items);
		$view->assign('list_total_pages', $total_pages);
		$view->assign('list_total_items', $total_items);
		$view->assign('items_per_page', $itemsPerPage);
		$view->assign('list_page', $page);
		$view->setTitle( __('Manage my items', 'modern') . ' - ' . osc_page_title() );
		echo $view->render( 'user/items' );
	}
}

