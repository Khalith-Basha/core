<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
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

class Ui_Theme
{
	protected $theme;
	protected $themeUrl;
	protected $basePath;
	protected $urlFactory;

	public function __construct( $themesPath, $themeName ) 
	{
		$classLoader = ClassLoader::getInstance();
		$this->urlFactory = $classLoader->getClassInstance( 'Url_Abstract' );

		$this->basePath = $themesPath;
		$this->theme = $themeName;
		$this->setCurrentThemePath();
		$this->setCurrentThemeUrl();
	}

	public function getCurrentTheme() 
	{
		return $this->theme;
	}

	public function getStaticUrl( $type, $resource = '' )
	{
		return $this->themeUrl . '/static/' . $type . $resource;
	}

	public function getStaticImageUrl( $resource )
	{
		return $this->getStaticUrl( $resource );
	}

	public function getCurrentThemeJs() 
	{
		return $this->getStaticUrl( 'scripts' );
	}

	public function getCurrentThemeStyles() 
	{
		return $this->getStaticUrl( 'styles' );
	}

	public function getCurrentThemePath() 
	{
		return $this->basePath . DIRECTORY_SEPARATOR . $this->theme;
	}

	public function getDefaultThemePath()
	{
		return $this->basePath . DIRECTORY_SEPARATOR . 'default';
	}

	public function getCurrentThemeUrl() 
	{
		return $this->themeUrl;
	}
}

