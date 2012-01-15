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
	private static $instance;

	public static function newInstance() 
	{
		if (!self::$instance instanceof self) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	private $rules;
	private $location;
	private $section;

	public function __construct() 
	{
		$this->rules = array();
		$this->location = null;
		$this->section = null;
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

		$serverName = $_SERVER['SERVER_NAME'];
		$request_uri = urldecode( preg_replace( '@^' . REL_WEB_URL . '@', '', $_SERVER['REQUEST_URI'] ) );
		if( osc_rewrite_enabled() )
		{
			$this->extractParams( $request_uri );
			$tmp_ar = explode( '?', $request_uri );
			$request_uri = $tmp_ar[0];
			foreach( $this->rules as $rule )
			{
				$match = $rule->rePath;
				$uri = $rule->request;
				if(
					preg_match( '#' . $match . '#', $request_uri, $m ) &&
					(
						is_null( $rule->reDomain ) ||
						preg_match( $rule->reDomain, $serverName )
					)
				)
				{
					$request_uri = preg_replace('#' . $match . '#', $uri, $request_uri);
					break;
				}
			}
		}
		$this->extractParams($request_uri);

		$page = Params::getParam( 'page' );
		if( !empty( $page ) )
			$this->location = $page;
		$action = Params::getParam( 'action' );
		if( !empty( $action ) )
			$this->section = $action;
	}

	public function extractParams($uri = '') 
	{
		$uri_array = explode('?', $uri);
		$url = substr($uri_array[0], 1);
		$length_i = count($uri_array);
		for ($var_i = 1; $var_i < $length_i; $var_i++) 
		{
			if (preg_match_all('|&([^=]+)=([^&]*)|', '&' . $uri_array[$var_i] . '&', $matches)) 
			{
				$length = count($matches[1]);
				for ($var_k = 0; $var_k < $length; $var_k++) 
				{
					Params::setParam($matches[1][$var_k], $matches[2][$var_k]);
				}
			}
		}
	}
	public function clearRules() 
	{
		$this->rules = array();
	}
	public function set_location($location) 
	{
		$this->location = $location;
	}
	public function get_location() 
	{
		return $this->location;
	}
	public function get_section() 
	{
		return $this->section;
	}
}
