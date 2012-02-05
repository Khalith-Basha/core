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
		$view = $this;
		$file = $name . '.php';
		$themes = $classLoader->getClassInstance( 'WebThemes' );
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

	function _get($key) 
	{
		if ($this->_exists($key)) 
		{
			return ($this->variables[$key]);
		}

		return null;
	}
	function _next($key) 
	{
		if (is_array($this->variables[$key])) 
		{
			$this->aCurrent[$key] = current($this->variables[$key]);
			if ($this->aCurrent[$key]) 
			{
				next($this->variables[$key]);
				return true;
			}
		}
		return false;
	}
	function _current($key) 
	{
		if (isset($this->aCurrent[$key]) && is_array($this->aCurrent[$key])) 
		{
			return $this->aCurrent[$key];
		}
		elseif (is_array($this->variables[$key])) 
		{
			$this->aCurrent[$key] = current($this->variables[$key]);
			return $this->aCurrent[$key];
		}
		return null;
	}
	function _reset($key) 
	{
		if (!array_key_exists($key, $this->variables)) 
		{
			return array();
		}
		if (!is_array($this->variables[$key])) 
		{
			return array();
		}
		return reset($this->variables[$key]);
	}
	function _exists($key) 
	{
		return isset( $this->variables[$key] );
	}
	function _count($key) 
	{
		if (is_array($this->variables[$key])) 
		{
			return count($this->variables[$key]);
		}
		return -1;
	}
	function _erase($key) 
	{
		unset($this->variables[$key]);
		unset($this->aCurrent[$key]);
	}
}

