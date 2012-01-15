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
class CAdminPage extends AdminSecBaseModel
{
	private $pageManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->pageManager = Page::newInstance();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->_exportVariableToView("page", array());
		$this->doView("pages/frm.php");
	}
	
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		// setForm just in case the form fails
		foreach (Params::getParamsAsArray() as $k => $v) 
		{
			Session::newInstance()->_setForm($k, $v);
		}
		$s_internal_name = Params::getParam("s_internal_name");
		if ($s_internal_name == '') 
		{
			osc_add_flash_error_message(_m('You have to set an internal name'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=pages&action=add");
		}
		if (!WebThemes::newInstance()->isValidPage($s_internal_name)) 
		{
			osc_add_flash_error_message(_m('You have to set a different internal name'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=pages&action=add");
		}
		$page = $this->pageManager->findByInternalName($s_internal_name);
		if (!isset($page['pk_i_id'])) 
		{
			$aFields = array('s_internal_name' => $s_internal_name, 'b_indelible' => '0');
			$aFieldsDescription = array();
			$postParams = Params::getParamsAsArray();
			$not_empty = false;
			foreach ($postParams as $k => $v) 
			{
				if (preg_match('|(.+?)#(.+)|', $k, $m)) 
				{
					if ($m[2] == 's_title' && $v != '') 
					{
						$not_empty = true;
					}
					$aFieldsDescription[$m[1]][$m[2]] = $v;
				}
			}
			if ($not_empty) 
			{
				$result = $this->pageManager->insert($aFields, $aFieldsDescription);
				osc_add_flash_ok_message(_m('The page has been added'), 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('The page couldn\'t be added, at least one title should not be empty'), 'admin');
			}
		}
		else
		{
			osc_add_flash_error_message(_m('Oops! That internal name is already in use. We can\'t made the changes'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=pages");
	}
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}