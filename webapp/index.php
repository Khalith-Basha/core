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

define( 'ABS_PATH', dirname( $_SERVER['SCRIPT_FILENAME'] ) );
define( 'CTRL_PATH', ABS_PATH . DIRECTORY_SEPARATOR . 'controllers' );

    require_once CTRL_PATH . '/load.php' ;
    
    if( file_exists(ABS_PATH . '.maintenance') ) {
        if(!osc_is_admin_user_logged_in()) {
            require_once 'osc/helpers/hErrors.php' ;

            $title = 'OpenSourceClassifieds &raquo; Error' ;
            $message = sprintf(__('We are sorry for any inconvenience. %s is under maintenance mode') . '.', osc_page_title() ) ;

            osc_die($title, $message) ;
        } else {
            define('__OSC_MAINTENANCE__', true);
        }
    }

    if(!osc_users_enabled() && osc_is_web_user_logged_in()) {
        Session::newInstance()->_drop('userId') ;
        Session::newInstance()->_drop('userName') ;
        Session::newInstance()->_drop('userEmail') ;
        Session::newInstance()->_drop('userPhone') ;

        Cookie::newInstance()->pop('oc_userId') ;
        Cookie::newInstance()->pop('oc_userSecret') ;
        Cookie::newInstance()->set() ;
    }

	$page = Params::getParam( 'page' );
    if ( empty( $page ) )
	    $page = 'index';
	$action = Params::getParam( 'action' );
    if( empty( $action ) )
	$action = 'index';

	$controllerPath = "controllers/$page/$action.php";
	if( file_exists( $controllerPath ) )
	{
		require $controllerPath;

		$className = 'CWeb' . ucfirst( $page );
		if ( class_exists( $className ) )
		{
			$classInstance = new $className;
			$classInstance->doModel();
		}

		exit( 0 );
	}
    switch( $page )
    {
        case ('cron'):      // cron system
                            define('__FROM_CRON__', true);
                            require_once(osc_lib_path() . 'osclass/cron.php');
        break;
        case ('user'):      // user pages (with security)
                            if($action=='change_email_confirm' || Params::getParam('action')=='activate_alert'
                            || (Params::getParam('action')=='unsub_alert' && !osc_is_web_user_logged_in())
                            || Params::getParam('action')=='contact_post'
                            || Params::getParam('action')=='pub_profile') {
                                require_once( CTRL_PATH . '/user-non-secure.php') ;
                                $do = new CWebUserNonSecure() ;
                                $do->doModel() ;
                            } else {
                                require_once( CTRL_PATH . '/user.php') ;
                                $do = new CWebUser() ;
                                $do->doModel() ;
                            }
        break;
        case ('ajax'):      // ajax
                            require_once( CTRL_PATH . '/ajax.php') ;
                            $do = new CWebAjax() ;
                            $do->doModel() ;
        break;
        case ('language'):  // set language
                            require_once( CTRL_PATH . '/language.php');
                            $do = new CWebLanguage();
                            $do->doModel();
        break;
        case ('custom'):   //contact
                            require_once( CTRL_PATH . '/custom.php') ;
                            $do = new CWebCustom() ;
                            $do->doModel() ;
        break;
    }

    if(!defined('__FROM_CRON__')) {
        if( osc_auto_cron() ) {
            osc_doRequest(osc_base_url(), array('page' => 'cron')) ;
        }
    }

