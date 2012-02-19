<?php

class Url_Abstract
{
	protected $urls;
	protected $classLoader;

	public function __construct()
	{
		$this->classLoader = ClassLoader::getInstance();
		$this->urls = array();

		$this->loadUrls();
	}

	public function loadUrls()
	{
	}

	public function loadRules( Rewrite $rewrite )
	{
	}

	/**
	 * Gets the root url for your installation
	 *
	 * @param boolean $with_index true if index.php in the url is needed
	 * @return string
	 */
	public function getBaseUrl( $withIndex = false )
	{
		$generalConfig = $this->classLoader->getClassInstance( 'Config' )->getConfig( 'general' );
		$path = $generalConfig['webUrl'];
		if( $withIndex )
			$path .= '/index.php';
		return $path;
	}

	public function create( $name )
	{
		$arguments = func_get_args();
		if( count( $arguments ) > 0 )
		{
			$name = $arguments[0];
			$arguments = array_slice( $arguments, 1 );
		}
		$url = null;

		$key = osc_rewrite_enabled() ? 'friendly' : 'default';
		$url = $this->urls[ $name ][ $key ];

		if( is_null( $url ) )
			throw new Exception( 'URL not found: ' . $name );

		return vsprintf( $url, $arguments );
	}
}

