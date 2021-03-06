<?php
/************************************************************************
 * CSS and Javascript Combinator 0.5
 * Copyright 2006 by Niels Leenheer
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function osc_get_current_web_theme_path( $file, View $view = null ) 
{
	if( empty( $file ) )
	{
		return null;
	}
	
	$classLoader = ClassLoader::getInstance();
	$themes = $classLoader->getClassInstance( 'Ui_MainTheme' );
	if( is_null( $view ) )
		$view = $classLoader->getClassInstance( 'View_Html' );
	$webThemes = $themes;

	$paths = array(
		$webThemes->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file,
		$webThemes->getDefaultThemePath() . DIRECTORY_SEPARATOR . $file,
	);

	foreach( $paths as $path )
	{
		if( file_exists( $path ) )
		{
			return $path;
		}
	}

	trigger_error('File not found: ' . $file, E_USER_NOTICE);
	return null;
}


$cache = false;
$cachedir = '../../uploads';

$type = $_GET['type'];
$elements = explode( ',', $_GET['files'] );

$contentType = 'javaScript' == $type ? 'javascript' : 'css';
$suffix = 'javaScript' === $type ? '.js' : '.css';
$folder = 'javaScript' === $type ? 'scripts' : 'styles';
$elementsPath = array();

// Determine last modification date of the files
$lastmodified = 0;
foreach( $elements as $element )
{
	if( empty( $element ) )
		continue;

	if( strstr( $element, '..' ) )
	{
		header( 'HTTP/1.0 403 Forbidden' );
		die( 'Forbidden: ' . $element );
	}

	$path = osc_get_current_web_theme_path( 'static/'. $folder . '/' . $element . $suffix );
	if( !file_exists( $path ) )
	{
		header( 'HTTP/1.0 404 Not Found' );
		die( 'Not found: ' . $path );
	}
	$elementsPath[] = $path;
	$lastmodified = max($lastmodified, filemtime($path));
}
// Send Etag hash
$hash = $lastmodified . '-' . md5($_GET['files']);
header("Etag: \"" . $hash . "\"");
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') 
{
	// Return visit and no modifications, so do not send anything
	header("HTTP/1.0 304 Not Modified");
	header('Content-Length: 0');
}
else
{
	// First time visit or files were modified
	if ($cache) 
	{
		// Determine supported compression method
		$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
		$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');
		// Determine used compression method
		$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');
		// Check for buggy versions of Internet Explorer
		if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') && preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) 
		{
			$version = floatval($matches[1]);
			if ($version < 6) $encoding = 'none';
			if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')) $encoding = 'none';
		}
		// Try the cache first to see if the combined files were already generated
		$cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');
		if (file_exists($cachedir . '/' . $cachefile)) 
		{
			if ($fp = fopen($cachedir . '/' . $cachefile, 'rb')) 
			{
				if ($encoding != 'none') 
				{
					header("Content-Encoding: " . $encoding);
				}
				header("Content-Type: text/" . $type);
				header("Content-Length: " . filesize($cachedir . '/' . $cachefile));
				fpassthru($fp);
				fclose($fp);
				exit;
			}
		}
	}
	// Get contents of the files
	$contents = '';
	foreach( $elementsPath as $path )
	{
		$contents.= "\n\n" . file_get_contents($path);
	}

	header("Content-Type: text/" . $contentType);
	if (isset($encoding) && $encoding != 'none') 
	{
		// Send compressed contents
		$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
		header("Content-Encoding: " . $encoding);
		header('Content-Length: ' . strlen($contents));
		echo $contents;
	}
	else
	{
		// Send regular contents
		header('Content-Length: ' . strlen($contents));
		echo $contents;
	}
	// Store cache
	if ($cache) 
	{
		if ($fp = fopen($cachedir . '/' . $cachefile, 'wb')) 
		{
			fwrite($fp, $contents);
			fclose($fp);
		}
	}
}

exit;
