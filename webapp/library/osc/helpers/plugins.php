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
/**
 * Helper Plugins
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Run a hook
 *
 * @param string $hook
 * @return void
 */
function osc_run_hook($hook) 
{
	global $pluginManager;
	$args = func_get_args();
	call_user_func_array(array( $pluginManager, 'runHook'), $args);
}
/**
 * Apply a filter to a text
 *
 * @param string $hook
 * @param string $content
 * @return boolean
 */
function osc_apply_filter($hook, $content) 
{
	global $pluginManager;
	return $pluginManager->applyFilter($hook, $content);
}
/**
 * Add a hook
 *
 * @param string $hook
 * @param string $function
 * @param int $priority
 * @return void
 */
function osc_add_hook($hook, $function, $priority = 5) 
{
	global $pluginManager;
	$pluginManager->addHook($hook, $function, $priority);
}
/**
 * Add a filter
 *
 * @param string $hook
 * @param string $function
 * @param int $priority
 * @return void
 */
function osc_add_filter($hook, $function, $priority = 5) 
{
	global $pluginManager;
	$pluginManager->addHook($hook, $function, $priority);
}
/**
 * Remove a hook's function
 *
 * @param string $hook
 * @param string $function
 * @return void
 */
function osc_remove_hook($hook, $function) 
{
	global $pluginManager;
	$pluginManager->removeHook($hook, $function);
}
/**
 * Remove a filter's function
 *
 * @param string $hook
 * @param string $function
 * @return void
 */
function osc_remove_filter($hook, $function) 
{
	global $pluginManager;
	$pluginManager->removeHook($hook, $function);
}
/**
 * If the plugin is attached to the category
 *
 * @param string $name
 * @param int $id
 * @return boolean
 */
function osc_is_this_category($name, $id) 
{
	global $pluginManager;
	return $pluginManager->isThisCategory($name, $id);
}
/**
 * Check if there's a new version of the plugin
 *
 * @param string $plugin
 * @return boolean
 */
function osc_plugin_check_update($plugin) 
{
	global $pluginManager;
	return $pluginManager->checkUpdate($plugin);
}
/**
 * Register a plugin file to be loaded
 *
 * @param string $path
 * @param string $function
 * @return void
 */
function osc_register_plugin($path, $function) 
{
	global $pluginManager;
	$pluginManager->register($path, $function);
}
/**
 * Show the default configure view for plugins (attach them to categories)
 *
 * @param string $plugin
 * @return boolean
 */
function osc_plugin_configure_view($plugin) 
{
	global $pluginManager;
	return $pluginManager->configureView($plugin);
}
/**
 * Gets the path to a plugin's resource
 *
 * @param string $file
 * @return string
 */
function osc_plugin_resource($file) 
{
	global $pluginManager;
	return $pluginManager->resource($file);
}
/**
 * Gets plugin's configure url
 *
 * @param string $plugin
 * @return string
 */
function osc_plugin_configure_url($plugin) 
{
	return osc_admin_base_url(true) . '?page=plugin&action=configure&plugin=' . $plugin;
}
/**
 * Gets the path for ajax
 *
 * @param string $file
 * @return string
 */
function osc_ajax_plugin_url($file = '') 
{
	$file = preg_replace('|/+|', '/', str_replace('\\', '/', $file));
	$plugin_path = str_replace('\\', '/', osc_plugins_path());
	$file = str_replace($plugin_path, '', $file);
	return (osc_base_url(true) . "?page=ajax&action=custom&ajaxfile=" . $file);
}
/**
 * Gets the configure admin's url
 *
 * @param string $file
 * @return string
 */
function osc_admin_configure_plugin_url($file = '') 
{
	$file = preg_replace('|/+|', '/', str_replace('\\', '/', $file));
	$plugin_path = str_replace('\\', '/', osc_plugins_path());
	$file = str_replace($plugin_path, '', $file);
	return osc_admin_base_url(true) . '?page=plugin&action=configure&plugin=' . $file;
}
/**
 * Gets urls for custom plugin administrations options
 *
 * @param string $file
 * @return string
 */
function osc_admin_render_plugin_url($file = '') 
{
	$file = preg_replace('|/+|', '/', str_replace('\\', '/', $file));
	$plugin_path = str_replace('\\', '/', osc_plugins_path());
	$file = str_replace($plugin_path, '', $file);
	return osc_admin_base_url(true) . '?page=plugin&action=renderplugin&file=' . $file;
}
/**
 * Show custom plugin administrationfile
 *
 * @param string $file
 * @return void
 */
function osc_admin_render_plugin($file = '') 
{
	header('Location: ' . osc_admin_render_plugin_url($file));
	exit;
}

/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_url($file) 
{
	// Sanitize windows paths and duplicated slashes
	$dir = preg_replace('|/+|', '/', str_replace('\\', '/', dirname($file)));
	$dir = WEB_PATH . 'components/plugins/' . preg_replace('#^.*components\/plugins\/#', '', $dir) . "/";
	return $dir;
}
/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_folder($file) 
{
	// Sanitize windows paths and duplicated slashes
	$dir = preg_replace('|/+|', '/', str_replace('\\', '/', dirname($file)));
	$dir = preg_replace('#^.*components\/plugins\/#', '', $dir) . "/";
	return $dir;
}

