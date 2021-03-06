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
class CAdminIndex extends Controller_Default
{
	public function init()
	{
		$classLoader = $this->getClassLoader();
		$classLoader->loadFile( 'helpers/security' );
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$adminTheme = $this->getClassLoader()->getClassInstance( 'Ui_AdminTheme' );
		$this->getView()->setTheme( $adminTheme );
		echo $this->getView()->render( 'recover' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$userModel = $classLoader->getClassInstance( 'Model_User' );
		$admin = $userModel->findByEmail(Params::getParam('email'));
		$session = $this->getSession();
		if ($admin) 
		{
			if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
			{
				if (!osc_check_recaptcha()) 
				{
					$session->addFlashMessage( _m('The Recaptcha code is wrong'), 'ERROR' );
					$this->redirectTo(osc_admin_base_url(true) . '?page=login&action=recover');
					return false;
					
				}
			}
			$newPassword = osc_genRandomPassword(40);
			$userModel->update(array('s_secret' => $newPassword), array('pk_i_id' => $admin['pk_i_id']));
			$password_url = osc_forgot_admin_password_confirm_url($admin['pk_i_id'], $newPassword);
			osc_run_hook('hook_email_user_forgot_password', $admin, $password_url);
		}
		$session->addFlashMessage( _m( 'A new password has been sent to your e-mail' ) );
		$this->redirectTo(osc_admin_base_url());
	}
}

