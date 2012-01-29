<?php

abstract class Url_Abstract
{
	protected $urls;

	public function __construct()
	{
		$this->urls = array();

		$this->loadUrls();
	}

	abstract public function loadUrls();

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

