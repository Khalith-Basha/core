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
 * Gets current page url
 *
 * @param string $locale
 * @return string
 */
function osc_static_page_url( array $page, $locale = null )
{
	if ($locale != '') 
	{
		if (osc_rewrite_enabled()) 
		{
			return osc_base_url() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id") . "-" . $locale;
		}
		else
		{
			return osc_base_url(true) . "?page=page&id=" . osc_field( $page, "pk_i_id") . "&lang=" . $locale;
		}
	}
	else
	{
		if (osc_rewrite_enabled()) 
		{
			return osc_base_url() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id");
		}
		else
		{
			return osc_base_url(true) . "?page=page&id=" . osc_field( $page, "pk_i_id");
		}
	}
}

