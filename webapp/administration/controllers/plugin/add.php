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
class CAdminPlugin extends AdminSecBaseModel
{
	function __construct() 
	{
		parent::__construct();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		parent::doModel();

		osc_current_admin_theme_path( 'plugins/add.php' );
		Session::newInstance()->_clearVariables();
	}
	
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$package = Params::getFiles("package");
		if (isset($package['size']) && $package['size'] != 0) 
		{
			$path = osc_plugins_path();
			(int)$status = osc_unzip_file($package['tmp_name'], $path);
		}
		else
		{
			$status = 3;
		}
		switch ($status) 
		{
		case (0):
			$msg = _m('The plugin folder is not writable');
			osc_add_flash_error_message($msg, 'admin');
			break;

		case (1):
			$msg = _m('The plugin has been uploaded correctly');
			osc_add_flash_ok_message($msg, 'admin');
			break;

		case (2):
			$msg = _m('The zip file is not valid');
			osc_add_flash_error_message($msg, 'admin');
			break;

		case (3):
			$msg = _m('No file was uploaded');
			osc_add_flash_error_message($msg, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin&action=add");
			break;

		case (-1):
		default:
			$msg = _m('There was a problem adding the plugin');
			osc_add_flash_error_message($msg, 'admin');
			break;
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
	}
	function doView($file) 
	{
	}
	function errorHandler($pn, $action) 
	{
		if (false === is_null($aError = error_get_last())) 
		{
			Plugins::deactivate($pn);
			if ($action == 'install') 
			{
				Plugins::uninstall($pn);
			}
			osc_add_flash_error_message(sprintf(_m('There was a fatal error and the plugin was not installed.<br />Error: "%s" Line: %s<br/>File: %s'), $aError['message'], $aError['line'], $aError['file']), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
		}
	}
}

