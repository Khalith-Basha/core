<?php

class View_Default
{
	private $name;
	private $variables;
	private $theme;

	public function __construct()
	{
		$this->variables = array();

		$this->classLoader = ClassLoader::getInstance();
		$this->theme = $this->classLoader->getClassInstance( 'Ui_MainTheme' );
	}

	public function setTheme( Ui_Theme $theme )
	{
		$this->theme = $theme;
	}

	public function setName( $name )
	{
		$this->name = $name;
	}
	
	public function assign( $name, $value )
	{
		$this->variables[ $name ] = $value;
	}

	public function render( $name = null, array $extraParams = array() )
	{
		if( is_null( $name ) )
			$name = $this->name;

		if( is_null( $name ) )
			throw new Exception( 'Missing template name with setName() or render(name).' );

		extract( $this->variables );
		extract( $extraParams );

		$classLoader = $this->classLoader;
		$urlFactory = $this->classLoader->getClassInstance( 'Url_Abstract' );
		$view = $this;
		$file = $name . '.php';
		$viewContent = null;

		$paths = array(
			$this->theme->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file,
			$this->theme->getDefaultThemePath() . DIRECTORY_SEPARATOR . $file,
		);
		$filePath = null;
		foreach( $paths as $path )
		{
			if( file_exists( $path ) )
			{
				$filePath = $path;
				break;
			}
		}

		if( !is_null( $filePath ) ) 
		{
			ob_start();
			# @TODO osc_run_hook('before_html');
			require $filePath;
			$viewContent = ob_get_contents();
			# @TODO osc_run_hook('after_html');
			ob_end_clean();
		}
		else
		{
			trigger_error( 'File not found: ' . $name, E_USER_NOTICE );
		}

		return $viewContent;
	}

	public function getVar( $key ) 
	{
		if( $this->varExists( $key ) )
			return $this->variables[ $key ];

		return null;
	}

	public function varExists( $key ) 
	{
		return isset( $this->variables[ $key ] );
	}

	public function countVar( $key ) 
	{
		if( $this->varExists( $key ) && is_array( $this->variables[$key] ) )
		{
			return count( $this->variables[$key] );
		}
		return -1;
	}

	public function removeVar( $key ) 
	{
		unset( $this->variables[ $key ] );
	}

	public function getTheme()
	{
		return $this->theme;
	}
}

