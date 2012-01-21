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
class CAdminTool extends AdminSecBaseModel
{
	function __construct() 
	{
		parent::__construct();
	}

	function doModel() 
	{
		switch ($this->action) 
		{
		case 'maintenance':
			$mode = Params::getParam('mode');
			if ($mode == 'on') 
			{
				$maintenance_file = ABS_PATH . '.maintenance';
				$fileHandler = @fopen($maintenance_file, 'w');
				if ($fileHandler) 
				{
					osc_add_flash_ok_message(_m('Maintenance mode is ON'), 'admin');
				}
				else
				{
					osc_add_flash_error_message(_m('There was an error creating .maintenance file, please create it manually at the root folder'), 'admin');
				}
				fclose($fileHandler);
				$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=maintenance');
			}
			else if ($mode == 'off') 
			{
				$deleted = @unlink(ABS_PATH . '.maintenance');
				if ($deleted) 
				{
					osc_add_flash_ok_message(_m('Maintenance mode is OFF'), 'admin');
				}
				else
				{
					osc_add_flash_error_message(_m('There was an error removing .maintenance file, please remove it manually from the root folder'), 'admin');
				}
				$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=maintenance');
			}
			$this->doView('tools/maintenance.php');
			break;
		}
	}
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}
