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
		$file = $this->getClassLoader()->getClassInstance( 'FileMaintenance' );

		$mode = Params::getParam('mode');
		if ($mode == 'on') 
		{
			try
			{
				$theme = $this->getView()->getTheme();
				$tmpPath = $theme->getCurrentThemePath() . '/maintenance.html';
				$file->copyFrom( $tmpPath );
				$this->getSession()->addFlashMessage( _m('Maintenance mode is ON') );
			}
			catch( Exception $e )
			{
				$this->getSession()->addFlashMessage( _m('There was an error creating .maintenance file, please create it manually at the root folder'), 'ERROR' );
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=maintenance');
		}
		else if ($mode == 'off') 
		{
			try
			{
				$file->delete();
				$this->getSession()->addFlashMessage( _m('Maintenance mode is OFF') );
			} catch( Exception $e )
			{
				$this->getSession()->addFlashMessage( _m('There was an error removing .maintenance file, please remove it manually from the root folder'), 'ERROR' );
			}
			$this->redirectTo(osc_admin_base_url(true) . '?page=tool&action=maintenance');
		}

		$this->getView()->assign( 'maintenanceModeEnabled', $file->exists() );
		$this->doView('tools/maintenance.php');
	}
}

