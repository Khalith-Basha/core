<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebLogin extends BaseModel
    {

        function __construct() {
            parent::__construct() ;
            if( !osc_users_enabled() ) {
                osc_add_flash_error_message( _m('Users not enabled') ) ;
                $this->redirectTo(osc_base_url(true));
            }
        }

        //Business Layer...
        function doModel() {
            switch( $this->action ) {
                case('forgot'):         //form to recover the password (in this case we have the form in /gui/)
                                        $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                        if($user) {
                                            $this->doView( 'user-forgot_password.php' ) ;
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid')) ;
                                            $this->redirectTo( osc_base_url() ) ;
                                        }
                break;
                case('forgot_post'):
                                        if( (Params::getParam('new_password') == '') || (Params::getParam('new_password2') == '') ) {
                                            osc_add_flash_warning_message( _m('Password cannot be blank')) ;
                                            $this->redirectTo(osc_forgot_user_password_confirm_url(Params::getParam('userId'), Params::getParam('code')));
                                        }

                                        $user = User::newInstance()->findByIdPasswordSecret(Params::getParam('userId'), Params::getParam('code'));
                                        if($user['b_enabled'] == 1) {
                                            if(Params::getParam('new_password')==Params::getParam('new_password2')) {
                                                User::newInstance()->update(
                                                    array('s_pass_code' => osc_genRandomPassword(50)
                                                        , 's_pass_date' => date('Y-m-d H:i:s', 0)
                                                        , 's_pass_ip' => $_SERVER['REMOTE_ADDR']
                                                        , 's_password' => sha1(Params::getParam('new_password'))
                                                    ), array('pk_i_id' => $user['pk_i_id'])
                                                );
                                                osc_add_flash_ok_message( _m('The password has been changed'));
                                                $this->redirectTo(osc_user_login_url());
                                            } else {
                                                osc_add_flash_error_message( _m('Error, the password don\'t match')) ;
                                                $this->redirectTo(osc_forgot_user_password_confirm_url(Params::getParam('userId'), Params::getParam('code')));
                                            }
                                        } else {
                                            osc_add_flash_error_message( _m('Sorry, the link is not valid')) ;
                                        }
                                        $this->redirectTo( osc_base_url() ) ;
                break;
            }

        }

        //hopefully generic...
        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            osc_run_hook("after_html");
        }
    }

$do = new CWebLogin() ;
$do->doModel() ;

