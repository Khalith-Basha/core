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
		$this->doView('settings/mailserver.php');
	}
	
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		// updating mailserver
		$iUpdated = 0;
		$mailserverAuth = Params::getParam('mailserver_auth');
		$mailserverAuth = ($mailserverAuth != '' ? true : false);
		$mailserverPop = Params::getParam('mailserver_pop');
		$mailserverPop = ($mailserverPop != '' ? true : false);
		$mailserverType = Params::getParam('mailserver_type');
		$mailserverHost = Params::getParam('mailserver_host');
		$mailserverPort = Params::getParam('mailserver_port');
		$mailserverUsername = Params::getParam('mailserver_username');
		$mailserverPassword = Params::getParam('mailserver_password');
		$mailserverSsl = Params::getParam('mailserver_ssl');
		if (!in_array($mailserverType, array('custom', 'gmail'))) 
		{
			$this->getSession()->addFlashMessage( _m('Mail server type is incorrect'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
		}
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverAuth), array('s_name' => 'mailserver_auth'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverPop), array('s_name' => 'mailserver_pop'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverType), array('s_name' => 'mailserver_type'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverHost), array('s_name' => 'mailserver_host'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverPort), array('s_name' => 'mailserver_port'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverUsername), array('s_name' => 'mailserver_username'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverPassword), array('s_name' => 'mailserver_password'));
		$iUpdated+= ClassLoader::getInstance()->getClassInstance( 'Model_Preference' )->update(array('s_value' => $mailserverSsl), array('s_name' => 'mailserver_ssl'));
		if ($iUpdated > 0) 
		{
			$this->getSession()->addFlashMessage( _m('Mail server configuration has changed'), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=mailserver');
	}
}

