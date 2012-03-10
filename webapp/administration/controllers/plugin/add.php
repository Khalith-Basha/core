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
class CAdminPlugin extends Controller_Administration
{
	function __construct() 
	{
		parent::__construct();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView( 'plugins/add.php' );
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
			$this->getSession()->addFlashMessage( $msg, 'admin', 'ERROR' );
			break;

		case (1):
			$msg = _m('The plugin has been uploaded correctly');
			$this->getSession()->addFlashMessage( $msg, 'admin' );
			break;

		case (2):
			$msg = _m('The zip file is not valid');
			$this->getSession()->addFlashMessage( $msg, 'admin', 'ERROR' );
			break;

		case (3):
			$msg = _m('No file was uploaded');
			$this->getSession()->addFlashMessage( $msg, 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin&action=add");
			break;

		case (-1):
		default:
			$msg = _m('There was a problem adding the plugin');
			$this->getSession()->addFlashMessage( $msg, 'admin', 'ERROR' );
			break;
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
	}
	function errorHandler($pn, $action) 
	{
		if (false === is_null($aError = error_get_last())) 
		{
			ClassLoader::getInstance()->getClassInstance( 'PluginManager' )->deactivate($pn);
			if ($action == 'install') 
			{
				ClassLoader::getInstance()->getClassInstance( 'PluginManager' )->uninstall($pn);
			}
			$this->getSession()->addFlashMessage( sprintf(_m('There was a fatal error and the plugin was not installed.<br />Error: "%s" Line: %s<br/>File: %s'), $aError['message'], $aError['line'], $aError['file']), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
		}
	}
}

