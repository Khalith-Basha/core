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
class CAdminSettings extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('settings/searches.php');
	}
	
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$preferenceModel = $this->getClassLoader()
			->getClassInstance( 'Model_Preference' );
		if (Params::getParam('save_latest_searches') == 'on') 
		{
			$preferenceModel->update(array('s_value' => 1), array('s_name' => 'save_latest_searches'));
		}
		else
		{
			$preferenceModel->update(array('s_value' => 0), array('s_name' => 'save_latest_searches'));
		}
		$preferenceModel->update(array('s_value' => Params::getParam('customPurge')), array('s_name' => 'purge_latest_searches'));
		osc_add_flash_ok_message(_m('Settings have been updated'), 'admin');
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches');
	}
}

