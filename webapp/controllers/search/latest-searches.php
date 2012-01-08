<?php

class CWebSearch extends Controller
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		require 'osc/model/LatestSearches.php';
		$latestSearches = LatestSearches::getInstance()->selectAll();

		$view = new View;
		$view->setName( 'search/latest-searches' );
		$view->assign( 'latestSearches', $latestSearches );
		$view->render();
	}
}


