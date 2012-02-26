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
	public function __construct()
	{
		parent::__construct( osc_themes_path(), osc_theme() );
	}

	public function setCurrentThemePath() 
	{
		$themePath = $this->basePath . DIRECTORY_SEPARATOR . $this->theme;
		if( file_exists( $themePath ) )
		{
			$this->theme_exists = true;
			$this->theme_path = $themePath;
		}
	}

	public function setCurrentThemeUrl() 
	{
		if ($this->theme_exists) 
		{
			$this->themeUrl = $this->urlFactory->getBaseUrl() . '/' . str_replace(osc_base_path(), '', $this->theme_path);
		}
		else
		{
			$this->themeUrl = $this->urlFactory->getBaseUrl() . '/components/themes/default/';
		}
	}

	/**
	 * This function returns an array of themes (those copied in the components/themes folder)
	 *
	 * @return array
	 */
	public function getListThemes() 
	{
		$themes = array();
		$dir = opendir( $this->basePath );
		while( $file = readdir( $dir ) )
		{
			if (preg_match('/^[a-z0-9_]+$/i', $file)) 
			{
				$themes[] = $file;
			}
		}
		closedir( $dir );
		return $themes;
	}

	/**
	 * @return array
	 */
	public function loadThemeInfo( $theme )
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $theme . '/index.php';
		if( !file_exists( $path ) )
			throw new Exception( "Theme '$theme' does not have an 'index.php' file." );
		require_once $path;
		$fxName = $theme . '_theme_info';
		if( !function_exists( $fxName ) )
			throw new Exception( "Theme '$theme' does not have function '$fxName'." );
	
		$result = call_user_func($fxName);
		if( !is_array( $result ) )
			throw new Exception( "Function '$fxName' of theme '$theme' did not return an array." );
		$result['int_name'] = $theme;

		$screenshotPath = osc_base_url() . '/components/themes/' . $theme . '/screenshot.png';
		if( file_exists( $screenshotPath ) )
		{
			$result['screenshot'] = $screenshotPath;
		}

		return $result;
	}
}

