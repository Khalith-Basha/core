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
class CAdminAjax extends AdminSecBaseModel
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	function doModel() 
	{
		$id = Params::getParam("id");
		$error = 0;
		try
		{
			$categoryManager = Category::newInstance();
			$categoryManager->deleteByPrimaryKey($id);
			$message = __('The categories have been deleted');
		}
		catch(Exception $e) 
		{
			$error = 1;
			$message = __('Error while deleting');
		}
		$result = "{";
		if ($error) 
		{
			$result.= '"error" : "';
			$result.= $message;
			$result.= '"';
		}
		else
		{
			$result.= '"ok" : "Saved." ';
		}
		$result.= "}";
		echo $result;
		Session::newInstance()->_dropKeepForm();
		Session::newInstance()->_clearVariables();
	}
}

