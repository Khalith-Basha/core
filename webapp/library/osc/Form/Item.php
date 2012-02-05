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
class Form_Item extends Form
{
	public function primary_input_hidden($item) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		parent::generic_input_hidden("id", $item["pk_i_id"]);
	}
	public function category_select($categories = null, $item = null, $default_item = null, $parent_selectable = false) 
	{
		// Did user select a specific category to post in?
		$catId = Params::getParam('catId');
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('catId') != "") 
		{
			$catId = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('catId');
		}
		if ($categories == null) 
		{
			if (ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_exists('categories')) 
			{
				$categories = ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_get('categories');
			}
			else
			{
				$categories = osc_get_categories();
			}
		}
		if ($item == null) 
		{
			$item = osc_item();
		}
		echo '<select name="catId" id="catId">';
		if (isset($default_item)) 
		{
			echo '<option value="">' . $default_item . '</option>';
		}
		else
		{
			echo '<option value="">' . __('Select a category') . '</option>';
		}
		if (count($categories) == 1) 
		{
			$parent_selectable = 1;
		};
		foreach ($categories as $c) 
		{
			if (!osc_selectable_parent_categories() && !$parent_selectable) 
			{
				echo '<optgroup label="' . $c['s_name'] . '">';
				if (isset($c['categories']) && is_array($c['categories'])) 
				{
					$this->subcategory_select($c['categories'], $item, $default_item, 1);
				}
			}
			else
			{
				$selected = ((isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']));
				echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected"' : '') . '>' . $c['s_name'] . '</option>';
				if (isset($c['categories']) && is_array($c['categories'])) 
				{
					$this->subcategory_select($c['categories'], $item, $default_item, 1);
				}
			}
		}
		echo '</select>';
	}
	public function subcategory_select($categories, $item, $default_item = null, $deep = 0) 
	{
		// Did user select a specific category to post in?
		$catId = Params::getParam('catId');
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('catId') != "") 
		{
			$catId = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('catId');
		}
		// How many indents to add?
		$deep_string = "";
		for ($var = 0; $var < $deep; $var++) 
		{
			$deep_string.= '&nbsp;&nbsp;';
		}
		$deep++;
		foreach ($categories as $c) 
		{
			$selected = ((isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']));
			echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected' . $item["fk_i_category_id"] . '"' : '') . '>' . $deep_string . $c['s_name'] . '</option>';
			if (isset($c['categories']) && is_array($c['categories'])) 
			{
				$this->subcategory_select($c['categories'], $item, $default_item, $deep);
			}
		}
	}
	public function user_select($users = null, $item = null, $default_item = null) 
	{
		if ($users == null) 
		{
			$users = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->listAll();
		};
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('userId') != "") 
		{
			$userId = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('userId');
		}
		else
		{
			$userId = '';
		};
		echo '<select name="userId" id="userId">';
		if (isset($default_item)) 
		{
			echo '<option value="">' . $default_item . '</option>';
		}
		foreach ($users as $user) 
		{
			$bool = false;
			if ($userId != '' && $userId == $user['pk_i_id']) 
			{
				$bool = true;
			}
			if ((isset($item["fk_i_user_id"]) && $item["fk_i_user_id"] == $user['pk_i_id'])) 
			{
				$bool = true;
			}
			echo '<option value="' . $user['pk_i_id'] . '"' . ($bool ? 'selected="selected"' : '') . '>' . $user['s_name'] . '</option>';
		}
		echo '</select>';
	}
	public function title_input($name, $locale = 'en_US', $value = '') 
	{
		parent::generic_input_text($name . '[' . $locale . ']', $value);
	}
	public function description_textarea($name, $locale = 'en_US', $value = '') 
	{
		parent::generic_textarea($name . '[' . $locale . ']', $value);
	}
	public function multilanguage_title_description($locales = null, $item = null) 
	{
		if ($locales == null) 
		{
			$locales = osc_get_locales();
		}
		if ($item == null) 
		{
			$item = osc_item();
		}
		$num_locales = count($locales);
		if ($num_locales > 1) 
		{
			echo '<div class="tabber">';
		};
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
			echo '<div class="title">';
			echo '<div>';
			echo '<label for="title">' . __('Title') . ' *';
			echo '<br /><a id="lowercaseTitle" href="#">Lowercase</a>';
			echo '</label></div>';
			$title = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '';
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('title') != "") 
			{
				$title_ = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('title');
				if ($title_[$locale['pk_c_code']] != "") 
				{
					$title = $title_[$locale['pk_c_code']];
				}
			}
			self::title_input('title', $locale['pk_c_code'], $title);
			echo '</div>';
			echo '<div class="description">';
			echo '<div>';
			echo '<label for="description">' . __('Description') . ' *';
			echo '<br /><a id="lowercaseDescription" href="#">Lowercase</a>';
			echo '</label></div>';
			$description = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '';
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('description') != "") 
			{
				$description_ = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('description');
				if ($description_[$locale['pk_c_code']] != "") 
				{
					$description = $description_[$locale['pk_c_code']];
				}
			}
			self::description_textarea('description', $locale['pk_c_code'], $description);
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
	public function price_input_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('price') != "") 
		{
			$item['i_price'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('price');
		}
		parent::generic_input_text('price', (isset($item['i_price'])) ? osc_prepare_price($item['i_price']) : null);
	}
	public function currency_select($currencies = null, $item = null) 
	{
		if ($currencies == null) 
		{
			$currencies = osc_get_currencies();
		};
		if ($item == null) 
		{
			$item = osc_item();
		}
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('currency') != "") 
		{
			$item['fk_c_currency_code'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('currency');
		}
		if (count($currencies) > 1) 
		{
			$default_key = null;
			$currency = osc_get_preference('currency');
			if (isset($item['fk_c_currency_code'])) 
			{
				$default_key = $item['fk_c_currency_code'];
			}
			elseif (is_array($currency)) 
			{
				if (isset($currency['s_value'])) 
				{
					$default_key = $currency['s_value'];
				}
			}
			parent::generic_select('currency', $currencies, 'pk_c_code', 's_description', null, $default_key);
		}
		else if (count($currencies) == 1) 
		{
			parent::generic_input_hidden("currency", $currencies[0]["pk_c_code"]);
			echo $currencies[0]['s_description'];
		}
	}
	public function country_select($countries = null, $item = null) 
	{
		if ($countries == null) 
		{
			$countries = osc_get_countries();
		};
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (count($countries) >= 1) 
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('countryId') != "") 
			{
				$item['fk_c_country_code'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('countryId');
			}
			parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select a country...'), (isset($item['fk_c_country_code'])) ? $item['fk_c_country_code'] : null);
		}
		else
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('country') != "") 
			{
				$item['s_country'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('country');
			}
			parent::generic_input_text('country', (isset($item['s_country'])) ? $item['s_country'] : null);
		}
	}
	public function country_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('country') != "") 
		{
			$item['s_country'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('country');
		}
		$only_one = false;
		if (!isset($item['s_country'])) 
		{
			$countries = osc_get_countries();
			if (count($countries) == 1) 
			{
				$item['s_country'] = $countries[0]['s_name'];
				$item['fk_c_country_code'] = $countries[0]['pk_c_code'];
				$only_one = true;
			}
		}
		parent::generic_input_text('countryName', (isset($item['s_country'])) ? $item['s_country'] : null, null, $only_one);
		parent::generic_input_hidden('countryId', (isset($item['fk_c_country_code']) && $item['fk_c_country_code'] != null) ? $item['fk_c_country_code'] : '');
	}
	public function region_select($regions = null, $item = null) 
	{
		// if have input text instead of select
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('region') != '') 
		{
			$regions = null;
		}
		else
		{
			if ($regions == null) 
			{
				$regions = osc_get_regions();
			};
		}
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (count($regions) >= 1) 
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('regionId') != "") 
			{
				$item['fk_i_region_id'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('regionId');
				if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('countryId') != "") 
				{
					$regions = Region::newInstance()->findByCountry(ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('countryId'));
				}
			}
			parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select a region...'), (isset($item['fk_i_region_id'])) ? $item['fk_i_region_id'] : null);
		}
		else
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('region') != "") 
			{
				$item['s_region'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('region');
			}
			parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null);
		}
	}
	public function city_select($cities = null, $item = null) 
	{
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('city') != '') 
		{
			$cities = null;
		}
		else
		{
			if ($cities == null) 
			{
				$cities = osc_get_cities();
			};
		}
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (count($cities) >= 1) 
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('cityId') != "") 
			{
				$item['fk_i_city_id'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('cityId');
				if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('regionId') != "") 
				{
					$cities = City::newInstance()->findByRegion(ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('regionId'));
				}
			}
			parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select a city...'), (isset($item['fk_i_city_id'])) ? $item['fk_i_city_id'] : null);
		}
		else
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('city') != "") 
			{
				$item['s_city'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('city');
			}
			parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null);
		}
	}
	public function region_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('region') != "") 
		{
			$item['s_region'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('region');
		}
		parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null, false, false);
		parent::generic_input_hidden('regionId', (isset($item['fk_i_region_id']) && $item['fk_i_region_id'] != null) ? $item['fk_i_region_id'] : '');
	}
	public function city_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('city') != "") 
		{
			$item['s_city'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('city');
		}
		parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null, false, false);
		parent::generic_input_hidden('cityId', (isset($item['fk_i_city_id']) && $item['fk_i_city_id'] != null) ? $item['fk_i_city_id'] : '');
	}
	public function city_area_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('cityArea') != "") 
		{
			$item['s_city_area'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('cityArea');
		}
		parent::generic_input_text('cityArea', (isset($item['s_city_area'])) ? $item['s_city_area'] : null);
		parent::generic_input_hidden('cityAreaId', (isset($item['fk_i_city_area_id']) && $item['fk_i_city_area_id'] != null) ? $item['fk_i_city_area_id'] : '');
	}
	public function address_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('address') != "") 
		{
			$item['s_address'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('address');
		}
		parent::generic_input_text('address', (isset($item['s_address'])) ? $item['s_address'] : null);
	}
	public function contact_name_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('contactName') != "") 
		{
			$item['s_contact_name'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('contactName');
		}
		parent::generic_input_text('contactName', (isset($item['s_contact_name'])) ? $item['s_contact_name'] : null);
	}
	public function contact_email_text($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('contactEmail') != "") 
		{
			$item['s_contact_email'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('contactEmail');
		}
		parent::generic_input_text('contactEmail', (isset($item['s_contact_email'])) ? $item['s_contact_email'] : null);
	}
	public function user_data_hidden() 
	{
		if (isset($_SESSION['userId']) && $_SESSION['userId'] != null) 
		{
			$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($_SESSION['userId']);
			parent::generic_input_hidden('contactName', $user['s_name']);
			parent::generic_input_hidden('contactEmail', $user['s_email']);
		}
		else
		{
			return false;
		}
	}
	public function show_email_checkbox($item = null) 
	{
		if ($item == null) 
		{
			$item = osc_item();
		};
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('showEmail') != 0) 
		{
			$item['b_show_email'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('showEmail');
		}
		parent::generic_input_checkbox('showEmail', '1', (isset($item['b_show_email'])) ? $item['b_show_email'] : false);
	}
	public function photos($resources = null) 
	{
		if ($resources == null) 
		{
			$resources = osc_get_item_resources();
		};
		if ($resources != null && is_array($resources) && count($resources) > 0) 
		{
			foreach ($resources as $_r) 
			{ ?>
                    <div id="<?php
				echo $_r['pk_i_id']; ?>" fkid="<?php
				echo $_r['fk_i_item_id']; ?>" name="<?php
				echo $_r['s_name']; ?>">
                        <img src="<?php
				echo osc_apply_filter('resource_path', osc_base_url() . $_r['s_path']) . $_r['pk_i_id'] . '_thumbnail.' . $_r['s_extension']; ?>" /><a href="javascript:delete_image(<?php
				echo $_r['pk_i_id'] . ", " . $_r['fk_i_item_id'] . ", '" . $_r['s_name'] . "', '" . Params::getParam('secret') . "'"; ?>);"  class="delete"><?php
				_e('Delete'); ?></a>
                    </div>
                <?php
			}
		}
	}
	public function plugin_edit_item() 
	{
		$this->plugin_post_item('edit&itemId=' . osc_item_id());
	}
}
