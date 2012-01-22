<?php

require_once 'osc/View.php';

class HtmlView extends View
{
	private $title;

	private $metas;
	private $robots;
	private $javaScripts;
	private $styleSheets;

	public function __construct()
	{
		parent::__construct();

		$this->metas = $this->robots = $this->javaScripts = $this->styleSheets = array();
	}

	public function setTitle( $title )
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return empty( $this->title ) ? meta_title() : $this->title;
	}

	public function addMeta( $name, $content )
	{
		$this->metas[ $name ] = $content;
	}

	public function getMetas()
	{
		return $this->metas;
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

	public function addJavaScript( $javaScript )
	{
		$this->javaScripts[] = $javaScript;
	}

	public function getJavaScripts()
	{
		return $this->javaScripts;
	}

	public function addStyleSheets( $styleSheet )
	{
		$this->styleSheets[] = $styleSheet;
	}

	public function getStyleSheets()
	{
		return $this->styleSheets;
	}
}

