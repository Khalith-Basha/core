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
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$userModel = $this->getClassLoader()->getClassInstance( 'Model_User' );
		$admin = $userModel->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
		if ($admin) 
		{
			require osc_admin_base_path() . 'gui/forgot_password.php';
		}
		else
		{
			$this->getSession()->addFlashMessage( _m('Sorry, the link is not valid'), 'admin', 'ERROR' );
			$this->redirectTo(osc_admin_base_url());
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$userModel = $this->getClassLoader()->getClassInstance( 'Model_User' );
		$admin = $userModel->findByIdSecret(Params::getParam('adminId'), Params::getParam('code'));
		if ($admin) 
		{
			if (Params::getParam('new_password') == Params::getParam('new_password2')) 
			{
				$userModel->update(array('s_secret' => osc_genRandomPassword(), 's_password' => sha1(Params::getParam('new_password'))), array('pk_i_id' => $admin['pk_i_id']));
				$this->getSession()->addFlashMessage( _m('The password has been changed'), 'admin' );
				$this->redirectTo(osc_admin_base_url());
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('Error, the password don\'t match'), 'admin', 'ERROR' );
				$this->redirectTo(osc_forgot_admin_password_confirm_url(Params::getParam('adminId'), Params::getParam('code')));
			}
		}
		else
		{
			$this->getSession()->addFlashMessage( _m('Sorry, the link is not valid'), 'admin', 'ERROR' );
		}
		$this->redirectTo(osc_admin_base_url());
	}
}

