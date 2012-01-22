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
class CAdminIndex extends Controller
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView('recover.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$admin = $this->getClassLoader()->getClassInstance( 'Model_Admin' )->findByEmail(Params::getParam('email'));
		if ($admin) 
		{
			if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
			{
				if (!osc_check_recaptcha()) 
				{
					osc_add_flash_error_message(_m('The Recaptcha code is wrong'), 'admin');
					$this->redirectTo(osc_admin_base_url(true) . '?page=login&action=recover');
					return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
					
				}
			}
			require_once 'osc/helpers/hSecurity.php';
			$newPassword = osc_genRandomPassword(40);
			$this->getClassLoader()->getClassInstance( 'Model_Admin' )->update(array('s_secret' => $newPassword), array('pk_i_id' => $admin['pk_i_id']));
			$password_url = osc_forgot_admin_password_confirm_url($admin['pk_i_id'], $newPassword);
			osc_run_hook('hook_email_user_forgot_password', $admin, $password_url);
		}
		osc_add_flash_ok_message(_m('A new password has been sent to your e-mail'), 'admin');
		$this->redirectTo(osc_admin_base_url());

	}

	public function doView($file) 
	{
		osc_current_admin_theme_path( $file );
	}
}
