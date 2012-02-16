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

class Ui_MainTheme extends Ui_Theme
{
	private $path;
	private $pages = array(
		'404', 'contact', 'alert-form', 'custom', 'footer', 'functions', 'head', 'header', 'inc.search', 'index', 'item-contact', 'item-edit', 'item-post', 'item-send-friend', 'item', 'main', 'page', 'search', 'search_gallery', 'search_list', 'user-alerts', 'user-change_email', 'user-change_password', 'user-dashboard', 'user-forgot_password', 'user-items', 'user-login', 'user-profile', 'user-recover', 'user-register'
	);
	protected $urlFactory;

	public function __construct() 
	{
		$this->path = osc_themes_path();
		$classLoader = ClassLoader::getInstance();
		$this->urlFactory = $classLoader->getClassInstance( 'Url_Abstract' );
		if (Params::getParam('theme') != '' && $classLoader->getClassInstance( 'Session' )->_get('adminId') != '')
			$this->setCurrentTheme(Params::getParam('theme'));
		else $this->setCurrentTheme(osc_theme());
		$functions_path = $this->getCurrentThemePath() . 'functions.php';
		if (file_exists($functions_path)) 
		{
			require_once $functions_path;
		}
	}

	public function setCurrentThemePath() 
	{
		if (file_exists($this->path . $this->theme . '/')) 
		{
			$this->theme_exists = true;
			$this->theme_path = $this->path . $this->theme . '/';
		}
	}

	public function setCurrentThemeUrl() 
	{
		if ($this->theme_exists) 
		{
			$this->theme_url = $this->urlFactory->getBaseUrl() . '/' . str_replace(osc_base_path(), '', $this->theme_path);
		}
		else
		{
			$this->theme_url = $this->urlFactory->getBaseUrl() . '/components/themes/default/';
		}
	}

	public function setGuiTheme() 
	{
		$this->theme = '';
		$this->theme_exists = false;
		$this->theme_path = ABS_PATH . '/components/themes/default';
		$this->theme_url = $this->urlFactory->getBaseUrl() . '/components/themes/default/';
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
	public function loadThemeInfo($theme) 
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
	public function isValidPage($internal_name) 
	{
		return !in_array($internal_name, $this->pages);
	}
}

