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
/**
 * Helper Defines
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets the root path for your installation
 *
 * @return string
 */
function osc_base_path() 
{
	return (ABS_PATH);
}
/**
 * Gets the root path of administration
 *
 * @return string
 */
function osc_admin_base_path() 
{
	return osc_base_path() . '/administration';
}
/**
 * Gets the content path
 *
 * @return string
 */
function osc_content_path() 
{
	return (CONTENT_PATH);
}
/**
 * Gets the themes path
 *
 * @return string
 */
function osc_themes_path() 
{
	return (THEMES_PATH);
}
/**
 * Gets the translations path
 *
 * @return string
 */
function osc_translations_path() 
{
	return (TRANSLATIONS_PATH);
}
/**
 * Gets the plugins path
 *
 * @return string
 */
function osc_plugins_path() 
{
	return (PLUGINS_PATH);
}

