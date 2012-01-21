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

	public function sendStatus()
	{
	}
}
abstract class Controller
{
	protected $action;
	protected $ajax;

	private $server;
	protected $view;
	protected $session;

	public function __construct() 
	{
		$this->action = Params::getParam('action');
		$this->ajax = false;

		$classLoader = ClassLoader::getInstance();
		$this->server = $classLoader->getClassInstance( 'Server' );
		$this->view = $classLoader->getClassInstance( 'View' );
		$this->session = $classLoader->getClassInstance( 'Session' );
	}

	public function __destruct() 
	{
	}

	protected function getServer()
	{
		return $this->server;
	}

	protected function _exportVariableToView($key, $value) 
	{
		$this->_exportVariableToView($key, $value);
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
		else
		{
			$this->doModel();
		}
	}
	protected function doModel() 
	{
	}
	protected function doView($file) 
	{
	}
	public function do404() 
	{
		ClassLoader::getInstance()->getClassInstance( 'Rewrite' )->set_location('error');
		header('HTTP/1.1 404 Not Found');
		osc_current_web_theme_path('404.php');
	}
	public function redirectTo($url) 
	{
		header('Location: ' . $url);
		exit;
	}

	public function getSession()
	{
		return $this->session;
	}
}
abstract class BaseModel extends Controller
{
}
