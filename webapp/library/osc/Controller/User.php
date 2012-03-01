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
/**
 * Prints the user's account menu
 *
 * @param array $options array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * @return void
 */
function osc_private_user_menu($options = null) 
{
	$userUrls = ClassLoader::getInstance()->getClassInstance( 'Url_User' );
	if ($options == null) 
	{
		$options = array();
		$options[] = array('name' => __('Dashboard'), 'url' => $userUrls->osc_user_dashboard_url(), 'class' => 'opt_dashboard');
		$options[] = array('name' => __('Manage your items'), 'url' => $userUrls->osc_user_list_items_url(), 'class' => 'opt_items');
		$options[] = array('name' => __('Manage your alerts'), 'url' => $userUrls->osc_user_alerts_url(), 'class' => 'opt_alerts');
		$options[] = array('name' => __('My account'), 'url' => $userUrls->osc_user_profile_url(), 'class' => 'opt_account');
		$options[] = array('name' => __('Logout'), 'url' => $userUrls->osc_user_logout_url(), 'class' => 'opt_logout');
	}
	echo '<script type="text/javascript">';
	echo '$(".user_menu > :first-child").addClass("first") ;';
	echo '$(".user_menu > :last-child").addClass("last") ;';
	echo '</script>';
	echo '<ul class="user_menu">';
	$var_l = count($options);
	for ($var_o = 0; $var_o < ($var_l - 1); $var_o++) 
	{
		echo '<li class="' . $options[$var_o]['class'] . '" ><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>';
	}
	osc_run_hook('user_menu');
	echo '<li class="' . $options[$var_l - 1]['class'] . '" ><a href="' . $options[$var_l - 1]['url'] . '" >' . $options[$var_l - 1]['name'] . '</a></li>';
	echo '</ul>';
}

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
		$session->remove('userId');
		$session->remove('userName');
		$session->remove('userEmail');
		$session->remove('userPhone');

		$cookie = $this->getCookie();
		$cookie->remove('oc_userId');
		$cookie->remove('oc_userSecret');
		$cookie->set();
	}
	
	public function showAuthFailPage() 
	{
		$this->redirectTo( osc_user_login_url() );
	}
}

