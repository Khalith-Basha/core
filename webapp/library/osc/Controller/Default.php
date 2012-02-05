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

require_once 'osc/Server.php';

class HttpRequest
{
	public function getMethod() 
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}
class HttpResponse
{
	private $headers;

	private $statusCodes = array(
		404 => 'Not Found',
		500 => 'Internal Server Error'
	);

	public function __construct()
	{
		$this->headers = array();
	}

	public function addHeader( $name, $value = null )
	{
		$this->headers[ $name ] = $value;
	}

	public function sendHeaders()
	{
		foreach( $this->headers as $headerName => $headerValue )
		{
			$header = $headerName;
			if( !is_null( $headerValue ) )
				$header .= ': ' . $headerValue;
			header( $header );
		}
	}

	public function sendRedirection( $url = null, $permanent = false ) 
	{
		if( $permanent )
			addHeader( 'HTTP/1.1 301 Moved Permanently' );
		$this->addHeader( 'Location', $url );
		$this->sendHeaders();
		exit;
	}

	public function sendRedirectToReferer( $permanent = false )
	{
		$referer = empty( $_SERVER['HTTP_REFERER'] ) ? '/' : $_SERVER['HTTP_REFERER'];
		$this->sendRedirect( $referer, $permanent );
	}

	public function sendStatusCode( $statusCode )
	{
		if( false === isset( $this->statusCodes[ $statusCode ] ) )
			$statusCode = 500;

		$header = sprintf( 'HTTP/1.1 %d %s', $statusCode, $this->statusCodes[ $statusCode ] );
		$this->addHeader( $header );
	}
}
abstract class Controller_Default
{
	protected $action;
	protected $ajax;

	protected $classLoader;
	protected $server;
	protected $view;
	protected $session;

	public function __construct() 
	{
		$this->action = Params::getParam('action');
		$this->ajax = false;

		$this->classLoader = ClassLoader::getInstance();
		$this->server = $this->classLoader->getClassInstance( 'Server' );
		$this->cookie = $this->classLoader->getClassInstance( 'Cookie' );
		
		$this->view = $this->classLoader->getClassInstance( 'HtmlView' );
		$this->view->setTitle( osc_page_title() );

		$inputClass = 'POST' === $_SERVER['REQUEST_METHOD'] ? 'Input_Post' : 'Input_Get';
		$this->input = $this->classLoader->getClassInstance( $inputClass );
	}

	public function __destruct() 
	{
	}

	protected function getView()
	{
		return $this->view;
	}

	protected function getServer()
	{
		return $this->server;
	}

	protected function assign($key, $value) 
	{
		$this->getView()->assign($key, $value);
	}

	protected function _view($key = null) 
	{
		$this->_view($key);
	}

	public function processRequest(HttpRequest $req, HttpResponse $resp) 
	{
		if ('POST' == $req->getMethod() && method_exists($this, 'doPost')) 
		{
			$this->doPost($req, $resp);
		}
		elseif (method_exists($this, 'doGet')) 
		{
			$this->doGet($req, $resp);
		}

		$resp->sendStatusCode( 500 );
	}

	public function redirectTo($url) 
	{
		header('Location: ' . $url);
		exit;
	}

	public function getClassLoader()
	{
		return $this->classLoader;
	}

	public function getSession()
	{
		if( is_null( $this->session ) )
		{
			$this->session = $this->getClassLoader()
				->getClassInstance( 'Session' );
		}

		return $this->session;
	}

	public function getCookie()
	{
		return $this->cookie;
	}

	public function getInput()
	{
		return $this->input;
	}
}

