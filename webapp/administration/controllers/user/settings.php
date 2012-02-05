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
class CAdminUser extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('users/settings.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$iUpdated = 0;
		$enabledUserValidation = Params::getParam('enabled_user_validation');
		$enabledUserValidation = (($enabledUserValidation != '') ? true : false);
		$enabledUserRegistration = Params::getParam('enabled_user_registration');
		$enabledUserRegistration = (($enabledUserRegistration != '') ? true : false);
		$enabledUsers = Params::getParam('enabled_users');
		$enabledUsers = (($enabledUsers != '') ? true : false);
		$notifyNewUser = Params::getParam('notify_new_user');
		$notifyNewUser = (($notifyNewUser != '') ? true : false);
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledUserValidation), array('s_name' => 'enabled_user_validation'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledUserRegistration), array('s_name' => 'enabled_user_registration'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledUsers), array('s_name' => 'enabled_users'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyNewUser), array('s_name' => 'notify_new_user'));
		if ($iUpdated > 0) 
		{
			osc_add_flash_ok_message(_m('Users\' settings have been updated'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=users&action=settings');
	}
}

