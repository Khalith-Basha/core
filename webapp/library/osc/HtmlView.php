<?php

require_once 'osc/View.php';

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

