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
				$this->getView()->_exportVariableToView("file", ABS_PATH . $file);
				$this->doView("theme/view.php");
			}
			break;

		case 'configure':
			$plugin = Params::getParam("plugin");
			if ($plugin != '') 
			{
				$plugin_data = $pluginManager->getInfo($plugin);
				$this->getView()->_exportVariableToView("categories", Category::newInstance()->toTreeAll());
				$this->getView()->_exportVariableToView("selected", PluginCategory::newInstance()->listSelected($plugin_data['short_name']));
				$this->getView()->_exportVariableToView("plugin_data", $plugin_data);
				$this->doView("plugins/configuration.php");
			}
			else
			{
				$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
			}
			break;

		case 'configure_post':
			$plugin_short_name = Params::getParam("plugin_short_name");
			$categories = Params::getParam("categories");
			if ($plugin_short_name != "") 
			{
				$pluginManager->cleanCategoryFromPlugin($plugin_short_name);
				if (isset($categories)) 
				{
					$pluginManager->addToCategoryPlugin($categories, $plugin_short_name);
				}
			}
			else
			{
				osc_add_flash_error_message(_m('No plugin selected'), 'admin');
				$this->doView("plugins/index.php");
			}
			osc_add_flash_ok_message(_m('Configuration was saved'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
			break;

		default:
			require_once 'osc/helpers/hPlugins.php';
			require_once 'osc/helpers/hDefines.php';
			require_once 'osc/utils.php';

			$this->getView()->_exportVariableToView("plugins", $pluginManager->listAll());
			$this->doView("plugins/index.php");
		}
	}

	public function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}

