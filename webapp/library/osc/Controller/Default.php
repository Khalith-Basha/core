<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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

abstract class Controller_Default
{
	protected $action;
	protected $ajax;

	protected $classLoader;
	protected $server;
	protected $view;
	protected $session;

	protected $redirector;

	public function __construct() 
	{
		$this->action = Params::getParam('action');
		$this->ajax = false;

		$this->classLoader = ClassLoader::getInstance();
		$this->server = new \Cuore\Input\Server;
		$this->cookie = new \Cuore\Input\Cookie;
		
		$this->view = $this->classLoader->getClassInstance( 'View_Html' );
		$this->view->setTitle( osc_page_title() );

		$this->input = new \Cuore\Input\Http( $_SERVER['REQUEST_METHOD'] );

		$this->redirector = new \Cuore\Web\Redirector;
	}

	public function __destruct() 
	{
	}

	public function init()
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

	protected function assign( $key, $value )
	{
		$this->getView()->assign( $key, $value );
	}

	public function processRequest( HttpRequest $req, HttpResponse $resp ) 
	{
		$method = $req->getMethod();
		if ('POST' == $method && method_exists($this, 'doPost')) 
		{
			$this->doPost($req, $resp);
			exit;
		}
		elseif (method_exists($this, 'doGet')) 
		{
			$this->doGet($req, $resp);
			exit;
		}

		$resp->setStatusCode( 500 );
	}

	public function redirectTo($url) 
	{
		header('Location: ' . $url);
		exit;
	}

	public function redirectToBaseUrl()
	{
		$url = $this->classLoader->getClassInstance( 'Url_Abstract' )->getBaseUrl( true );
		$this->redirectTo( $url );
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

