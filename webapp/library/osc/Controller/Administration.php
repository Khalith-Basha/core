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
class Controller_Administration extends Controller_Secure 
{
	public function __construct()
	{
		parent::__construct();
		$this->view->setTheme( $this->getClassLoader()->getClassInstance( 'Ui_AdminTheme' ) );
	}

	public function isLogged() 
	{
		return osc_is_admin_user_logged_in();
	}

	public function logout() 
	{
		$session = $this->getSession();
		$session->destroy();
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
	}

	public function showAuthFailPage() 
	{
		echo $this->getView()->render( 'login.php' );
		exit;
	}

	public function doView( $file )
	{
		$view = $this->getView();
		echo $view->render( 'header' );
		echo $view->render( preg_replace( '/\.php$/', '', $file ) );
		echo $view->render( 'footer' );
		$this->getSession()->_clearVariables();
	}
}

