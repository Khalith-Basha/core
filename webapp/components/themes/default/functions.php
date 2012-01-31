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
if (!function_exists('default_admin_menu')) 
{
	function default_admin_menu() 
	{
		echo '<h3><a href="#">' . __('Modern theme', 'modern') . '</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_theme_url('components/themes/modern/admin/admin_settings.php') . '">&raquo; ' . __('Settings theme', 'modern') . '</a></li>
            </ul>';
	}
	osc_add_hook('admin_menu', 'default_admin_menu');
}

