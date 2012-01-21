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
class CAdminPlugin extends AdminSecBaseModel
{
	public function doModel() 
	{
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
		parent::doModel();
		switch ($this->action) 
		{
		case 'renderplugin':
			global $active_plugins;
			$file = Params::getParam("file");
			if ($file != "") 
			{
				// We pass the GET variables (in case we have somes)
				if (preg_match('|(.+?)\?(.*)|', $file, $match)) 
				{
					$file = $match[1];
					if (preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&' . $match[2] . '&'), $get_vars)) 
					{
						for ($var_k = 0; $var_k < count($get_vars[1]); $var_k++) 
						{
							//$_GET[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
							//$_REQUEST[$get_vars[1][$var_k]] = $get_vars[2][$var_k];
							Params::setParam($get_vars[1][$var_k], $get_vars[2][$var_k]);
						}
					}
				}
				else
				{
					$file = $_REQUEST['file'];
				};
				$this->_exportVariableToView("file", osc_plugins_path() . DIRECTORY_SEPARATOR . $file);
				//osc_renderPluginView($file);
				$this->doView("plugins/view.php");
			}
			break;

		case 'render':
			$file = Params::getParam("file");
			if ($file != "") 
			{
				// We pass the GET variables (in case we have somes)
				if (preg_match('|(.+?)\?(.*)|', $file, $match)) 
				{
					$file = $match[1];
					if (preg_match_all('|&([^=]+)=([^&]*)|', urldecode('&' . $match[2] . '&'), $get_vars)) 
					{
						for ($var_k = 0; $var_k < count($get_vars[1]); $var_k++) 
						{
							Params::setParam($get_vars[1][$var_k], $get_vars[2][$var_k]);
						}
					}
				}
				else
				{
					$file = $_REQUEST['file'];
				};
				$this->_exportVariableToView("file", ABS_PATH . DIRECTORY_SEPARATOR . $file);
				$this->doView("theme/view.php");
			}
			break;
		}
	}

	public function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}

