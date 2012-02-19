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
	if ($view->varExists('subcategories')) 
	{
		$category = $view->_current('subcategories');
	}
	elseif ($view->varExists('categories')) 
	{
		$category = $view->_current('categories');
	}
	elseif ($view->varExists('category')) 
	{
		$category = $view->getVar('category');
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
	return $view->getVar('categories');
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
function osc_category_field( array $category, $field, $locale = '') 
{
	return osc_field_toTree( $category, $field );
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
	if (!$view->varExists('categories')) 
	{
		$category = $classLoader->getClassInstance( 'Model_Category' );
		$view->assign('categories', $category->toTree());
	}
	return $view->countVar('subcategories');
}
/**
 * Gets the name of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_name( array $item, $locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	return osc_category_field( $item, "s_name", $locale);
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
function osc_category_id( array $category, $locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	return osc_category_field( $category, "pk_i_id", $locale);
}
/**
 * Gets the slug of the current category
 *
 * @param string $locale
 * @return string
 */
function osc_category_slug( array $item, $locale = "") 
{
	if ($locale == "")
		$locale = osc_current_user_locale();
	return osc_category_field( $item, "s_slug", $locale);
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
	return $aCategories;
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
