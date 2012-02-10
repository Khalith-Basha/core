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

class Theme
{
	private $theme;
	private $theme_url;
	private $theme_path;
	private $theme_exists;

	public function setCurrentTheme($theme) 
	{
		$this->theme = $theme;
		$this->setCurrentThemePath();
		$this->setCurrentThemeUrl();
	}

	public function getCurrentTheme() 
	{
		return $this->theme;
	}

	public function getCurrentThemeJs() 
	{
		return $this->theme_url . '/static/scripts';
	}

	public function getCurrentThemeStyles() 
	{
		return $this->theme_url . '/static/styles';
	}

	public function getCurrentThemePath() 
	{
		return $this->theme_path;
	}

	public function getCurrentThemeUrl() 
	{
		return $this->theme_url;
	}
}

class WebThemes extends Theme
{
	private $path;
	private $pages = array(
		'404', 'contact', 'alert-form', 'custom', 'footer', 'functions', 'head', 'header', 'inc.search', 'index', 'item-contact', 'item-edit', 'item-post', 'item-send-friend', 'item', 'main', 'page', 'search', 'search_gallery', 'search_list', 'user-alerts', 'user-change_email', 'user-change_password', 'user-dashboard', 'user-forgot_password', 'user-items', 'user-login', 'user-profile', 'user-recover', 'user-register'
	);

	public function __construct() 
	{
		$this->path = osc_themes_path();
		if (Params::getParam('theme') != '' && ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('adminId') != '')
			$this->setCurrentTheme(Params::getParam('theme'));
		else $this->setCurrentTheme(osc_theme());
		$functions_path = $this->getCurrentThemePath() . 'functions.php';
		if (file_exists($functions_path)) 
		{
			require_once $functions_path;
		}
	}

	private function setCurrentThemePath() 
	{
		if (file_exists($this->path . $this->theme . '/')) 
		{
			$this->theme_exists = true;
			$this->theme_path = $this->path . $this->theme . '/';
		}
		else
		{
			$this->theme_exists = false;
			$this->theme_path = osc_lib_path() . '/osc/gui';
		}
	}
	private function setCurrentThemeUrl() 
	{
		if ($this->theme_exists) 
		{
			$this->theme_url = osc_base_url() . '/' . str_replace(osc_base_path(), '', $this->theme_path);
		}
		else
		{
			$this->theme_url = osc_base_url() . '/components/themes/default/';
		}
	}
	/* PUBLIC */
	public function setPath($path) 
	{
		if (file_exists($path)) 
		{
			$this->path = $path;
			return true;
		}
		return false;
	}

	public function setGuiTheme() 
	{
		$this->theme = '';
		$this->theme_exists = false;
		$this->theme_path = ABS_PATH . '/components/themes/default';
		$this->theme_url = osc_base_url() . '/components/themes/default/';
		$functions_path = $this->getCurrentThemePath() . '/functions.php';
		if (file_exists($functions_path)) 
		{
			require_once $functions_path;
		}
	}
	/**
	 * This function returns an array of themes (those copied in the components/themes folder)
	 * @return <type>
	 */
	public function getListThemes() 
	{
		$themes = array();
		$dir = opendir($this->path);
		while ($file = readdir($dir)) 
		{
			if (preg_match('/^[a-z0-9_]+$/i', $file)) 
			{
				$themes[] = $file;
			}
		}
		closedir($dir);
		return $themes;
	}
	/**
	 *
	 * @param <type> $theme
	 * @return <type>
	 */
	function loadThemeInfo($theme) 
	{
		$path = $this->path . DIRECTORY_SEPARATOR . $theme . '/index.php';
		if( !file_exists( $path ) )
			throw new Exception( "Theme '$theme' does not have an 'index.php' file." );
		require_once $path;
		$fxName = $theme . '_theme_info';
		if( !function_exists( $fxName ) )
			throw new Exception( "Theme '$theme' does not have function '$fxName'." );
	
		$result = call_user_func($fxName);
		$result['int_name'] = $theme;
		return $result;
	}
	function isValidPage($internal_name) 
	{
		return !in_array($internal_name, $this->pages);
	}
}

class AdminThemes extends Theme
{
	public function __construct() 
	{
		$this->setCurrentTheme(osc_admin_theme());
	}

	private function setCurrentThemeUrl() 
	{
		$this->theme_url = osc_admin_base_url() . '/themes/' . $this->theme;
	}

	private function setCurrentThemePath() 
	{
		$this->theme_exists = true;
		$this->theme_path = osc_admin_base_path() . '/themes/' . $this->theme;
	}
}
