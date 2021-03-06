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
		$iDeleted = 0;
		$userId = Params::getParam('id');
		if (!is_array($userId)) 
		{
			$this->getSession()->addFlashMessage( _m('User id isn\'t in the correct format'), 'admin', 'ERROR' );
		}
		foreach ($userId as $id) 
		{
			$user = $this->userManager->findByPrimaryKey($id);
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'delete', $id, $user['s_email'], 'admin', osc_logged_admin_id());
			if ($this->userManager->deleteUser($id)) 
			{
				$iDeleted++;
			}
		}
		switch ($iDeleted) 
		{
		case (0):
			$msg = _m('No user has been deleted');
			break;

		case (1):
			$msg = _m('One user has been deleted');
			break;

		default:
			$msg = sprintf(_m('%s users have been deleted'), $iDeleted);
			break;
		}
		$this->getSession()->addFlashMessage( $msg, 'admin' );
		$this->redirectTo(osc_admin_base_url(true) . '?page=user');
	}
}

