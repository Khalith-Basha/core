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
class CAdminIndex extends AdminSecBaseModel
{
	public function doModel() 
	{
		$this->_exportVariableToView("numUsers", User::newInstance()->count());
		$this->_exportVariableToView("numAdmins", Admin::newInstance()->count());
		$this->_exportVariableToView("numItems", Item::newInstance()->count());
		$this->_exportVariableToView("numItemsPerCategory", osc_get_non_empty_categories());
		$this->_exportVariableToView("newsList", osc_listNews());
		$this->_exportVariableToView("comments", ItemComment::newInstance()->getLastComments(5));

		$this->doView('main/index.php');
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}
