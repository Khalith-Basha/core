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

class Ui_AdminTheme extends Ui_Theme
{
	public function __construct() 
	{
		$this->setCurrentTheme(osc_admin_theme());
	}

	public function setCurrentThemeUrl() 
	{
		$this->theme_url = osc_admin_base_url() . '/themes/' . $this->theme;
	}

	public function setCurrentThemePath() 
	{
		$this->theme_exists = true;
		$this->theme_path = osc_admin_base_path() . '/themes/' . $this->theme;
	}

	public function setGuiTheme()
	{
	}
}
