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

class CAdminIndex extends AdministrationController
{
	public function doModel() 
	{
		require 'osc/feeds.php';

		$this->getView()->assign("numUsers", $this->getClassLoader()->getClassInstance( 'Model_User' )->count());
		$this->getView()->assign("numAdmins", $this->getClassLoader()->getClassInstance( 'Model_Admin' )->count());
		$this->getView()->assign("numItems", $this->getClassLoader()->getClassInstance( 'Model_Item' )->count());
		$this->getView()->assign("numItemsPerCategory", osc_get_non_empty_categories());
		$this->getView()->assign("newsList", osc_listNews());
		$this->getView()->assign("comments", $this->getClassLoader()->getClassInstance( 'Model_ItemComment' )->getLastComments(5));

		$this->doView('main/index.php');
	}
}

