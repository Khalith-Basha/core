<?php

abstract class Controller_Cacheable extends Controller_Default
{
	public final function doGet( HttpRequest $req, HttpResponse $res )
	{
		$cacheConfig = array();

		$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
		if( $config->hasConfig( 'memcached' ) )
		{
			$cacheConfig = $config->getConfig( 'memcached' );
		}

		$cacheKey = $this->getCacheKey();
		$cacheService = new \Cuore\Cache\Volatile( $cacheConfig );
		$cachedResponse = $cacheService->get( $cacheKey );
		if( true||false === $cachedResponse ) // @TODO REMOVE
		{
			$cachedResponse = $this->renderView( $req, $res );
			$cacheExpiration = $this->getCacheExpiration();
			$cacheService->set( $cacheKey, $cachedResponse, $cacheExpiration );
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

