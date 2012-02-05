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
define('IS_AJAX', true);
class CAdminAjax extends Controller_Administration
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	function doModel() 
	{
			$id = Params::getParam("id");
			$fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;
			$error = 0;
			$has_one_title = 0;
			$postParams = Params::getParamsAsArray();
			foreach ($postParams as $k => $v) 
			{
				if (preg_match('|(.+?)#(.+)|', $k, $m)) 
				{
					if ($m[2] == 's_name') 
					{
						if ($v != "") 
						{
							$has_one_title = 1;
							$aFieldsDescription[$m[1]][$m[2]] = $v;
							$s_text = $v;
						}
						else
						{
							$aFieldsDescription[$m[1]][$m[2]] = ' ';
							$error = 1;
						}
					}
					else
					{
						$aFieldsDescription[$m[1]][$m[2]] = $v;
					}
				}
			}
			$l = osc_language();
			if ($error == 0 || ($error == 1 && $has_one_title == 1)) 
			{
				try
				{
					$categoryManager = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
					$categoryManager->updateByPrimaryKey(array('fields' => $fields, 'aFieldsDescription' => $aFieldsDescription), $id);
				}
				catch(Exception $e) 
				{
					$error = 2;
				}
			}
			if ($error == 0) 
			{
				$msg = __("Category updated correctly");
			}
			else if ($error == 1) 
			{
				if ($has_one_title == 1) 
				{
					$error = 4;
					$msg = __('Category updated correctly, but some titles were empty');
				}
				else
				{
					$msg = __('Sorry, at least a title is needed');
				}
			}
			else if ($error == 2) 
			{
				$msg = __('Error while updating');
			}
			echo json_encode(array('error' => $error, 'msg' => $msg, 'text' => $aFieldsDescription[$l]['s_name']));
	$this->getSession()->_dropKeepForm();
	$this->getSession()->_clearVariables();
	}
}

