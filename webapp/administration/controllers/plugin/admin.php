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
class CAdminPlugin extends AdministrationController
{
	public function doModel() 
	{
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
		parent::doModel();
		switch ($this->action) 
		{
		case 'admin':
			global $active_plugins;
			$active_plugins = osc_active_plugins();
			$plugin = Params::getParam("plugin");
			if( !empty( $plugin ) )
			{
				$pluginManager->runHook($plugin . '_configure');
			}
			break;

		case 'admin_post':
			$pluginManager->runHook('admin_post');
		}
	}
}

