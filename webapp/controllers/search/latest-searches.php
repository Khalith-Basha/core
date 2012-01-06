<?php

class NewView
{
	protected $variables;

	public function __construct()
	{
		$this->variables = array();
	}

	public function assign( $name, $value )
	{
		$this->variables[ $name ] = $value;
	}
}

class CWebSearch extends Controller
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		require 'osc/model/LatestSearches.php';
		$latestSearches = LatestSearches::getInstance()->selectAll();
		var_dump($latestSearches);die;

		$view = new NewView;
		
		$view->assign( 'latestSearches', $latestSearches );
		echo 'xxx';
	}
}


