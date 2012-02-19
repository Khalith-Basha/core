<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class RewriteRule
{
	public $reDomain;
	public $rePath;

	public $request;

	public function __construct( $request, $rePath, $reDomain = null )
	{
		$this->request = $request;
		$this->rePath = $rePath;
		$this->reDomain = $reDomain;
	}
}

class Rewrite
{
	private $rules;

	public function __construct() 
	{
		$this->clearRules();
	}

	public function clearRules()
	{
		$this->rules = array();
	}

	public function getRules() 
	{
		return $this->rules;
	}

	public function addRule( $rePath, $request, $reDomain = null )
	{
		$rule = new RewriteRule( $request, $rePath, $reDomain );
		$this->rules[] = $rule;
	}

	public function init() 
	{
		if( empty( $_SERVER['REQUEST_URI'] ) )
			return;

		$classLoader = ClassLoader::getInstance();
		$generalConfig = $classLoader->getClassInstance( 'Config' )->getConfig( 'general' );
		$relativeWebUrl = $generalConfig['relativeWebUrl'];

		$serverName = $_SERVER['SERVER_NAME'];
		$requestUri = urldecode( preg_replace( '@^' . $relativeWebUrl. '@', '', $_SERVER['REQUEST_URI'] ) );
		if( osc_rewrite_enabled() )
		{
			$requestParts = explode( '?', $requestUri );
			if( 2 === count( $requestParts ) )
				$this->extractParams( $requestParts[1] );
			foreach( $this->rules as $rule )
			{
				$match = $rule->rePath;
				$uri = $rule->request;
				if(
					preg_match( '#' . $match . '#', $requestUri, $m ) &&
					(
						is_null( $rule->reDomain ) ||
						preg_match( $rule->reDomain, $serverName )
					)
				)
				{
					$requestUri = preg_replace('#' . $match . '#', $uri, $requestUri);
					break;
				}
			}
		}
		$this->extractParams( $requestUri );
	}

	public function extractParams( $uri ) 
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		$input = null;
		if( 'POST' === $requestMethod )
			$input = &$_POST;
		else
			$input = &$_GET;

		$uri_array = explode( '?', $uri );
		$url = substr($uri_array[0], 1);
		$length_i = count($uri_array);
		for ($var_i = 1; $var_i < $length_i; $var_i++) 
		{
			if (preg_match_all('|&([^=]+)=([^&]*)|', '&' . $uri_array[$var_i] . '&', $matches)) 
			{
				$length = count($matches[1]);
				for ($var_k = 0; $var_k < $length; $var_k++) 
				{
					$input[ $matches[1][$var_k] ] = $matches[2][$var_k];
					$_REQUEST[ $matches[1][$var_k] ] = $matches[2][$var_k];
				}
			}
		}
	}

	public function loadRules()
	{
		$this->clearRules();

		$classLoader = ClassLoader::getInstance();
		$this->addRule( '^/index.php', 'index.php' );

		$factories = array( 'Url_User', 'Url_Page', 'Url_Index', 'Url_Item', 'Url_Search' );
		foreach( $factories as $factory )
		{
			$classLoader->getClassInstance( $factory )
				->loadRules( $this );
		}
	}
}

