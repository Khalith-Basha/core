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
class CAdminIndex extends Controller_Default
{
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$userModel = $classLoader->getClassInstance( 'Model_User' );

		$username = Params::getParam('user');
		$password = Params::getParam('password');
		$admin = $userModel->findByUsernamePassword( $username, $password );
		if( $admin && 1 == $admin['role_id'] )
		{
			if (Params::getParam('remember')) 
			{
				$classLoader->loadFile( 'helpers/security' );
				$secret = osc_genRandomPassword();
				$userModel->update(array('s_secret' => $secret), array('pk_i_id' => $admin['pk_i_id']));

				$cookie = $this->getCookie();
				$cookie->set_expires(osc_time_cookie());
				$cookie->push('oc_adminId', $admin['pk_i_id']);
				$cookie->push('oc_adminSecret', $secret);
				$cookie->push('oc_adminLocale', Params::getParam('locale'));
				$cookie->set();
			}
			$session = $this->getSession();
			$session->_set('adminId', $admin['pk_i_id']);
			$session->_set('adminUserName', $admin['s_username']);
			$session->_set('adminName', $admin['s_name']);
			$session->_set('adminEmail', $admin['s_email']);
			$session->_set('adminLocale', Params::getParam('locale'));
		}
		else
		{
			$this->getSession()->addFlashMessage( _m( 'That username does not exist' ), 'ERROR' );
		}
		$this->redirectTo(osc_admin_base_url());
	}
}

