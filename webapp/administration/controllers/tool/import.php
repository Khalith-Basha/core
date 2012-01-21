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
		case 'import':
			$this->doView('tools/import.php');
			break;

		case 'import_post':
			// calling
			$sql = Params::getFiles('sql');
			if (isset($sql['size']) && $sql['size'] != 0) 
			{
				$content_file = file_get_contents($sql['tmp_name']);
				$conn = Database_Connection::newInstance();
				$c_db = $conn->getOsclassDb();
				$comm = new DBCommandClass($c_db);
				if ($comm->importSQL($content_file)) 
				{
					osc_add_flash_ok_message(_m('Import complete'), 'admin');
				}
				else
				{
					osc_add_flash_error_message(_m('There was a problem importing data to the database'), 'admin');
				}
			}
			else
			{
				osc_add_flash_error_message(_m('No file was uploaded'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=import');
			break;
		}
	}
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}
