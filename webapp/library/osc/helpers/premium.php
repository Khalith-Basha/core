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
 * Gets new premiums ads
 *
 * @return array $premiums
 */
function osc_get_premiums( $max = 2 )
{
	$mSearch = ClassLoader::getInstance()->getClassInstance( 'Model_Search' );
	$premiums = $mSearch->getPremiums( $max );
	return $premiums;
}
/**
 * Gets a specific field from current premium
 *
 * @param type $field
 * @param type $locale
 * @return field_type
 */
function osc_premium_field( array $premium, $field, $locale = "") 
{
	return osc_field( $premium, $field, $locale);
}

/**
 * Gets id from current premium
 *
 * @return int
 */
function osc_premium_id() 
{
	return (int)osc_premium_field("pk_i_id");
}
/**
 * Gets user id from current premium
 *
 * @return int
 */
function osc_premium_user_id() 
{
	return (int)osc_premium_field("fk_i_user_id");
}
/**
 * Gets description from current premium, if $locale is unspecified $locale is current user locale
 *
 * @param string $locale
 * @return string $desc
 */
function osc_premium_description($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	$desc = osc_premium_field("s_description", $locale);
	if ($desc == '') 
	{
		$desc = osc_premium_field("s_description", osc_language());
		if ($desc == '') 
		{
			$aLocales = osc_get_locales();
			foreach ($aLocales as $locale) 
			{
				$desc = osc_premium_field("s_description", $locale);
				if ($desc != '') 
				{
					break;
				}
			}
		}
	}
	return (string)$desc;
}
/**
 * Gets title from current premium, if $locale is unspecified $locale is current user locale
 *
 * @param string $locale
 * @return string
 */
function osc_premium_title($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	$title = osc_premium_field("s_title", $locale);
	if ($title == '') 
	{
		$title = osc_premium_field("s_title", osc_language());
		if ($title == '') 
		{
			$aLocales = osc_get_locales();
			foreach ($aLocales as $locale) 
			{
				$title = osc_premium_field("s_title", $locale);
				if ($title != '') 
				{
					break;
				}
			}
		}
	}
	return (string)$title;
}
/**
 * Gets category from current premium
 *
 * @param string $locale
 * @return string
 */
function osc_premium_category($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('premium_category')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('premium_category', ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->findByPrimaryKey(osc_premium_category_id()));
	}
	$category = ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_get('premium_category');
	return (string)osc_field($category, "s_name", $locale);
}
/**
 * Gets category description from current premium, if $locale is unspecified $locale is current user locale
 *
 * @param type $locale
 * @return string
 */
function osc_premium_category_description($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('premium_category')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('premium_category', ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->findByPrimaryKey(osc_premium_category_id()));
	}
	$category = ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_get('premium_category');
	return osc_field($category, "s_description", $locale);
}
/**
 * Gets category id of current premium
 *
 * @return int
 */
function osc_premium_category_id() 
{
	return (int)osc_premium_field("fk_i_category_id");
}
/**
 * Gets publication date of current premium
 *
 * @return string
 */
function osc_premium_pub_date() 
{
	return (string)osc_premium_field("dt_pub_date");
}
/**
 * Gets modification date of current premium
 *
 * @return string
 */
function osc_premium_mod_date() 
{
	return (string)osc_premium_field("dt_mod_date");
}
/**
 * Gets price of current premium
 *
 * @return float
 */
function osc_premium_price() 
{
	return (float)osc_premium_field("i_price");
}
/**
 * Gets formated price of current premium
 *
 * @return string
 */
function osc_premium_formated_price() 
{
	return (string)osc_format_price(osc_premium_field("i_price"));
}
/**
 * Gets currency of current premium
 *
 * @return string
 */
function osc_premium_currency() 
{
	return (string)osc_premium_field("fk_c_currency_code");
}
/**
 * Gets contact name of current premium
 *
 * @return string
 */
function osc_premium_contact_name() 
{
	return (string)osc_premium_field("s_contact_name");
}
/**
 * Gets contact email of current premium
 *
 * @return string
 */
function osc_premium_contact_email() 
{
	return (string)osc_premium_field("s_contact_email");
}
/**
 * Gets country name of current premium
 *
 * @return string
 */
function osc_premium_country() 
{
	return (string)osc_premium_field("s_country");
}
/**
 * Gets country code of current premium
 * Country code are two letters like US, ES, ...
 *
 * @return string
 */
function osc_premium_country_code() 
{
	return (string)osc_premium_field("fk_c_country_code");
}
/**
 * Gets region of current premium
 *
 * @return string
 */
function osc_premium_region() 
{
	return (string)osc_premium_field("s_region");
}
/**
 * Gets city of current premium
 *
 * @return string
 */
function osc_premium_city() 
{
	return (string)osc_premium_field("s_city");
}
/**
 * Gets city area of current premium
 *
 * @return string
 */
function osc_premium_city_area() 
{
	return (string)osc_premium_field("s_city_area");
}
/**
 * Gets address of current premium
 *
 * @return string
 */
function osc_premium_address() 
{
	return (string)osc_premium_field("s_address");
}
/**
 * Gets true if can show email user at frontend, else return false
 *
 * @return boolean
 */
function osc_premium_show_email() 
{
	return (boolean)osc_premium_field("b_show_email");
}
/**
 * Gets zup code of current premium
 *
 * @return string
 */
function osc_premium_zip() 
{
	return (string)osc_premium_field("s_zip");
}
/**
 * Gets latitude of current premium
 *
 * @return float
 */
function osc_premium_latitude() 
{
	return (float)osc_premium_field("d_coord_lat");
}
/**
 * Gets longitude of current premium
 *
 * @return float
 */
function osc_premium_longitude() 
{
	return (float)osc_premium_field("d_coord_long");
}
/**
 * Gets true if current premium is marked premium, else return false
 *
 * @return boolean
 */
function osc_premium_is_premium() 
{
	if (osc_premium_field("b_premium")) return true;
	else return false;
}
/**
 * return number of views of current premium
 *
 * @return int
 */
function osc_premium_views() 
{
	$item = osc_premium();
	if (isset($item['i_num_views'])) 
	{
		return (int)osc_premium_field("i_num_views");
	}
	else
	{
		return ItemClassLoader::getInstance()->getClassInstance( 'Stats' )->getViews(osc_premium_id());
	}
}
/**
 * Gets status of current premium.
 * b_active = true  -> premium is active
 * b_active = false -> premium is inactive
 *
 * @return boolean
 */
function osc_premium_status() 
{
	return (boolean)osc_premium_field("b_active");
}
/**
 * Gets secret string of current premium
 *
 * @return string
 */
function osc_premium_secret() 
{
	return (string)osc_premium_field("s_secret");
}
/**
 * Gets if current premium is active
 *
 * @return boolean
 */
function osc_premium_is_active() 
{
	return (osc_premium_field("b_active") == 1);
}
/**
 * Gets if current premium is inactive
 *
 * @return boolean
 */
function osc_premium_is_inactive() 
{
	return (osc_premium_field("b_active") == 0);
}
/**
 * Gets if premium is marked as spam
 *
 * @return boolean
 */
function osc_premium_is_spam() 
{
	return (osc_premium_field("b_spam") == 1);
}
/**
 * Gets total number of comments of current premium
 *
 * @return int
 */
function osc_premium_total_comments() 
{
	return premiumComment::newInstance()->total_comments(osc_premium_id());
}
/**
 * Gets page of comments in current pagination
 *
 * @return <type>
 */
function osc_premium_comments_page() 
{
	$page = Params::getParam('comments-page');
	if ($page == '') 
	{
		$page = 0;
	}
	return (int)$page;
}

/**
 * Set the internal pointer of array premiums to its first element, and return it.
 *
 * @return array
 */
function osc_reset_premiums() 
{
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_reset('premiums');
}
/**
 * Gets number of premiums in current array premiums
 *
 * @return int
 */
function osc_count_premiums() 
{
	return (int)ClassLoader::getInstance()->getClassInstance( 'View_Default' )->countVar('premiums');
}
/**
 * Gets number of resources in array resources of current premium
 *
 * @return int
 */
function osc_count_premium_resources() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('resources')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('resources', ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->getAllResources(osc_premium_id()));
	}
	return osc_priv_count_item_resources();
}
/**
 * Gets next premium resource if there is, else return null
 *
 * @return array
 */
function osc_has_premium_resources() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('resources')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('resources', ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->getAllResources(osc_premium_id()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_next('resources');
}
/**
 * Gets current resource of current array resources of current premium
 *
 * @return array
 */
function osc_get_premium_resources() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('resources')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('resources', ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->getAllResources(osc_premium_id()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_get('resources');
}
/**
 * Gets number of premium comments of current premium
 *
 * @return int
 */
function osc_count_premium_comments() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('comments')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('comments', ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->findBypremiumID(osc_premium_id(), osc_premium_comments_page(), osc_comments_per_page()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->countVar('comments');
}
/**
 * Gets next comment of current premium comments
 *
 * @return array
 */
function osc_has_premium_comments() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('comments')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('comments', ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->findBypremiumID(osc_premium_id(), osc_premium_comments_page(), osc_comments_per_page()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_next('comments');
}
/**
 * Gets number of premiums
 *
 * @access private
 * @return int
 */
function osc_priv_count_premiums() 
{
	return (int)ClassLoader::getInstance()->getClassInstance( 'View_Default' )->countVar('premiums');
}
/***************
 * META FIELDS *
 ***************/
/**
 * Gets number of premium meta field
 *
 * @return integer
 */
function osc_count_premium_meta() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('metafields')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('metafields', ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->metaFields(osc_premium_id()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->countVar('metafields');
}
/**
 * Gets next premium meta field if there is, else return null
 *
 * @return array
 */
function osc_has_premium_meta() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('metafields')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('metafields', ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->metaFields(osc_premium_id()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_next('metafields');
}
/**
 * Gets premium meta fields
 *
 * @return array
 */
function osc_get_premium_meta() 
{
	if (!ClassLoader::getInstance()->getClassInstance( 'View_Default' )->varExists('metafields')) 
	{
		ClassLoader::getInstance()->getClassInstance( 'View_Default' )->assign('metafields', ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->metaFields(osc_premium_id()));
	}
	return ClassLoader::getInstance()->getClassInstance( 'View_Default' )->_get('metafields');
}
