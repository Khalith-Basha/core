<?php

/**
 * Gets the current theme for the public website
 *
 * @return string
 */
function osc_current_web_theme() 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'WebThemes' );
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
	$themes = ClassLoader::getInstance()->getClassInstance( 'WebThemes' );
	$url = $themes->getCurrentThemeUrl() . $file;
	return $url;
}

function osc_get_current_web_theme_path( $file, View $view = null ) 
{
	$classLoader = ClassLoader::getInstance();
	$themes = $classLoader->getClassInstance( 'WebThemes' );
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
 * Gets the complete path of a given file using the theme path as a root
 *
 * @param string $file
 * @return string
 */
function osc_current_web_theme_path( $file, View $view = null ) 
{
	$path = osc_get_current_web_theme_path( $file, $view );
	if( !is_null( $path ) )
		require $path;
	else
		trigger_error( 'Path not found: ' . $path );
}
/**
 * Gets the complete path of a given styles file using the theme path as a root
 *
 * @param string $file
 * @return string
 */
function osc_current_web_theme_styles_url($file = '') 
{
	$themes = ClassLoader::getInstance()->getClassInstance( 'WebThemes' );
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
	$themes = ClassLoader::getInstance()->getClassInstance( 'WebThemes' );
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
/**
 * Gets urls for render custom files in front-end
 *
 * @param string $file must be a relative path, from PLUGINS_PATH
 * @return string
 */
function osc_render_file_url($file = '') 
{
	osc_sanitize_url($file);
	$file = str_replace("../", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file)));
	return osc_base_url(true) . '?page=custom&file=' . $file;
}
/**
 * Re-send the flash messages of the given section. Usefull for custom theme/plugins files.
 *
 * @param string $$section
 */
function osc_resend_flash_messages($section = "pubMessages") 
{
	$message = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getMessage($section);
	if ($message["type"] == "info") 
	{
		osc_add_flash_info_message($message['msg'], $section);
	}
	else if ($message["type"] == "ok") 
	{
		osc_add_flash_ok_message($message['msg'], $section);
	}
	else
	{
		osc_add_flash_error_message($message['msg'], $section);
	}
}
