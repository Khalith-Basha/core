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
class CAdminTool extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('tools/import.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$sql = Params::getFiles('sql');
		if (isset($sql['size']) && $sql['size'] != 0) 
		{
			$content_file = file_get_contents($sql['tmp_name']);
			$conn = $this->getClassLoader()->getClassInstance( 'cuore_db_Connection' );
			$c_db = $conn->getResource();
			$comm = $this->getClassLoader()->getClassInstance( 'Database_Command', false, array( $c_db ) );
			if ($comm->importSQL($content_file)) 
			{
				$this->getSession()->addFlashMessage( _m('Import complete') );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('There was a problem importing data to the database'), 'ERROR' );
			}
		}
		else
		{
			$this->getSession()->addFlashMessage( _m('No file was uploaded'), 'admin', 'ERROR' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=import');
	}
}

