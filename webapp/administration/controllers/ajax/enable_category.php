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

class CAdminAjax extends Controller_Administration
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = Params::getParam("id");
		$enabled = (Params::getParam("enabled") != '') ? Params::getParam("enabled") : 0;
		$error = 0;
		$result = array();
		$aUpdated = array();
		$mCategory = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
		$aCategory = $mCategory->findByPrimaryKey($id);
		if ($aCategory == false) 
		{
			$result = array('error' => sprintf(__("It doesn't exist a category with this id: %d"), $id));
			echo json_encode($result);
		}
		// root category
		if ($aCategory['fk_i_parent_id'] == '') 
		{
			$mCategory->update(array('b_enabled' => $enabled), array('pk_i_id' => $id));
			$mCategory->update(array('b_enabled' => $enabled), array('fk_i_parent_id' => $id));
			$subCategories = $mCategory->findSubcategories($id);
			$aUpdated[] = array('id' => $id);
			foreach ($subCategories as $subcategory) 
			{
				$aUpdated[] = array('id' => $subcategory['pk_i_id']);
			}
			if ($enabled) 
			{
				$result = array('ok' => __('The category and its subcategories have been enabled'));
			}
			else
			{
				$result = array('ok' => __('The category and its subcategories have been disabled'));
			}
			$result['affectedIds'] = $aUpdated;
			echo json_encode($result);
		}
		// subcategory
		$parentCategory = $mCategory->findRootCategory($id);
		if (!$parentCategory['b_enabled']) 
		{
			$result = array('error' => __('Parent category is disabled, you can not enable that category'));
			echo json_encode($result);
		}
		$mCategory->update(array('b_enabled' => $enabled), array('pk_i_id' => $id));
		if ($enabled) 
		{
			$result = array('ok' => __('The subcategory has been enabled'));
		}
		else
		{
			$result = array('ok' => __('The subcategory has been disabled'));
		}
		$result['affectedIds'] = array(array('id' => $id));
		echo json_encode($result);
	}
}
