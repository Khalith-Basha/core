<?php

abstract class Controller_Cacheable extends Controller_Default
{
	public final function doGet( HttpRequest $req, HttpResponse $res )
	{
		$cacheKey = $this->getCacheKey();
		$cacheService = $this->getClassLoader()->getClassInstance( 'Services_Cache_Memcached' );
		$cachedResponse = $cacheService->read( $cacheKey );
		if( true||false === $cachedResponse ) // @TODO REMOVE
		{
			$cachedResponse = $this->renderView( $req, $res );
			$cacheExpiration = $this->getCacheExpiration();
			$cacheService->write( $cacheKey, $cachedResponse, $cacheExpiration );
		}

		if( is_array( $cachedResponse ) )
		{
			if( !empty( $cachedResponse['statusCode'] ) )
			{
				$res->sendStatusCode( $cachedResponse['statusCode'] );
				$res->sendHeaders();
			}

			echo $cachedResponse['viewContent'];
		}
		elseif( is_string( $cachedResponse ) )
		{
			echo $cachedResponse;
		}
		else
		{
			$res->sendStatusCode( 500 );
			$res->sendHeaders();
			echo $this->getView()->render( 'error/500' );
		}
	}

	abstract public function getCacheKey();
	abstract public function getCacheExpiration();
	abstract public function renderView( HttpRequest $req, HttpResponse $res );
}

