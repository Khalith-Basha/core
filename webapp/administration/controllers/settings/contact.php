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
		$this->doView('settings/contact.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$preferenceModel = $this->getClassLoader()
			->getClassInstance( 'Model_Preference' );
		
		$enabled_attachment = Params::getParam('enabled_attachment');
		if ($enabled_attachment == '')
			$enabled_attachment = 0;
		else
			$enabled_attachment = 1;

		$iUpdated = $preferenceModel->update(array('s_value' => $enabled_attachment), array('s_name' => 'contact_attachment'));
		if ($iUpdated > 0) 
		{
			$this->getSession()->addFlashMessage( _m('Contact configuration has been updated'), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=contact');
	}
}

