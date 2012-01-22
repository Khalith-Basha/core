<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
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
class CWebUser extends Controller
{
	function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users not enabled'));
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$httpReferer = $this->getServer()->getHttpReferer();

		if (!osc_users_enabled()) 
		{
			osc_add_flash_error_message(_m('Users are not enabled'));
			$this->redirectTo(osc_base_url());
		}
		require_once 'osc/UserActions.php';
		$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByEmail(Params::getParam('email'));
		$url_redirect = osc_user_dashboard_url();
		$page_redirect = '';
		if (osc_rewrite_enabled()) 
		{
			if( !is_null( $httpReferer ) ) 
			{
				$request_uri = urldecode(preg_replace('@^' . osc_base_url() . '@', "", $httpReferer ));
				$tmp_ar = explode("?", $request_uri);
				$request_uri = $tmp_ar[0];
				$rules = Rewrite::newInstance()->getRules();
				foreach ($rules as $rule) 
				{
					$match = $rule->rePath;
					$uri = $rule->request;
					if (preg_match('#' . $match . '#', $request_uri, $m)) 
					{
						$request_uri = preg_replace('#' . $match . '#', $uri, $request_uri);
						if (preg_match('|([&?]{1})page=([^&]*)|', '&' . $request_uri . '&', $match)) 
						{
							$page_redirect = $match[2];
						}
						break;
					}
				}
			}
		}
		else if (preg_match('|[\?&]page=([^&]+)|', $httpReferer . '&', $match)) 
		{
			$page_redirect = $match[1];
		}
		if (Params::getParam('http_referer') != '') 
		{
			Session::newInstance()->_setReferer(Params::getParam('http_referer'));
			$url_redirect = Params::getParam('http_referer');
		}
		else if (Session::newInstance()->_getReferer() != '') 
		{
			Session::newInstance()->_setReferer(Session::newInstance()->_getReferer());
			$url_redirect = Session::newInstance()->_getReferer();
		}
		else if ($page_redirect != '' && $page_redirect != 'login') 
		{
			Session::newInstance()->_setReferer( $httpReferer );
			$url_redirect = $httpReferer;
		}
		if (!$user) 
		{
			osc_add_flash_error_message(_m('The username doesn\'t exist'));

			$res->sendRedirection( osc_user_login_url() );
		}
		if ($user["s_password"] != sha1(Params::getParam('password'))) 
		{
			osc_add_flash_error_message(_m('The password is incorrect'));

			$res->sendRedirection( osc_user_login_url() );
		}
		$uActions = new UserActions(false);
		$logged = $uActions->bootstrap_login($user['pk_i_id']);
		if ($logged == 0) 
		{
			osc_add_flash_error_message(_m('The username doesn\'t exist'));
		}
		else if ($logged == 1) 
		{
			osc_add_flash_error_message(_m('The user has not been validated yet'));
		}
		else if ($logged == 2) 
		{
			osc_add_flash_error_message(_m('The user has been suspended'));
		}
		else if ($logged == 3) 
		{
			if (Params::getParam('remember') == 1) 
			{
				//this include contains de osc_genRandomPassword function
				require_once 'osc/helpers/hSecurity.php';
				$secret = osc_genRandomPassword();
				ClassLoader::getInstance()->getClassInstance( 'Model_User' )->update(array('s_secret' => $secret), array('pk_i_id' => $user['pk_i_id']));
				Cookie::newInstance()->set_expires(osc_time_cookie());
				Cookie::newInstance()->push('oc_userId', $user['pk_i_id']);
				Cookie::newInstance()->push('oc_userSecret', $secret);
				Cookie::newInstance()->set();
			}

			$res->sendRedirection( $url_redirect );
		}
		else
		{
			osc_add_flash_error_message(_m('This should never happens'));
		}

		$res->sendRedirection( osc_user_login_url() );
	}
}

