<?php

class CWebSearch extends Controller
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$latestSearches = $this->getClassLoader()
			->getClassInstance( 'Model_LatestSearches' )
			->selectAll();

		$view = $this->getView();
		$view->assign( 'latestSearches', $latestSearches );
		echo $view->render( 'search/latest-searches' );
	}
}

