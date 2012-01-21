<?php

class View
{
	private $name;
	private $variables;

	public function __construct()
	{
		$this->variables = array();
		$this->aExported = array();
	}

	public function setName( $name )
	{
		$this->name = $name;
	}
	
	public function assign( $name, $value )
	{
		$this->variables[ $name ] = $value;
	}

	public function render()
	{
		$fileName = $this->name . '.php';
		osc_run_hook('before_html');
		$content = osc_render_view( $fileName, $this->variables, $this );
		Session::newInstance()->_clearVariables();
		osc_run_hook('after_html');

		echo $content;
	}

	/* @TODO: REMOVE */
	private $aExported;
	private $aCurrent;
	//to export variables at the business layer
	function _exportVariableToView($key, $value) 
	{
		$this->aExported[$key] = $value;
	}
	//to get the exported variables for the view
	function _get($key) 
	{
		if ($this->_exists($key)) 
		{
			return ($this->aExported[$key]);
		}
		else
		{
			return '';
		}
	}
	//only for debug
	function _view($key = null) 
	{
		if ($key) 
		{
			print_r($this->aExported[$key]);
		}
		else
		{
			print_r($this->aExported);
		}
	}
	function _next($key) 
	{
		if (is_array($this->aExported[$key])) 
		{
			$this->aCurrent[$key] = current($this->aExported[$key]);
			if ($this->aCurrent[$key]) 
			{
				next($this->aExported[$key]);
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
		elseif (is_array($this->aExported[$key])) 
		{
			$this->aCurrent[$key] = current($this->aExported[$key]);
			return $this->aCurrent[$key];
		}
		return '';
	}
	function _reset($key) 
	{
		if (!array_key_exists($key, $this->aExported)) 
		{
			return array();
		}
		if (!is_array($this->aExported[$key])) 
		{
			return array();
		}
		return reset($this->aExported[$key]);
	}
	function _exists($key) 
	{
		return (isset($this->aExported[$key]) ? true : false);
	}
	function _count($key) 
	{
		if (is_array($this->aExported[$key])) 
		{
			return count($this->aExported[$key]);
		}
		return -1;
	}
	function _erase($key) 
	{
		unset($this->aExported[$key]);
		unset($this->aCurrent[$key]);
	}

}


