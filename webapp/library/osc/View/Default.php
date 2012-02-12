<?php

class View_Default
{
	private $name;
	private $variables;
	private $aCurrent;

	public function __construct()
	{
		$this->variables = array();
	}

	public function setName( $name )
	{
		$this->name = $name;
	}
	
	public function assign( $name, $value )
	{
		$this->variables[ $name ] = $value;
	}

	public function render( $name = null )
	{
		if( is_null( $name ) )
			$name = $this->name;

		if( is_null( $name ) )
			throw new Exception( 'Missing template name with setName() or render(name).' );

		$classLoader = ClassLoader::getInstance();
		extract( $this->variables );
		$urlFactory = $classLoader->getClassInstance( 'Url_Abstract' );
		$view = $this;
		$file = $name . '.php';
		$webThemes = $classLoader->getClassInstance( 'Ui_MainTheme' );
		$viewContent = null;
		$filePath = $webThemes->getCurrentThemePath() . $file;
		if (!file_exists($filePath)) 
		{
			$webThemes->setGuiTheme();
			$filePath = $webThemes->getCurrentThemePath() . DIRECTORY_SEPARATOR . $file;
		}
		if (file_exists($filePath)) 
		{
			ob_start();
			osc_run_hook('before_html');
			require $filePath;
			$viewContent = ob_get_contents();
			osc_run_hook('after_html');
			ob_end_clean();
		}
		else
		{
			trigger_error('File not found: ' . $filePath, E_USER_NOTICE);
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
		if (is_array($this->variables[$key])) 
		{
			return count($this->variables[$key]);
		}
		return -1;
	}

	public function removeVar( $key ) 
	{
		unset( $this->variables[ $key ] );
	}
}

