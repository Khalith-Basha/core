<?php

/**
 * Gets the complete path of a given file using the theme path as a root
 *
 * @param string $file
 * @return string
 */
function osc_render_view( $file, array $variables = array(), View $view = null ) 
{
	extract( $variables );

	$themes = ClassLoader::getInstance()->getClassInstance( 'WebThemes' );
	$viewContent = null;
	$webThemes = $themes;
	$filePath = $webThemes->getCurrentThemePath() . $file;
	if (!file_exists($filePath)) 
	{
		$webThemes->setGuiTheme();
		$filePath = $webThemes->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file;
	}
	if (file_exists($filePath)) 
	{
		ob_start();
		require $filePath;
		$viewContent = ob_get_contents();
		ob_end_clean();
	}
	else
	{
		trigger_error('File not found: ' . $filePath, E_USER_NOTICE);
	}
	return $viewContent;
}

