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

class PluginManager
{
	private $hooks;

	public function __construct()
	{
		$this->hooks = array();
		ClassLoader::getInstance()->loadFile( 'helpers/plugins' );
	}

	public function loadPlugins()
	{
		foreach( $this->listAll() as $plugin )
		{
			$this->loadPlugin( $plugin );
		}
	}

	public function runHook( $hook )
	{
		$args = func_get_args();
		array_shift($args);
		if( isset( $this->hooks[$hook] ) )
		{
			for ($priority = 0; $priority <= 10; $priority++) 
			{
				if (isset( $this->hooks[$hook][$priority]) && is_array( $this->hooks[$hook][$priority])) 
				{
					foreach ( $this->hooks[$hook][$priority] as $fxName) 
					{
						if (function_exists($fxName)) 
						{
							call_user_func_array($fxName, $args);
						}
					}
				}
			}
		}
	}

	public function applyFilter( $hook, $content )
	{
		if( isset( $this->hooks[$hook] ) )
		{
			for ($priority = 0; $priority <= 10; $priority++) 
			{
				/* @TODO FIX */
				if (false&&isset( $this->hooks[$hook][$priority]) && is_array( $this->$hooks[$hook][$priority])) 
				{
					foreach ( $this->hooks[$hook][$priority] as $fxName) 
					{
						if (function_exists($fxName)) 
						{
							$content = call_user_func($fxName, $content);
						}
					}
				}
			}
		}
		return $content;
	}

	public function isInstalled($plugin) 
	{
		$p_installed = $this->listInstalled();
		foreach ($p_installed as $p) 
		{
			if ($p == $plugin) 
			{
				return true;
			}
		}
		return false;
	}

	public function isEnabled($plugin) 
	{
		$p_installed = $this->listEnabled();
		foreach ($p_installed as $p) 
		{
			if ($p == $plugin) 
			{
				return true;
			}
		}
		return false;
	}

	public function loadPlugin( $pluginName )
	{
		$pluginsPath = osc_plugins_path();
		$pluginPath = $pluginsPath . "/$pluginName/index.php";
		if( false === file_exists( $pluginPath ) )
			throw new Exception( 'Plugin path: ' . $pluginPath );

		require $pluginPath;
	}

	public function listAll() 
	{
		$plugins = array();
		$pluginsPath = osc_plugins_path();
		$dir = opendir($pluginsPath);
		while ($file = readdir($dir)) 
		{
			if (preg_match('/^[a-zA-Z0-9_]+$/', $file, $matches)) 
			{
				try
				{
					$plugins[] = $file;
				}
				catch ( Exception $e )
				{
					trigger_error( $e->getMessage() );
				}
			}
		}
		closedir($dir);
		return $plugins;
	}

	public function listInstalled() 
	{
		$p_array = array();
		try
		{
			$data['s_value'] = osc_installed_plugins();
			$plugins_list = unserialize($data['s_value']);
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $plugin_name) 
				{
					$p_array[] = $plugin_name;
				}
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
		return $p_array;
	}

	public function listEnabled() 
	{
		$p_array = array();
		try
		{
			$data['s_value'] = osc_active_plugins();
			$plugins_list = unserialize($data['s_value']);
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $plugin_name) 
				{
					$p_array[] = $plugin_name;
				}
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
		return $p_array;
	}

	public function resource($path) 
	{
		$fullPath = osc_plugins_path() . $path;
		return file_exists($fullPath) ? $fullPath : false;
	}

	public function activate($path) 
	{
		try
		{
			$data['s_value'] = osc_active_plugins();
			$plugins_list = unserialize($data['s_value']);
			$found_it = false;
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $plugin_name) 
				{
					// Check if the plugin is already installed
					if ($plugin_name == $path) 
					{
						$found_it = true;
						break;
					}
				}
			}
			if (!$found_it) 
			{
				$plugins_list[] = $path;
				$data['s_value'] = serialize($plugins_list);
				$condition = array('s_section' => 'osc', 's_name' => 'active_plugins');
				ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update($data, $condition);
				unset($condition);
				unset($data);
				$this->reload();
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
	}

	public function register($path, $function) 
	{
		$path = str_replace(osc_plugins_path(), '', $path);
		$this->addHook('install_' . $path, $function);
	}

	public function deactivate($path) 
	{
		try
		{
			$data['s_value'] = osc_active_plugins();
			$plugins_list = unserialize($data['s_value']);
			$path = str_replace(osc_plugins_path(), '', $path);
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $key => $value) 
				{
					if ($value == $path) 
					{
						unset($plugins_list[$key]);
					}
				}
				$data['s_value'] = serialize($plugins_list);
				$condition = array('s_section' => 'osc', 's_name' => 'active_plugins');
				ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update($data, $condition);
				unset($condition);
				unset($data);
				$this->reload();
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
	}

	public function install($path) 
	{
		try
		{
			$data['s_value'] = osc_installed_plugins();
			$plugins_list = unserialize($data['s_value']);
			$found_it = false;
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $plugin_name) 
				{
					// Check if the plugin is already installed
					if ($plugin_name == $path) 
					{
						$found_it = true;
						break;
					}
				}
			}
			$this->activate($path);
			if (!$found_it) 
			{
				$plugins_list[] = $path;
				$data['s_value'] = serialize($plugins_list);
				$condition = array('s_section' => 'osc', 's_name' => 'installed_plugins');
				ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update($data, $condition);
				unset($condition);
				unset($data);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
	}

	public function uninstall($path) 
	{
		try
		{
			$data['s_value'] = osc_installed_plugins();
			$plugins_list = unserialize($data['s_value']);
			$this->deactivate($path);
			$path = str_replace(osc_plugins_path(), '', $path);
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $key => $value) 
				{
					if ($value == $path) 
					{
						unset($plugins_list[$key]);
					}
				}
				$data['s_value'] = serialize($plugins_list);
				$condition = array('s_section' => 'osc', 's_name' => 'installed_plugins');
				ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update($data, $condition);
				unset($condition);
				unset($data);
				$plugin = $this->getInfo($path);
				$this->cleanCategoryFromPlugin($plugin['short_name']);
			}
		}
		catch(Exception $e) 
		{
			echo $e->getMessage();
		}
	}

	public function isThisCategory($name, $id) 
	{
		return ClassLoader::getInstance()->getClassInstance( 'Model_PluginCategory' )->isThisCategory($name, $id);
	}

	public function getInfo( $plugin )
	{
		$fxName = 'getPluginInfo_' . $plugin;
		if( !function_exists( $fxName ) )
			throw new Exception( 'Missing plugin info function: ' . $fxName );

		$pluginInfo = call_user_func( $fxName );
		$pluginInfo['int_name'] = $plugin;
		if( empty( $pluginInfo['name'] ) )
			throw new Exception( 'Missing required plugin info argument: name, ' . $plugin );

		$pluginInfo += array( 'description' => '' );

		return $pluginInfo;
	}

	public function checkUpdate( $plugin )
	{
		$info = $this->getInfo( $plugin );
		if( !empty( $info['plugin_update_uri'] ) )
		{
			$str = @file_get_contents( $info['plugin_update_uri'] );
			if( false === $str ) 
			{
				return false;
			}
			else
			{
				if (preg_match('|\?\(([^\)]+)|', preg_replace('/,\s*([\]}])/m', '$1', $str), $data)) 
				{
					$json = json_decode($data[1], true);
					if ($json['version'] > $info['version']) 
					{
						return true;
					}
				}
			}
		}
		return false;
	}

	public function configureView($path) 
	{
		$plugin = str_replace(osc_plugins_path(), '', $path);
		if (stripos($plugin, ".php") === FALSE) 
		{
			$plugins_list = unserialize(osc_active_plugins());
			if (is_array($plugins_list)) 
			{
				foreach ($plugins_list as $p) 
				{
					$data = $this->getInfo($p);
					if ($plugin == $data['plugin_name']) 
					{
						$plugin = $p;
						break;
					}
				}
			}
		}
		header('Location: ' . osc_plugin_configure_url($plugin));
		exit;
	}
	static function cleanCategoryFromPlugin($plugin) 
	{
		$dao_pluginCategory = ClassLoader::getInstance()->getClassInstance( 'PluginCategory' );
		$dao_pluginCategory->delete(array('s_plugin_name' => $plugin));
		unset($dao_pluginCategory);
	}
	static function addToCategoryPlugin($categories, $plugin) 
	{
		$dao_pluginCategory = new PluginCategory();
		$dao_category = new Category();
		if (!empty($categories)) 
		{
			foreach ($categories as $catId) 
			{
				$result = $dao_pluginCategory->isThisCategory($plugin, $catId);
				if ($result == 0) 
				{
					$fields = array();
					$fields['s_plugin_name'] = $plugin;
					$fields['fk_i_category_id'] = $catId;
					$dao_pluginCategory->insert($fields);
					$subs = $dao_category->findSubcategories($catId);
					if (is_array($subs) && count($subs) > 0) 
					{
						$cats = array();
						foreach ($subs as $sub) 
						{
							$cats[] = $sub['pk_i_id'];
						}
						$this->addToCategoryPlugin($cats, $plugin);
					}
				}
			}
		}
		unset($dao_pluginCategory);
		unset($dao_category);
	}

	public function addHook($hook, $function, $priority = 5) 
	{
		$hook = preg_replace('|/+|', '/', str_replace('\\', '/', $hook));
		$plugin_path = str_replace('\\', '/', osc_plugins_path());
		$hook = str_replace($plugin_path, '', $hook);
		$found_plugin = false;
		if (isset( $this->hooks[$hook])) 
		{
			if (is_array( $this->hooks[$hook])) 
			{
				foreach ( $this->hooks[$hook] as $fxName) 
				{
					if ($fxName == $function) 
					{
						$found_plugin = true;
						break;
					}
				}
			}
		}
		if (!$found_plugin) 
		{
			$this->hooks[$hook][$priority][] = $function;
		}
	}

	public function removeHook($hook, $function) 
	{
		unset( $this->hooks[$hook][$function]);
	}

	public function getRegisteredHooks() 
	{
		return $this->hooks;
	}

	public function reload() 
	{
		ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->toArray();
	}
}

