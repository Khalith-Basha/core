<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
class CWebUser extends Controller_Default
{
	public function init()
	{
		if( !osc_users_enabled() )
		{
			$this->getSession()->addFlashMessage( _m( 'Users are not enabled' ), 'ERROR' );
			$this->redirectToBaseUrl();
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$userUrls = $classLoader->getClassInstance( 'Url_User' );
		$email = $this->getInput()->getString( 'email' );
		$password = $this->getInput()->getString( 'password' );

		$userModel = $classLoader->getClassInstance( 'Model_User' );
		$user = $userModel->findByEmailPassword( $email, $password );
		if( is_null( $user ) )
		{
			$this->getSession()->addFlashMessage( _m( 'The username or password are wrong' ), 'ERROR' );

			$res->sendRedirection( $userUrls->osc_user_login_url() );
		}

		$logged = $classLoader->getClassInstance( 'Manager_User', false, array( false ) )
			->bootstrap_login( $user['pk_i_id'] );
		if ($logged == 0) 
		{
			osc_add_flash_error_message(_m('The username doesn\'t exist'));
		}
		else if ($logged == 1) 
		{
			$this->getSession()->addFlashMessage( _m('The user has not been validated yet'), 'ERROR' );
		}
		else if ($logged == 2) 
		{
			$this->getSession()->addFlashMessage( _m('The user has been suspended'), 'ERROR' );
		}
		else if ($logged == 3) 
		{
			if( true === $this->getInput()->getBoolean( 'remember' ) ) 
			{
				$classLoader->loadFile( 'helpers/security' );
				$secret = osc_genRandomPassword();
				$userModel->update(
					array( 's_secret' => $secret), array('pk_i_id' => $user['pk_i_id'] )
				);

				$cookie = $this->getCookie();
				$cookie->set_expires(osc_time_cookie());
				$cookie->push('oc_userId', $user['pk_i_id']);
				$cookie->push('oc_userSecret', $secret);
				$cookie->set();
			}
	
			$url_redirect = $userUrls->osc_user_dashboard_url();
			$httpReferer = $this->getInput()->getString( 'http_referer' );
			if( !is_null( $httpReferer ) )
				$url_redirect = $htpReferer;
			$res->sendRedirection( $url_redirect );
		}

		$res->sendRedirection( $userUrls->osc_user_login_url() );
	}
}

