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
class CAdminIndex extends Controller_Administration
{
	public function doGet() 
	{
		$session = $this->getSession();
		$session->remove('adminId');
		$session->remove('adminUserName');
		$session->remove('adminName');
		$session->remove('adminEmail');
		$session->remove('adminLocale');

		$cookie = $this->getCookie();
		$cookie->remove('oc_adminId');
		$cookie->remove('oc_adminSecret');
		$cookie->remove('oc_adminLocale');
		$cookie->set();
		$this->redirectTo(osc_admin_base_url(true));
	}
}
