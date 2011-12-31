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

    class CWebUser extends WebSecBaseModel
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
                case('dashboard'):      //dashboard...
                                        $max_items = (Params::getParam('max_items')!='')?Params::getParam('max_items'):5;
                                        $aItems = Item::newInstance()->findByUserIDEnabled(Session::newInstance()->_get('userId'), 0, $max_items);
                                        //calling the view...
                                        $this->_exportVariableToView('items', $aItems) ;
                                        $this->_exportVariableToView('max_items', $max_items) ;
                                        $this->doView('user-dashboard.php') ;
                break ;
                case('profile_post'):   //profile post...
                                        $userId = Session::newInstance()->_get('userId') ;

                                        require_once 'osc/UserActions.php' ;
                                        $userActions = new UserActions(false) ;
                                        $success = $userActions->edit( $userId ) ;

                                        osc_add_flash_ok_message( _m('Your profile has been updated successfully') ) ;
                                        $this->redirectTo( osc_user_profile_url() ) ;
                break ;
                case('change_email'):           //change email
                                                $this->doView('user-change_email.php') ;
                break;
                case('change_email_post'):      //change email post
                                                if(!preg_match("/^[_a-z0-9-\+]+(\.[_a-z0-9-\+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", Params::getParam('new_email'))) {
                                                    osc_add_flash_error_message( _m('The specified e-mail is not valid')) ;
                                                    $this->redirectTo( osc_change_user_email_url() ) ;
                                                } else {
                                                    $user = User::newInstance()->findByEmail(Params::getParam('new_email'));
                                                    if(!isset($user['pk_i_id'])) {
                                                        $userEmailTmp = array() ;
                                                        $userEmailTmp['fk_i_user_id'] = Session::newInstance()->_get('userId') ;
                                                        $userEmailTmp['s_new_email'] = Params::getParam('new_email') ;

                                                        UserEmailTmp::newInstance()->insertOrUpdate($userEmailTmp) ;

                                                        $code = osc_genRandomPassword(30) ;
                                                        $date = date('Y-m-d H:i:s') ;

                                                        $userManager = new User() ;
                                                        $userManager->update (
                                                            array( 's_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR'] )
                                                            ,array( 'pk_i_id' => Session::newInstance()->_get('userId') )
                                                        );

                                                        $validation_url = osc_change_user_email_confirm_url( Session::newInstance()->_get('userId'), $code ) ;
                                                        osc_run_hook('hook_email_new_email', Params::getParam('new_email'), $validation_url);
                                                        $this->redirectTo( osc_user_profile_url() ) ;
                                                    } else {
                                                        osc_add_flash_error_message( _m('The specified e-mail is already in use')) ;
                                                        $this->redirectTo( osc_change_user_email_url() ) ;
                                                    }
                                                }
                break;
                case('change_password'):        //change password
                                                $this->doView('user-change_password.php') ;
                break;
                case 'change_password_post':    //change password post
                                                $user = User::newInstance()->findByPrimaryKey( Session::newInstance()->_get('userId') ) ;

                                                if( (Params::getParam('password') == '') || (Params::getParam('new_password') == '') || (Params::getParam('new_password2') == '') ) {
                                                    osc_add_flash_warning_message( _m('Password cannot be blank')) ;
                                                    $this->redirectTo( osc_change_user_password_url() ) ;
                                                }

                                                if( $user['s_password'] != sha1( Params::getParam('password') ) ) {
                                                    osc_add_flash_error_message( _m('Current password doesn\'t match')) ;
                                                    $this->redirectTo( osc_change_user_password_url() ) ;
                                                }

                                                if( !Params::getParam('new_password') ) {
                                                    osc_add_flash_error_message( _m('Passwords can\'t be empty')) ;
                                                    $this->redirectTo( osc_change_user_password_url() ) ;
                                                }

                                                if( Params::getParam('new_password') != Params::getParam('new_password2') ) {
                                                    osc_add_flash_error_message( _m('Passwords don\'t match'));
                                                    $this->redirectTo( osc_change_user_password_url() ) ;
                                                }

                                                User::newInstance()->update(array( 's_password' => sha1( Params::getParam ('new_password') ) )
                                                                           ,array( 'pk_i_id' => Session::newInstance()->_get('userId') ) ) ;

                                                osc_add_flash_ok_message( _m('Password has been changed')) ;
                                                $this->redirectTo( osc_user_profile_url() ) ;
                break;
                case 'activate_alert':
                    $email  = Params::getParam('email');
                    $secret = Params::getParam('secret');

                    $result = 0;
                    if($email!='' && $secret!='') {
                        $result = Alerts::newInstance()->activate($email, $secret );
                    }

                    if( $result == 1 ) {
                        osc_add_flash_ok_message(_m('Alert activated'));
                    }else{
                        osc_add_flash_error_message(_m('Ops! There was a problem trying to activate alert. Please contact the administrator'));
                    }

                    $this->redirectTo( osc_base_url(true) );
                break;
                case 'unsub_alert':
                    $email  = Params::getParam('email');
                    $secret = Params::getParam('secret');
                    if($email!='' && $secret!='') {
                        Alerts::newInstance()->delete(array('s_email' => $email, 's_secret' => $secret));
                        osc_add_flash_ok_message(_m('Unsubscribed correctly'));
                    } else {
                        osc_add_flash_error_message(_m('Ops! There was a problem trying to unsubscribe you. Please contact the administrator'));
                    }
                    $this->redirectTo(osc_user_alerts_url());
                break;


                case 'deleteResource':
                    $id   = Params::getParam('id') ;
                    $name = Params::getParam('name') ;
                    $fkid = Params::getParam('fkid') ;

                    osc_deleteResource($id);

                    ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name) );

                    $this->redirectTo( osc_base_url(true) . "?page=item&action=item_edit&id=" . $fkid );
                break;

            }
        }

        //hopefully generic...
        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

