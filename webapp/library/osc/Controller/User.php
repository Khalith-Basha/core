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
class Controller_User extends Controller_Secure
{
	public function isLogged() 
	{
		return osc_is_web_user_logged_in();
	}

	public function logout() 
	{
		$session = $this->getSession();
		$session->destroy();
		$session->_drop('userId');
		$session->_drop('userName');
		$session->_drop('userEmail');
		$session->_drop('userPhone');

		$cookie = $this->getCookie();
		$cookie->pop('oc_userId');
		$cookie->pop('oc_userSecret');
		$cookie->set();
	}
	
	public function showAuthFailPage() 
	{
		$this->redirectTo( osc_user_login_url() );
	}
}

