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

    class CWebItem extends Controller
    {
        private $itemManager;
        private $user;
        private $userId;

        function __construct() {
            parent::__construct() ;
            $this->itemManager = Item::newInstance();

            // here allways userId == ''
            if( osc_is_web_user_logged_in() ){
                $this->userId = osc_logged_user_id();
                $this->user = User::newInstance()->findByPrimaryKey($this->userId);
            }else{
                $this->userId = null;
                $this->user = null;
            }
        }

        function doModel() {
            $locales = OSCLocale::newInstance()->listAllEnabled() ;
            $this->_exportVariableToView('locales', $locales) ;

            switch( $this->action ){
                case 'send_friend':
                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );

                    $this->_exportVariableToView('item', $item) ;

                    $this->doView('item-send-friend.php');
                break;
                case 'send_friend_post':
                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                    $this->_exportVariableToView('item', $item) ;
                    
                    Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
                    Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
                    Session::newInstance()->_setForm("friendName", Params::getParam('friendName'));
                    Session::newInstance()->_setForm("friendEmail", Params::getParam('friendEmail'));
                    Session::newInstance()->_setForm("message_body",Params::getParam('message'));

                    if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong')) ;
                            $this->redirectTo(osc_item_send_friend_url() );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }
                    
                    $mItem = new ItemActions(false);
                    $success = $mItem->send_friend();

                    if($success){
                        Session::newInstance()->_clearVariables();
                        $this->redirectTo( osc_item_url() );
                    } else {
                        $this->redirectTo(osc_item_send_friend_url() );
                    }
                break;
            }
        }

        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

