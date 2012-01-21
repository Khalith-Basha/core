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
	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$admin = $this->getClassLoader()->getClassInstance( 'Model_Admin' )->findByUsername(Params::getParam('user'));
		if ($admin) 
		{
			if ($admin["s_password"] == sha1(Params::getParam('password'))) 
			{
				if (Params::getParam('remember')) 
				{
					//this include contains de osc_genRandomPassword function
					require_once 'osc/helpers/hSecurity.php';
					$secret = osc_genRandomPassword();
					$this->getClassLoader()->getClassInstance( 'Model_Admin' )->update(array('s_secret' => $secret), array('pk_i_id' => $admin['pk_i_id']));
					$this->getCookie()->set_expires(osc_time_cookie());
					$this->getCookie()->push('oc_adminId', $admin['pk_i_id']);
					$this->getCookie()->push('oc_adminSecret', $secret);
					$this->getCookie()->push('oc_adminLocale', Params::getParam('locale'));
					$this->getCookie()->set();
				}
				//we are logged in... let's go!
				$this->getSession()->_set('adminId', $admin['pk_i_id']);
				$this->getSession()->_set('adminUserName', $admin['s_username']);
				$this->getSession()->_set('adminName', $admin['s_name']);
				$this->getSession()->_set('adminEmail', $admin['s_email']);
				$this->getSession()->_set('adminLocale', Params::getParam('locale'));
			}
			else
			{
				osc_add_flash_error_message(_m('The password is incorrect'), 'admin');
			}
		}
		else
		{
			osc_add_flash_error_message(_m('That username does not exist'), 'admin');
		}
		//returning logged in to the main page...
		$this->redirectTo(osc_admin_base_url());
	}

	public function doModel() 
	{
		switch(123)
	{
		case ('recover'): //form to recover the password (in this case we have the form in /gui/)
			//#dev.conquer: we cannot use the doView here and only here
			$this->doView('gui/recover.php');
			break;

		case ('recover_post'):
			//post execution to recover the password
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
			break;

		case ('forgot'): //form to recover the password (in this case we have the form in /gui/)
			$admin = $this->getClassLoader()->getClassInstance( 'Model_Admin' )->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
			if ($admin) 
			{
				$this->doView('gui/forgot_password.php');
			}
			else
			{
				osc_add_flash_error_message(_m('Sorry, the link is not valid'), 'admin');
				$this->redirectTo(osc_admin_base_url());
			}
			break;

		case ('forgot_post'):
			$admin = $this->getClassLoader()->getClassInstance( 'Model_Admin' )->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
			if ($admin) 
			{
				if (Params::getParam('new_password') == Params::getParam('new_password2')) 
				{
					$this->getClassLoader()->getClassInstance( 'Model_Admin' )->update(array('s_secret' => osc_genRandomPassword(), 's_password' => sha1(Params::getParam('new_password'))), array('pk_i_id' => $admin['pk_i_id']));
					osc_add_flash_ok_message(_m('The password has been changed'), 'admin');
					$this->redirectTo(osc_admin_base_url());
				}
				else
				{
					osc_add_flash_error_message(_m('Error, the password don\'t match'), 'admin');
					$this->redirectTo(osc_forgot_admin_password_confirm_url(Params::getParam('adminId'), Params::getParam('code')));
				}
			}
			else
			{
				osc_add_flash_error_message(_m('Sorry, the link is not valid'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url());
			break;
		}
	}

	public function doView($file) 
	{
		require osc_admin_base_path() . $file;
	}
}
