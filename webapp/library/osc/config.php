<?php
/**
 * @return array
 */
function getConfigurationsAvailable() 
{
	$configs = array();
	$dir = dir(ABS_PATH . DIRECTORY_SEPARATOR . 'config');
	while ($file = $dir->read()) 
	{
		if ('.' != $file && '..' != $file) 
		{
			$configs[] = $file;
		}
	}
	$dir->close();
	return $configs;
}
/**
 * @return string
 */
function getCurrentConfig() 
{
	$currentConfig = 'default';
	$serverName = $_SERVER['SERVER_NAME'];
	$configs = getConfigurationsAvailable();
	foreach ($configs as $config) 
	{
		if (preg_match('/' . $config . '$/', $serverName)) 
		{
			$currentConfig = $config;
			break;
		}
	}
	return $currentConfig;
}
