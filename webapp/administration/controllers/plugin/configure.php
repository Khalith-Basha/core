<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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
class CAdminPlugin extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
		$plugin = Params::getParam("plugin");
		if ($plugin != '') 
		{
			$plugin_data = $pluginManager->getInfo($plugin);
			$this->getView()->assign("categories", ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->toTreeAll());
			$this->getView()->assign("selected", PluginClassLoader::getInstance()->getClassInstance( 'Model_Category' )->listSelected($plugin_data['short_name']));
			$this->getView()->assign("plugin_data", $plugin_data);
			$this->doView("plugins/configuration.php");
		}
		else
		{
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
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
	}
}

