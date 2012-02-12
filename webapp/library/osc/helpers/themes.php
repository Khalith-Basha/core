<?php

/**
 * Gets the current theme for the public website
 *
 * @return string
 */
function osc_current_web_theme() 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' );
	return $themes->getCurrentTheme();
}
/**
 * Gets the complete url of a given file using the theme url as a root
 *
 * @param string $file the given file
 * @return string
 */
function osc_current_web_theme_url($file = '') 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' );
	$url = $themes->getCurrentThemeUrl() . $file;
	return $url;
}

function osc_get_current_web_theme_path( $file, View $view = null ) 
{
	$classLoader = ClassLoader::getInstance();
	$themes = $classLoader->getClassInstance( 'Ui_MainTheme' );
	if( is_null( $view ) )
		$view = $classLoader->getClassInstance( 'View_Html' );
	$webThemes = $themes;
	$filePath = $webThemes->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file;
	if (file_exists($filePath)) 
	{
		return $filePath;
	}
	else
	{
		$webThemes->setGuiTheme();
		$filePath = $webThemes->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file;
		if (file_exists($filePath)) 
		{
			return $filePath;
		}
		else
		{
			trigger_error('File not found: ' . $filePath, E_USER_NOTICE);
			return null;
		}
	}
}

/**
 * Gets the complete path of a given styles file using the theme path as a root
 *
 * @param string $file
 * @return string
 */
function osc_current_web_theme_styles_url($file = '') 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' );
	return $themes->getCurrentThemeStyles() . $file;
}
/**
 * Gets the complete path of a given js file using the theme path as a root
 *
 * @param string $file
 * @return string
 */
function osc_current_web_theme_js_url($file = '') 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' );
	return $themes->getCurrentThemeJs() . $file;
}

/**
 * Gets urls for current theme administrations options
 *
 * @param string $file must be a relative path, from ABS_PATH
 * @return string
 */
function osc_admin_render_theme_url($file = '') 
{
	return osc_admin_base_url(true) . '?page=plugin&action=render&file=' . $file;
}
/**
 * Render the specified file
 *
 * @param string $file must be a relative path, from PLUGINS_PATH
 */
function osc_render_file($file = '') 
{
	if ($file == '') 
	{
		$file = __get('file');
	}
	// Clean $file to prevent hacking of some type
	osc_sanitize_url($file);
	$file = str_replace("../", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file)));
	include osc_plugins_path() . $file;
}

