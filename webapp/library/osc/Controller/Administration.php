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
		$session->_drop('adminId');
		$session->_drop('adminUserName');
		$session->_drop('adminName');
		$session->_drop('adminEmail');
		$session->_drop('adminLocale');

		$cookie = $this->getCookie();
		$cookie->pop('oc_adminId');
		$cookie->pop('oc_adminSecret');
		$cookie->pop('oc_adminLocale');
		$cookie->set();
	}

	public function showAuthFailPage() 
	{
		osc_current_admin_theme_path( 'login.php' );
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

