<?php

class Config
{
	public function getConfigurationsAvailable()
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

	public function getCurrentProfile()
	{
		$currentConfig = 'default';
		$serverName = $_SERVER['SERVER_NAME'];
		$configs = $this->getConfigurationsAvailable();
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

	public function getConfigPath( $name )
	{
		$currentProfile = $this->getCurrentProfile();
		return implode( DIRECTORY_SEPARATOR, array( ABS_PATH, 'config', $currentProfile, $name . '.php' ) );
	}

	public function hasConfig( $name )
	{
		$configPath = $this->getConfigPath( $name );
		return file_exists( $configPath );
	}

	public function getConfig( $name )
	{
		$configPath = $this->getConfigPath( $name );
		require $configPath;
		$varName = 'config_' . $name;
		if( isset( $varName ) )
			return $$varName;

		throw new Exception( 'Configuration variable not found: ' . $varName );
	}
}

