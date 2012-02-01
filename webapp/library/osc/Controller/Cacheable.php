<?php

abstract class Controller_Cacheable extends Controller_Default
{
	public final function doGet( HttpRequest $req, HttpResponse $res )
	{
		$cacheKey = $this->getCacheKey();
		$cacheService = $this->getClassLoader()->getClassInstance( 'Services_Cache_Memcached' );
		$viewContent = $cacheService->read( $cacheKey );
		if( false === $viewContent )
		{
			$viewContent = $this->renderView( $req, $res );
			$cacheExpiration = $this->getCacheExpiration();
			$cacheService->write( $cacheKey, $viewContent, $cacheExpiration );
		}
		echo $viewContent;
	}

	abstract public function getCacheKey();
	abstract public function getCacheExpiration();
	abstract public function renderView( HttpRequest $req, HttpResponse $res );
}

