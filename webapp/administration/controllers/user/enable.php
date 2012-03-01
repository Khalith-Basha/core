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
class CAdminUser extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$this->userManager = ClassLoader::getInstance()->getClassInstance( 'Model_User' );
		$iUpdated = 0;
		$userId = Params::getParam('id');
		if (!is_array($userId)) 
		{
			osc_add_flash_error_message(_m('User id isn\'t in the correct format'), 'admin');
		}
		$userActions = $this->getClassLoader()->getClassInstance( 'Manager_User', false, array( true ) );
		foreach ($userId as $id) 
		{
			$iUpdated+= $userActions->enable($id);
		}
		switch ($iUpdated) 
		{
		case (0):
			$msg = _m('No user has been enabled');
			break;

		case (1):
			$msg = _m('One user has been enabled');
			break;

		default:
			$msg = sprintf(_m('%s users have been enabled'), $iUpdated);
			break;
		}
		osc_add_flash_ok_message($msg, 'admin');
		$this->redirectTo(osc_admin_base_url(true) . '?page=user');
	}
}

