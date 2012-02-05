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
class Form_Alert extends Form
{
	public function user_id_hidden() 
	{
		parent::generic_input_hidden('alert_userId', osc_logged_user_id());
	}
	public function email_hidden() 
	{
		parent::generic_input_hidden('alert_email', osc_logged_user_email());
	}
	public function default_email_text() 
	{
		return __('Enter your e-mail');
	}
	public function email_text() 
	{
		$value = "";
		if (osc_logged_user_email() == '') 
		{
			$value = self::default_email_text();
		}
		parent::generic_input_text('alert_email', $value);
	}
	public function page_hidden() 
	{
		parent::generic_input_hidden('page', 'search');
	}
	public function alert_hidden() 
	{
		parent::generic_input_hidden('alert', osc_search_alert());
	}
}
