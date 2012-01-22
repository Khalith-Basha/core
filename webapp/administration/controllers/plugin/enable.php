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
class CAdminPlugin extends AdministrationController
{
	function doModel() 
	{
		$pn = Params::getParam("plugin");
		// CATCH FATAL ERRORS
		register_shutdown_function(array($this, 'errorHandler'), $pn, 'enable');
		$enabled = Plugins::activate($pn);
		if ($enabled) 
		{
			Plugins::runHook($pn . '_enable');
			osc_add_flash_ok_message(_m('Plugin enabled'), 'admin');
		}
		else
		{
			osc_add_flash_error_message(_m('Error: Plugin already enabled'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=plugin");
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
