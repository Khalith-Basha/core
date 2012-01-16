<?php

class View extends OldView
{
	private $name;
	private $variables;

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

	public function render()
	{
		$fileName = $this->name . '.php';
		osc_run_hook('before_html');
		$content = osc_render_view( $fileName, $this->variables, $this );
		Session::newInstance()->_clearVariables();
		osc_run_hook('after_html');

		echo $content;
	}
}

class HtmlView extends View
{
	private $robots;

	public function __construct()
	{
		parent::__construct();

		$this->robots = array();
	}

	public function hasMetaRobots()
	{
		return count( $this->robots );
	}

	public function getMetaRobots()
	{
		return implode( ',', $this->robots );
	}

	public function setMetaRobots( array $robots )
	{
		$this->robots = $robots;
	}
}

