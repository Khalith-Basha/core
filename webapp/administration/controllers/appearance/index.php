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
class CAdminAppearance extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = ClassLoader::getInstance();
		$themeManager = $classLoader->getClassInstance( 'Ui_MainTheme' );

		$view = $this->getView();
		$themeName = osc_theme();

		$currentTheme = null;
		$availableThemes = array();

		$themes = $themeManager->getListThemes();
		foreach( $themes as $theme )
		{
			if( $theme === $themeName )
				$currentTheme = $themeManager->loadThemeInfo( $theme );
			else
				$availableThemes[] = $themeManager->loadThemeInfo( $theme );
		}

		$screenshotPath = osc_base_url() . '/components/themes/' . $themeName . '/screenshot.png';
		if( file_exists( $screenshotPath ) )
		{
			$view->assign( 'screenshotPath', $screenshotPath );
		}

		$view->assign( 'currentTheme', $currentTheme );
		$view->assign( 'availableThemes', $availableThemes );
		$this->doView( 'appearance/index.php' );
	}
}

