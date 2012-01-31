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
		return $this->title;
	}

	public function setMeta( $name, $content )
	{
		$this->metas[ $name ] = $content;
	}

	public function getMetas()
	{
		return $this->metas;
	}

	public function setMetaRobots( array $robots )
	{
		$this->setMeta( 'robots', implode( ',', $robots ) );
	}

	public function setMetaDescription( $description )
	{
		$this->setMeta( 'description', $description );
	}

	public function setMetaKeywords( $keywords )
	{
		$this->setMetaContent( 'keywords', $keywords );
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

