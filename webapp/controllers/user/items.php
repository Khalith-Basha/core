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
class CWebUser extends WebSecBaseModel
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
	//Business Layer...
	function doModel() 
	{
		$itemsPerPage = (Params::getParam('itemsPerPage') != '') ? Params::getParam('itemsPerPage') : 5;
		$page = (Params::getParam('iPage') != '') ? Params::getParam('iPage') : 0;
		$total_items = Item::newInstance()->countByUserIDEnabled($_SESSION['userId']);
		$total_pages = ceil($total_items / $itemsPerPage);
		$items = Item::newInstance()->findByUserIDEnabled($_SESSION['userId'], $page * $itemsPerPage, $itemsPerPage);
		$this->_exportVariableToView('items', $items);
		$this->_exportVariableToView('list_total_pages', $total_pages);
		$this->_exportVariableToView('list_total_items', $total_items);
		$this->_exportVariableToView('items_per_page', $itemsPerPage);
		$this->_exportVariableToView('list_page', $page);
		$this->doView('user/items.php');
	}
	//hopefully generic...
	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		Session::newInstance()->_clearVariables();
		osc_run_hook("after_html");
	}
}
