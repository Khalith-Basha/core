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
 * Helper Items - returns object from the static class (View)
 * @package OpenSourceClassifieds
 * @subpackage Items
 * @author OpenSourceClassifieds
 */

/**
 * @param array $item
 * @return boolean Whether the item is considered old.
 */
function osc_itemIsOld( array $item )
{
	$classLoader = ClassLoader::getInstance();
	$preference = $classLoader->getClassInstance( 'Model_Preference' );
	$numDaysOld = $preference->get( 'item_num_days_old' );
	if( is_null( $numDaysOld ) )
		$numDaysOld = 1;

	$format = 'Y-m-d H:i:s';
	$pubDate = DateTime::createFromFormat( $format, $item['pub_date'] );
	$currentDate = new DateTime;
	$diff = $currentDate->diff( $pubDate, true );
	return $diff->days >= $numDaysOld;
}

/**
 * Gets current item array from view
 *
 * @return array $item, or null if not exist
 */
function osc_item() 
{
	$item = null;

	/* @TODO: View or HtmlView but not both. */
	$classLoader = ClassLoader::getInstance();

	$view = $classLoader->getClassInstance( 'View_Default' );
	if( $view->varExists( 'items' ) )
	{
		$item = $view->_current( 'items' );
	}
	elseif( $view->varExists( 'item' ) )
	{
		$item = $view->getVar( 'item' );
	}
	if( is_null( $item ) )
		$view = $classLoader->getClassInstance( 'View_Html' );
	if( $view->varExists( 'items' ) )
	{
		$item = $view->_current( 'items' );
	}
	elseif( $view->varExists( 'item' ) )
	{
		$item = $view->getVar( 'item' );
	}

	return $item;
}
/**
 * Gets a specific field from current item
 *
 * @param type $field
 * @param type $locale
 * @return field_type
 */
function osc_item_field( array $item, $field, $locale = "") 
{
	return osc_field( $item, $field, $locale);
}
/**
 * Gets a specific field from current comment
 *
 * @param type $field
 * @param type $locale
 * @return field_type
 */
function osc_comment_field( array $comment, $field, $locale = '') 
{
	return osc_field( $comment, $field, $locale);
}
/**
 * Gets a specific field from current resource
 *
 * @param type $field
 * @param type $locale
 * @return field_type
 */
function osc_resource_field( array $resource, $field, $locale = '') 
{
	return osc_field( $resource, $field, $locale);
}

/**
 * Gets id from current item
 *
 * @return int
 */
function osc_item_id( array $item ) 
{
	return (int)osc_item_field( $item, "pk_i_id");
}
/**
 * Gets user id from current item
 *
 * @return int
 */
function osc_item_user_id( array $item ) 
{
	return (int)osc_item_field( $item, "fk_i_user_id");
}
/**
 * Gets description from current item, if $locale is unspecified $locale is current user locale
 *
 * @param string $locale
 * @return string $desc
 */
function osc_item_description( array $item, $locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	$desc = osc_item_field( $item, "s_description", $locale);
	if ($desc == '') 
	{
		$desc = osc_item_field( $item, "s_description", osc_language());
		if ($desc == '') 
		{
			$aLocales = osc_get_locales();
			foreach ($aLocales as $locale) 
			{
				$desc = osc_item_field( $item, "s_description", $locale);
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
 * Gets title from current item, if $locale is unspecified $locale is current user locale
 *
 * @param string $locale
 * @return string
 */
function osc_item_title( array $item, $locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	$title = osc_item_field( $item, "s_title", $locale);
	if ($title == '') 
	{
		$title = osc_item_field( $item, "s_title", osc_language());
		if ($title == '') 
		{
			$aLocales = osc_get_locales();
			foreach ($aLocales as $locale) 
			{
				$title = osc_item_field( $item, "s_title", $locale);
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
 * Gets category from current item
 *
 * @param string $locale
 * @return string
 */
function osc_item_category( array $item, $locale = "") 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($locale == "")
		$locale = osc_current_user_locale();
	if (!$view->varExists('item_category')) 
	{
		$categoryId = osc_item_category_id( $item );
		$view->assign('item_category', $classLoader->getClassInstance( 'Model_Category' )->findByPrimaryKey( $categoryId ));
	}
	$category = $view->getVar('item_category');
	return (string)osc_field($category, "s_name", $locale);
}
/**
 * Gets category description from current item, if $locale is unspecified $locale is current user locale
 *
 * @param type $locale
 * @return string
 */
function osc_item_category_description($locale = "") 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($locale == "")
		$locale = osc_current_user_locale();
	if (!$view->varExists('item_category')) 
	{
		$view->assign('item_category', $classLoader->getClassInstance( 'Model_Category' )->findByPrimaryKey(osc_item_category_id()));
	}
	$category = $view->getVar('item_category');
	return osc_field($category, "s_description", $locale);
}
/**
 * Gets category id of current item
 *
 * @return int
 */
function osc_item_category_id( array $item ) 
{
	return (int)osc_item_field( $item, "fk_i_category_id");
}
/**
 * Gets publication date of current item
 *
 * @return string
 */
function osc_item_pub_date( array $item )
{
	return (string)osc_item_field( $item, "dt_pub_date");
}
/**
 * Gets modification date of current item
 *
 * @return string
 */
function osc_item_mod_date( array $item )
{
	return (string)osc_item_field( $item, "dt_mod_date");
}
/**
 * Gets price of current item
 *
 * @return float
 */
function osc_item_price( array $item ) 
{
	return (float)osc_item_field( $item, "i_price");
}
/**
 * Gets formated price of current item
 *
 * @return string
 */
function osc_item_formated_price( array $item ) 
{
	return (string)osc_format_price( $item, osc_item_field( $item, "i_price") );
}
/**
 * Gets currency of current item
 *
 * @return string
 */
function osc_item_currency( array $item ) 
{
	return (string)osc_item_field( $item, "fk_c_currency_code");
}
/**
 * Gets contact name of current item
 *
 * @return string
 */
function osc_item_contact_name( array $item ) 
{
	return (string)osc_item_field( $item, "s_contact_name");
}
/**
 * Gets contact email of current item
 *
 * @return string
 */
function osc_item_contact_email( array $item ) 
{
	return (string)osc_item_field( $item, "s_contact_email");
}
/**
 * Gets country name of current item
 *
 * @return string
 */
function osc_item_country( array $item ) 
{
	return (string)osc_item_field( $item, "s_country");
}
/**
 * Gets country code of current item
 * Country code are two letters like US, ES, ...
 *
 * @return string
 */
function osc_item_country_code( array $item ) 
{
	return (string)osc_item_field( $item, "fk_c_country_code");
}
/**
 * Gets region of current item
 *
 * @return string
 */
function osc_item_region( array $item )
{
	return (string)osc_item_field( $item, "s_region");
}
/**
 * Gets city of current item
 *
 * @return string
 */
function osc_item_city( array $item ) 
{
	return (string)osc_item_field( $item, "s_city");
}
/**
 * Gets city area of current item
 *
 * @return string
 */
function osc_item_city_area( array $item ) 
{
	return (string)osc_item_field( $item, "s_city_area");
}
/**
 * Gets address of current item
 *
 * @return string
 */
function osc_item_address( array $item ) 
{
	return (string)osc_item_field( $item, "s_address");
}
/**
 * Gets true if can show email user at frontend, else return false
 *
 * @return boolean
 */
function osc_item_show_email( array $item ) 
{
	return (boolean)osc_item_field( $item, "b_show_email");
}
/**
 * Gets zup code of current item
 *
 * @return string
 */
function osc_item_zip( array $item ) 
{
	return (string)osc_item_field( $item, "s_zip");
}
/**
 * Gets latitude of current item
 *
 * @return float
 */
function osc_item_latitude() 
{
	return (float)osc_item_field("d_coord_lat");
}
/**
 * Gets longitude of current item
 *
 * @return float
 */
function osc_item_longitude() 
{
	return (float)osc_item_field("d_coord_long");
}
/**
 * Gets true if current item is marked premium, else return false
 *
 * @return boolean
 */
function osc_item_is_premium( array $item )
{
	return osc_item_field( $item, "b_premium");
}
/**
 * return number of views of current item
 *
 * @return int
 */
function osc_item_views() 
{
	$item = osc_item();
	if (isset($item['i_num_views'])) 
	{
		return (int)osc_item_field("i_num_views");
	}
	else
	{
		$classLoader = ClassLoader::getInstance();
		return $classLoader->getClassInstance( 'Model_ItemStats' )->getViews(osc_item_id());
	}
}
/**
 * Return true if item is expired, else return false
 *
 * @return boolean
 */
function osc_item_is_expired( array $item ) 
{
	if (osc_item_is_premium( $item ) ) 
	{
		return false;
	}
	else
	{
		$classLoader = ClassLoader::getInstance();
		$category = $classLoader->getClassInstance( 'Model_Category' )->findByPrimaryKey( osc_item_category_id( $item ) );
		$expiration = $category['i_expiration_days'];
		if ($expiration == 0) 
		{
			return false;
		}
		else
		{
			$date_expiration = strtotime(date("Y-m-d H:i:s", strtotime( osc_item_pub_date( $item ) ) ) . " +$expiration day");
			$now = strtotime(date('Y-m-d H:i:s'));
			if ($date_expiration < $now) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}
/**
 * Gets status of current item.
 * b_active = true  -> item is active
 * b_active = false -> item is inactive
 *
 * @return boolean
 */
function osc_item_status( array $item ) 
{
	return (boolean)osc_item_field( $item, "b_active");
}
/**
 * Gets secret string of current item
 *
 * @return string
 */
function osc_item_secret( array $item ) 
{
	return (string)osc_item_field( $item, "s_secret");
}
/**
 * Gets if current item is active
 *
 * @return boolean
 */
function osc_item_is_active( array $item ) 
{
	return (osc_item_field( $item, "b_active") == 1);
}
/**
 * Gets if current item is inactive
 *
 * @return boolean
 */
function osc_item_is_inactive( array $item ) 
{
	return (osc_item_field( $item, "b_active") == 0);
}
/**
 * Gets if item is marked as spam
 *
 * @return boolean
 */
function osc_item_is_spam( array $item ) 
{
	return (osc_item_field( $item, "b_spam") == 1);
}

/**
 * Gets actual page for current pagination
 *
 * @return int
 */
function osc_list_page() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('list_page');
}
/**
 * Gets total of pages for current pagination
 *
 * @return int
 */
function osc_list_total_pages() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('list_total_pages');
}
/**
 * Gets number of items per page for current pagination
 *
 * @return <type>
 */
function osc_list_items_per_page() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->getVar('items_per_page');
}
/**
 * Gets total number of comments of current item
 *
 * @return int
 */
function osc_item_total_comments( array $item ) 
{
	$classLoader = ClassLoader::getInstance();
	return $classLoader->getClassInstance( 'Model_ItemComment' )->totalComments(osc_item_id( $item ));
}
/**
 * Gets page of comments in current pagination
 *
 * @return <type>
 */
function osc_item_comments_page() 
{
	$page = Params::getParam('comments-page');
	if ($page == '') 
	{
		$page = 0;
	}
	return (int)$page;
}

/**
 * Gets id of current comment
 *
 * @return int
 */
function osc_comment_id() 
{
	return (int)osc_comment_field("pk_i_id");
}
/**
 * Gets publication date of current comment
 *
 * @return string
 */
function osc_comment_pub_date() 
{
	return (string)osc_comment_field("dt_pub_date");
}
/**
 * Gets title of current commnet
 *
 * @return string
 */
function osc_comment_title( array $comment ) 
{
	return (string)osc_comment_field( $comment, "s_title");
}
/**
 * Gets author name of current comment
 *
 * @return string
 */
function osc_comment_author_name( array $comment ) 
{
	return (string)osc_comment_field( $comment, "s_author_name");
}
/**
 * Gets author email of current comment
 *
 * @return string
 */
function osc_comment_author_email( array $comment ) 
{
	return (string)osc_comment_field( $comment, "s_author_email");
}
/**
 * Gets body of current comment
 *
 * @return string
 */
function osc_comment_body( array $comment ) 
{
	return (string)osc_comment_field( $comment, "s_body");
}
/**
 * Gets user id of current comment
 *
 * @return int
 */
function osc_comment_user_id( array $comment ) 
{
	return (int)osc_comment_field( $comment, "fk_i_user_id");
}
/**
 * Gets  link to delete the current comment of current item
 *
 * @return string
 */
function osc_delete_comment_url() 
{
	$urlFactory = ClassLoader::getInstance()->getClassInstance( 'Url_Index' );
	return (string)$urlFactory->getBaseUrl(true) . "?page=item&action=delete_comment&id=" . osc_item_id() . "&comment=" . osc_comment_id();
}

/**
 * Gets id of current resource
 *
 * @return int
 */
function osc_resource_id( array $resource ) 
{
	return (int)osc_resource_field( $resource, "pk_i_id");
}
/**
 * Gets name of current resource
 *
 * @return string
 */
function osc_resource_name( array $resource ) 
{
	return (string)osc_resource_field( $resource, "s_name");
}
/**
 * Gets content type of current resource
 *
 * @return string
 */
function osc_resource_type( array $resource ) 
{
	return (string)osc_resource_field( $resource, "s_content_type");
}
/**
 * Gets extension of current resource
 *
 * @return string
 */
function osc_resource_extension( array $resource ) 
{
	return (string)osc_resource_field( $resource, "s_extension");
}
/**
 * Gets path of current resource
 *
 * @return string
 */
function osc_resource_path( array $resource ) 
{
	$urlFactory = ClassLoader::getInstance()->getClassInstance( 'Url_Index' );
	return (string)osc_apply_filter('resource_path', $urlFactory->getBaseUrl() . '/'. osc_resource_field( $resource, "s_path"));
}

/**
 * Gets number of items in current array items
 *
 * @return int
 */
function osc_count_items() 
{
	return osc_priv_count_items();
}
/**
 * Gets number of resources in array resources of current item
 *
 * @return int
 */
function osc_count_item_resources( array $item ) 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('resources')) 
	{
	}
	return osc_priv_count_item_resources();
}
/**
 * Gets next item resource if there is, else return null
 *
 * @return array
 */
function osc_has_item_resources() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if (!$view->varExists('resources')) 
	{
		$itemResourceManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' );
		$view->assign('resources', $itemResourceManager->getAllResources(osc_item_id()));
	}
	return $view->_next('resources');
}
/**
 * Gets current resource of current array resources of current item
 *
 * @return array
 */
function osc_get_item_resources( array $item ) 
{
	$itemResourceManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' );
	return $itemResourceManager->getAllResources(osc_item_id( $item ));
}
/**
 * Gets next comment of current item comments
 *
 * @return array
 */
function osc_has_item_comments() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('comments')) 
	{
		$view->assign('comments', $classLoader->getClassInstance( 'Model_ItemComment' )->findByItemID(osc_item_id(), osc_item_comments_page(), osc_comments_per_page()));
	}
	return $view->_next('comments');
}

/**
 * Formats the price using the appropiate currency.
 *
 * @param float $price
 * @return string
 */
function osc_format_price( array $item, $price )
{
	if ($price == null) return osc_apply_filter('item_price_null', __('Check with seller'));
	if ($price == 0) return osc_apply_filter('item_price_zero', __('Free'));
	$price = $price / 1000000;
	$currencyFormat = osc_locale_currency_format();
	$currencyFormat = str_replace('{NUMBER}', number_format($price, osc_locale_num_dec(), osc_locale_dec_point(), osc_locale_thousands_sep()), $currencyFormat);
	$currencyFormat = str_replace('{CURRENCY}', osc_item_currency( $item ), $currencyFormat);
	return osc_apply_filter('item_price', $currencyFormat);
}
/**
 * Gets number of items
 *
 * @access private
 * @return int
 */
function osc_priv_count_items() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return (int)$view->countVar('items');
}
/**
 * Gets number of item resources
 *
 * @access private
 * @return int
 */
function osc_priv_count_item_resources() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return (int)$view->countVar('resources');
}

/**
 * Gets number of item meta field
 *
 * @return integer
 */
function osc_count_item_meta( array $item ) 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('metafields')) 
	{
		$view->assign('metafields', $classLoader->getClassInstance( 'Model_Item' )->metaFields( osc_item_id( $item ) ) );
	}
	return $view->countVar('metafields');
}
/**
 * Gets next item meta field if there is, else return null
 *
 * @return array
 */
function osc_has_item_meta( array $item ) 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('metafields')) 
	{
		$view->assign('metafields', $classLoader->getClassInstance( 'Model_Item' )->metaFields(osc_item_id( $item )));
	}
	return $view->_next('metafields');
}
/**
 * Gets item meta fields
 *
 * @return array
 */
function osc_get_item_meta( array $item ) 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->varExists('metafields')) 
	{
		$view->assign('metafields', $classLoader->getClassInstance( 'Model_Item' )->metaFields(osc_item_id( $item )));
	}
	return $view->getVar('metafields');
}
/**
 * Gets item meta field
 *
 * @return array
 */
function osc_item_meta( array $item ) 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->_current('metafields');
}
/**
 * Gets item meta value
 *
 * @return string
 */
function osc_item_meta_value( array $item ) 
{
	return htmlentities(osc_field(osc_item_meta( $item ), 's_value', ''), ENT_COMPAT, "UTF-8");
}
/**
 * Gets item meta name
 *
 * @return string
 */
function osc_item_meta_name() 
{
	return osc_field(osc_item_meta(), 's_name', '');
}
/**
 * Gets item meta id
 *
 * @return integer
 */
function osc_item_meta_id() 
{
	return osc_field(osc_item_meta(), 'pk_i_id', '');
}
/**
 * Gets item meta slug
 *
 * @return string
 */
function osc_item_meta_slug() 
{
	return osc_field(osc_item_meta(), 's_slug', '');
}

/**
 * Gets the pagination links of comments pagination
 *
 * @return string pagination links
 */
function osc_comments_pagination( array $item ) 
{
	$classLoader = ClassLoader::getInstance();
	$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
	if ((osc_comments_per_page() == 0) || (osc_item_comments_page() === 'all')) 
	{
		return null;
	}
	else
	{
		$params = array('total' => ceil(osc_item_total_comments( $item ) / osc_comments_per_page()), 'selected' => osc_item_comments_page(), 'url' => $itemUrls->osc_item_comments_url( $item, '{PAGE}'));
		return $classLoader->getClassInstance( 'Pagination', true, array( $params ) )
			->showLinks();
	}
}

