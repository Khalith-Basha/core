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
		$this->doView('settings/cron.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$iUpdated = 0;
		$bAutoCron = Params::getParam('auto_cron');
		$bAutoCron = ($bAutoCron != '' ? true : false);
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $bAutoCron), array('s_name' => 'auto_cron'));
		if ($iUpdated > 0) 
		{
			$this->getSession()->addFlashMessage( _m('Cron config has been updated'), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=cron');
	}
}

