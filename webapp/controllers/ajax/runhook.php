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
define('IS_AJAX', true);
class CWebAjax extends Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}
	//Business Layer...
	function doModel() 
	{
		$hook = Params::getParam("hook");
		switch ($hook) 
		{
		case 'item_form':
			$catId = Params::getParam("catId");
			if ($catId != '') 
			{
				osc_run_hook("item_form", $catId);
			}
			else
			{
				osc_run_hook("item_form");
			}
			break;

		case 'item_edit':
			$catId = Params::getParam("catId");
			$itemId = Params::getParam("itemId");
			osc_run_hook("item_edit", $catId, $itemId);
			break;

		default:
			if ($hook == '') 
			{
				return false;
			}
			else
			{
				osc_run_hook($hook);
			}
			break;
		}
		Session::newInstance()->_dropKeepForm();
		Session::newInstance()->_clearVariables();
	}
	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		osc_run_hook("after_html");
	}
}
