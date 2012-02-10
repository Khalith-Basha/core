<?php

/**
 * Gets the current administration theme
 *
 * @return string
 */
function osc_current_admin_theme() 
{
	return (ClassLoader::getInstance()->getClassInstance( 'AdminThemes' )->getCurrentTheme());
}
/**
 * Gets the complete url of a given admin's file
 *
 * @param string $file the admin's file
 * @return string
 */
function osc_current_admin_theme_url($file = '') 
{
	return ClassLoader::getInstance()->getClassInstance( 'AdminThemes' )->getCurrentThemeUrl() . '/' . $file;
}
/**
 * Gets the complete path of a given admin's file
 *
 * @param string $file the admin's file
 * @return string
 */
function osc_current_admin_theme_path($file = '') 
{
	require ClassLoader::getInstance()->getClassInstance( 'AdminThemes' )->getCurrentThemePath() . '/' . $file;
}
/**
 * Gets the complete url of a given style's file
 *
 * @param string $file the style's file
 * @return string
 */
function osc_current_admin_theme_styles_url( $file ) 
{
	return ClassLoader::getInstance()->getClassInstance( 'AdminThemes' )->getCurrentThemeStyles() . '/' . $file;
}
/**
 * Gets the complete url of a given js's file
 *
 * @param string $file the js's file
 * @return string
 */
function osc_current_admin_theme_js_url($file = '') 
{
	return ClassLoader::getInstance()->getClassInstance( 'AdminThemes' )->getCurrentThemeJs() . '/' . $file;
}

