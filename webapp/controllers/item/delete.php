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

        //Business Layer...
        function doModel() {
            //calling the view...

            $locales = OSCLocale::newInstance()->listAllEnabled() ;
            $this->_exportVariableToView('locales', $locales) ;

	    $secret = Params::getParam('secret');
	    $id     = Params::getParam('id');
	    $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s') OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
	    if (count($item) == 1) {
		$mItems = new ItemActions(false);
		$success = $mItems->delete($item[0]['s_secret'], $item[0]['pk_i_id']);
		if($success) {
		    osc_add_flash_ok_message( _m('Your item has been deleted') ) ;
		} else {
		    osc_add_flash_error_message( _m('The item you are trying to delete couldn\'t be deleted') ) ;
		}
		if($this->user!=null) {
		    $this->redirectTo(osc_user_list_items_url());
		} else {
		    $this->redirectTo( osc_base_url() ) ;
		}
	    }else{
		osc_add_flash_error_message( _m('The item you are trying to delete couldn\'t be deleted') ) ;
		$this->redirectTo( osc_base_url() ) ;
	    }
        }

        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

