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
class UserController extends SecureController
{
	public function isLogged() 
	{
		return osc_is_web_user_logged_in();
	}

	public function logout() 
	{
		$this->getSession()->destroy();
		$this->getSession()->_drop('userId');
		$this->getSession()->_drop('userName');
		$this->getSession()->_drop('userEmail');
		$this->getSession()->_drop('userPhone');
		$this->getCookie()->pop('oc_userId');
		$this->getCookie()->pop('oc_userSecret');
		$this->getCookie()->set();
	}
	
	public function showAuthFailPage() 
	{
		$this->redirectTo( osc_user_login_url() );
	}
}

