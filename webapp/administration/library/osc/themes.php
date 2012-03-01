<?php

/**
 * Gets the current administration theme
 *
 * @return string
 */
function osc_current_admin_theme() 
{
	return (ClassLoader::getInstance()->getClassInstance( 'Ui_AdminTheme' )->getCurrentTheme());
}

/**
 * Gets the complete url of a given admin's file
 *
 * @param string $file the admin's file
 * @return string
 */
function osc_current_admin_theme_url($file = '') 
{
	return ClassLoader::getInstance()->getClassInstance( 'Ui_AdminTheme' )->getCurrentThemeUrl() . '/' . $file;
}

/**
 * Gets the complete url of a given style's file
 *
 * @param string $file the style's file
 * @return string
 */
function osc_current_admin_theme_styles_url( $file ) 
{
	return ClassLoader::getInstance()->getClassInstance( 'Ui_AdminTheme' )->getCurrentThemeStyles() . '/' . $file;
}

/**
 * Gets the complete url of a given js's file
 *
 * @param string $file the js's file
 * @return string
 */
function osc_current_admin_theme_js_url($file = '') 
{
	return ClassLoader::getInstance()->getClassInstance( 'Ui_AdminTheme' )->getCurrentThemeJs() . '/' . $file;
}

