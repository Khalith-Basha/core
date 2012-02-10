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
 * Helper Categories
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets current category
 *
 * @return array
 */
function osc_category() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	if ($view->_exists('subcategories')) 
	{
		$category = $view->_current('subcategories');
	}
	elseif ($view->_exists('categories')) 
	{
		$category = $view->_current('categories');
	}
	elseif ($view->_exists('category')) 
	{
		$category = $view->_get('category');
	}
	else
	{
		$category = null;
	}
	return ($category);
}
/**
 * Low level function: Gets the list of categories as a tree
 *
 * <code>
 * <?php
 *  $c = osc_get_categories() ;
 * ?>
 * </code>
 *
 * @return <array>
 */
function osc_get_categories() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	$category = $classLoader->getClassInstance( 'Model_Category' );
	$view->assign('categories', $category->toTree());
	return $view->_get('categories');
}
function osc_field_toTree($item, $field) 
{
	if (isset($item[$field])) 
	{
		return $item[$field];
	}
	return null;
}
/**
 * Low level function: Gets the value of the category attribute
 *
 * @return <array>
 */
function osc_category_field($field, $locale = '') 
{
	return osc_field_toTree(osc_category(), $field);
}
/**
 * Gets the number of categories
 *
 * @return int
 */
function osc_priv_count_categories() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->_count('categories');
}
/**
 * Gets the number of subcategories
 *
 * @return int
 */
function osc_priv_count_subcategories() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	return $view->_count('subcategories');
}
/**
 * Gets the total of categories. If categories are not loaded, this function will load them.
 *
 * @return int
 */
function osc_count_categories() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->_exists('categories')) 
	{
		$category = $classLoader->getClassInstance( 'Model_Category' );
		$view->assign('categories', $category->toTree());
	}
	return osc_priv_count_categories();
}
/**
 * Let you know if there are more categories in the list. If categories are not loaded, this function will load them.
 *
 * @return boolean
 */
function osc_has_categories() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->_exists('categories')) 
	{
		$category = $classLoader->getClassInstance( 'Model_Category' );
		$view->assign('categories', $category->toTree());
	}
	return $view->_next('categories');
}
/**
 * Gets the total of subcategories for the current category. If subcategories are not loaded, this function will load them and
 * it will prepare the the pointer to the first element
 *
 * @return int
 */
function osc_count_subcategories() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	$category = $view->_current('categories');
	if ($category == '') return -1;
	if (!isset($category['categories'])) return 0;
	if (!is_array($category['categories'])) return 0;
	if (count($category['categories']) == 0) return 0;
	if (!$view->_exists('subcategories')) 
	{
		$view->assign('subcategories', $category['categories']);
	}
	return osc_priv_count_subcategories();
}
/**
 * Let you know if there are more subcategories for the current category in the list. If subcategories are not loaded, this
 * function will load them and it will prepare the pointer to the first element
 *
 * @return boolean
 */
function osc_has_subcategories() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	$category = $view->_current('categories');
	if ($category == '') return -1;
	if (!isset($category['categories'])) return false;
	if (!$view->_exists('subcategories')) 
	{
		$view->assign('subcategories', $category['categories']);
	}
	$ret = $view->_next('subcategories');
	//we have to delete for next iteration
	if (!$ret) $view->_erase('subcategories');
	return $ret;
}
/**
 * Gets the name of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_name($locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	return osc_category_field("s_name", $locale);
}
/**
 * Gets the description of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_description($locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	return osc_category_field("s_description", $locale);
}
/**
 * Gets the id of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_id($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	return osc_category_field("pk_i_id", $locale);
}
/**
 * Gets the slug of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_slug($locale = "") 
{
	if ($locale == "") $locale = osc_current_user_locale();
	return osc_category_field("s_slug", $locale);
}
/**
 * Gets the total items related with the current category
 *
 * @return int
 */
function osc_category_total_items() 
{
	return osc_category_field("i_num_items", "");
}
/**
 * Reset the pointer of the array to the first category
 *
 * @return void
 */
function osc_goto_first_category() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Html' );
	$view->_reset('categories');
}
/**
 * Gets list of non-empty categories
 *
 * @return void
 */
function osc_get_non_empty_categories() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	$category = $classLoader->getClassInstance( 'Model_Category' );
	$aCategories = $category->toTree(false);
	$view->assign('categories', $aCategories);
	return $view->_get('categories');
}
/**
 * Prints category select
 *
 * @return void
 */
function osc_categories_select($name = 'sCategory', $category = null, $default_str = null) 
{
	$classLoader = ClassLoader::getInstance();
	if ($default_str == null)
		$default_str = __('Select a category');
	$categoryModel = $classLoader->getClassInstance( 'Model_Category' );
	$categoryForm = $classLoader->getClassInstance( 'Form_Category' );
	$categoryForm->category_select($categoryModel->toTree(), $category, $default_str, $name);
}
