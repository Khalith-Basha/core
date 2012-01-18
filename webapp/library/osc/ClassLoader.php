<?php

class ClassLoader
{
	private static $singleton = null;

	public static function getInstance()
	{
		if( is_null( self::$singleton ) )
		{
			self::$singleton = new self;
		}

		return self::$singleton;
	}

	private $searchPaths;

	private function __construct()
	{
		$this->searchPaths = array();
	}

	public function addSearchPath( $searchPath, $classNamePrefix = '' )
	{
		$this->searchPaths[] = array(
			'searchPath' => $searchPath,
			'classNamePrefix' => $classNamePrefix
		);
	}

	public function loadFile( $filePath )
	{
		foreach( $this->searchPaths as $searchPath )
		{
			$requirePath = $searchPath['searchPath'] . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, $filePath ) . '.php';
			if( file_exists( $requirePath ) )
			{
				require $requirePath;
				return $searchPath['classNamePrefix'];
			}
		}

		throw new Exception( 'File not found: ' . $filePath );
	}

	private $classInstances;

	public function getClassInstance( $className, $singleton = true )
	{
		if( isset( $this->classInstances[ $className ] ) )
			return $this->classInstances[ $className ];

		$className = $this->loadFile( $className ) . $className;

		if( class_exists( $className ) )
		{
			$this->classInstances[ $className ] = new $className;
			return $this->classInstances[ $className ];
		}

		throw new Exception( 'ClassName not found: ' . $className );
	}
}

