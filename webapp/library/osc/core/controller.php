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
class HttpRequest
{
	public function getMethod() 
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}
class HttpResponse
{
	public function sendRedirect() 
	{
	}
}
abstract class Controller
{
	protected $action;
	protected $ajax;
	protected $time;
	public function __construct() 
	{
		$this->action = Params::getParam('action');
		$this->ajax = false;
		$this->time = list($sm, $ss) = explode(' ', microtime());
	}
	public function __destruct() 
	{
		if (!$this->ajax && OSC_DEBUG) 
		{
			echo '<!-- ' . $this->getTime() . ' seg. -->';
		}
	}
	protected function _exportVariableToView($key, $value) 
	{
		View::newInstance()->_exportVariableToView($key, $value);
	}
	protected function _view($key = null) 
	{
		View::newInstance()->_view($key);
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
		Rewrite::newInstance()->set_location('error');
		header('HTTP/1.1 404 Not Found');
		osc_current_web_theme_path('404.php');
	}
	public function redirectTo($url) 
	{
		header('Location: ' . $url);
		exit;
	}
	public function getTime() 
	{
		$timeEnd = list($em, $es) = explode(' ', microtime());
		return ($timeEnd[0] + $timeEnd[1]) - ($this->time[0] + $this->time[1]);
	}
}
abstract class BaseModel extends Controller
{
}
