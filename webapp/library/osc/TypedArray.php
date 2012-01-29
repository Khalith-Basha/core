<?php

class TypedArray
{
	private $storage;

	public function __construct( array $storage = array() )
	{
		$this->storage = $storage;
	}

	public function getValue( $name )
	{
		return $this->storage[ $name ];
	}

	public function exists( $name )
	{
		return isset( $this->storage[ $name ] );
	}

	public function getInteger( $name )
	{
		if( $this->exists( $name ) )
			return intval( $this->getValue( $name ) );

		return null;
	}

	public function getBoolean( $name )
	{
		if( $this->exists( $name ) )
			return (bool) $this->getValue( $name );

		return null;
	}

	public function getString( $name, $default = null )
	{
		if( $this->exists( $name ) )
			return strval( $this->getValue( $name ) );

		return $default;
	}
}

