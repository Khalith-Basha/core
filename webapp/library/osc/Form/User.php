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
class Form_User extends Form
{
	public function primary_input_hidden($user) 
	{
		parent::generic_input_hidden("id", (isset($user["pk_i_id"]) ? $user['pk_i_id'] : ''));
	}
	public function name_text($user = null) 
	{
		parent::generic_input_text("s_name", isset($user['s_name']) ? $user['s_name'] : '', null, false);
	}
	public function email_login_text($user = null) 
	{
		parent::generic_input_text("email", isset($user['s_email']) ? $user['s_email'] : '', null, false);
	}
	public function password_login_text($user = null) 
	{
		parent::generic_password("password", '', null, false);
	}
	public function old_password_text($user = null) 
	{
		parent::generic_password("old_password", '', null, false);
	}
	public function password_text($user = null) 
	{
		parent::generic_password("s_password", '', null, false);
	}
	public function check_password_text($user = null) 
	{
		parent::generic_password("s_password2", '', null, false);
	}
	public function email_text($user = null) 
	{
		parent::generic_input_text("s_email", isset($user['s_email']) ? $user['s_email'] : '', null, false);
	}
	public function website_text($user = null) 
	{
		parent::generic_input_text("s_website", isset($user['s_website']) ? $user['s_website'] : '', null, false);
	}
	public function mobile_text($user = null) 
	{
		parent::generic_input_text("s_phone_mobile", isset($user['s_phone_mobile']) ? $user['s_phone_mobile'] : '', null, false);
	}
	public function phone_land_text($user = null) 
	{
		parent::generic_input_text("s_phone_land", isset($user['s_phone_land']) ? $user['s_phone_land'] : '', null, false);
	}
	public function info_textarea($name, $locale = 'en_US', $value = '') 
	{
		parent::generic_textarea($name . '[' . $locale . ']', $value);
	}
	public function multilanguage_info($locales, $user = null) 
	{
		$num_locales = count($locales);
		if ($num_locales > 1) 
		{
			echo '<div class="tabber">';
		}
		foreach ($locales as $locale) 
		{
			if ($num_locales > 1) 
			{
				echo '<div class="tabbertab">';
			};
			if ($num_locales > 1) 
			{
				echo '<h2>' . $locale['s_name'] . '</h2>';
			};
			echo '<div class="description">';
			echo '<div><label for="description">' . __('User Description') . '</label></div>';
			$info = '';
			if (is_array($user)) 
			{
				if (isset($user['locale'][$locale['pk_c_code']])) 
				{
					if (isset($user['locale'][$locale['pk_c_code']]['s_info'])) 
					{
						$info = $user['locale'][$locale['pk_c_code']]['s_info'];
					}
				}
			}
			self::info_textarea('s_info', $locale['pk_c_code'], $info);
			echo '</div>';
			if ($num_locales > 1) 
			{
				echo '</div>';
			};
		}
		if ($num_locales > 1) 
		{
			echo '</div>';
		};
	}
	public function country_select($countries, $user = null) 
	{
		if (count($countries) >= 1) 
		{
			parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select a country...'), (isset($user['fk_c_country_code'])) ? $user['fk_c_country_code'] : null);
		}
		else
		{
			parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null);
		}
	}
	public function country_text($user = null) 
	{
		parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null);
	}
	public function region_select($regions, $user = null) 
	{
		if (count($regions) >= 1) 
		{
			parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select a region...'), (isset($user['fk_i_region_id'])) ? $user['fk_i_region_id'] : null);
		}
		else
		{
			parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null);
		}
	}
	public function region_text($user = null) 
	{
		parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null);
	}
	public function city_select($cities, $user = null) 
	{
		if (count($cities) >= 1) 
		{
			parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select a city...'), (isset($user['fk_i_city_id'])) ? $user['fk_i_city_id'] : null);
		}
		else
		{
			parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null);
		}
	}
	public function city_text($user = null) 
	{
		parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null);
	}
	public function city_area_text($user = null) 
	{
		parent::generic_input_text('cityArea', (isset($user['s_city_area'])) ? $user['s_city_area'] : null);
	}
	public function address_text($user = null) 
	{
		parent::generic_input_text('address', (isset($user['s_address'])) ? $user['s_address'] : null);
	}
	public function is_company_select($user = null) 
	{
		$options = array(array('i_value' => '0', 's_text' => __('User')), array('i_value' => '1', 's_text' => __('Company')));
		parent::generic_select('b_company', $options, 'i_value', 's_text', null, (isset($user['b_company'])) ? $user['b_company'] : null);
	}
	public function user_select($users) 
	{
		Form::generic_select('userId', $users, 'pk_i_id', 's_name', __('All'), NULL);
	}
}
