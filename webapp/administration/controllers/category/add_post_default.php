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
class CAdminCategory extends AdminSecBaseModel
{
	private $categoryManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->categoryManager = Category::newInstance();
	}

	function doModel() 
	{
		parent::doModel();
		$fields['fk_i_parent_id'] = NULL;
		$fields['i_expiration_days'] = 0;
		$fields['i_position'] = 0;
		$fields['b_enabled'] = 1;
		$default_locale = osc_language();
		$aFieldsDescription[$default_locale]['s_name'] = "NEW CATEGORY, EDIT ME!";
		$categoryId = $this->categoryManager->insert($fields, $aFieldsDescription);
		// reorder parent categories. NEW category first
		$rootCategories = $this->categoryManager->findRootCategories();
		foreach ($rootCategories as $cat) 
		{
			$order = $cat['i_position'];
			$order++;
			$this->categoryManager->updateOrder($cat['pk_i_id'], $order);
		}
		$this->categoryManager->updateOrder($categoryId, '0');
		$this->redirectTo(osc_admin_base_url(true) . '?page=category');
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}
