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

    class CWebItem extends BaseModel
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
                case 'add':
                    if( osc_reg_user_post() && $this->user == null ) {
                        osc_add_flash_warning_message( _m('Only registered users are allowed to post items') ) ;
                        $this->redirectTo(osc_user_login_url()) ;
                    }

                    $countries = Country::newInstance()->listAll();
                    $regions = array();
                    if( isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code']!='' ) {
                        $regions = Region::newInstance()->findByCountry($this->user['fk_c_country_code']);
                    } else if( count($countries) > 0 ) {
                        $regions = Region::newInstance()->findByCountry($countries[0]['pk_c_code']);
                    }
                    $cities = array();
                    if( isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id']!='' ) {
                        $cities = City::newInstance()->findByRegion($this->user['fk_i_region_id']) ;
                    } else if( count($regions) > 0 ) {
                        $cities = City::newInstance()->findByRegion($regions[0]['pk_i_id']) ;
                    }

                    $this->_exportVariableToView('countries',$countries ) ;
                    $this->_exportVariableToView('regions', $regions) ;
                    $this->_exportVariableToView('cities', $cities) ;

                    $form = count(Session::newInstance()->_getForm());
                    $keepForm = count(Session::newInstance()->_getKeepForm());
                    if($form==0 || $form==$keepForm) {
                        Session::newInstance()->_dropKeepForm();
                    }
                    
                    
                    if( Session::newInstance()->_getForm('countryId') != "" ) {
                        $countryId  = Session::newInstance()->_getForm('countryId') ;
                        $regions    = Region::newInstance()->findByCountry($countryId) ; 
                        $this->_exportVariableToView('regions', $regions) ;
                        if(Session::newInstance()->_getForm('regionId') != "" ) {
                            $regionId  = Session::newInstance()->_getForm('regionId') ;
                            $cities = City::newInstance()->findByRegion($regionId ) ;
                            $this->_exportVariableToView('cities', $cities ) ;
                        }
                    }
                    
                    $this->_exportVariableToView('user', $this->user) ;
                    
                    osc_run_hook('post_item');

                    $this->doView('item-post.php');
                    break;

                case 'add_post':
                    if( osc_reg_user_post() && $this->user == null ) {
                        osc_add_flash_warning_message( _m('Only registered users are allowed to post items') ) ;
                        $this->redirectTo( osc_base_url(true) ) ;
                    }
                    
                    $mItems = new ItemActions(false);
                    // prepare data for ADD ITEM
                    $mItems->prepareData(true);
                    // set all parameters into session
                    foreach( $mItems->data as $key => $value ) {
                        Session::newInstance()->_setForm($key,$value);
                    }
                    
                    $meta = Params::getParam('meta');
                    if(is_array($meta)) {
                        foreach( $meta as $key => $value ) {
                            Session::newInstance()->_setForm('meta_'.$key, $value);
                            Session::newInstance()->_keepForm('meta_'.$key);
                        }
                    }
                    
                    if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong')) ;
                            $this->redirectTo( osc_item_post_url() );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }
                    // POST ITEM ( ADD ITEM )
                    $success = $mItems->add();

                    if($success!=1 && $success!=2) {
                        osc_add_flash_error_message( $success) ;
                        $this->redirectTo( osc_item_post_url() );
                    } else {
                        Session::newInstance()->_dropkeepForm('meta_'.$key);
                        
                        if($success==1) {
                            osc_add_flash_ok_message( _m('Check your inbox to verify your email address')) ;
                        } else {
                            osc_add_flash_ok_message( _m('Your item has been published')) ;
                        }
                        
                        $itemId         = Params::getParam('itemId');
                        $item           = $this->itemManager->findByPrimaryKey($itemId);

                        osc_run_hook('posted_item', $item);
                        $category = Category::newInstance()->findByPrimaryKey(Params::getParam('catId'));
                        View::newInstance()->_exportVariableToView('category', $category);
                        $this->redirectTo(osc_search_category_url());
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

