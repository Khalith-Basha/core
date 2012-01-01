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

	    if( Params::getParam('id') == ''){
		$this->redirectTo(osc_base_url());
	    }

	    if( Params::getParam('lang') != '' ) {
		Session::newInstance()->_set('userLocale', Params::getParam('lang'));
	    };

	    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
	    // if item doesn't exist redirect to base url
	    if( count($item) == 0 ){
		osc_add_flash_error_message( _m('This item doesn\'t exist') );
		$this->redirectTo( osc_base_url(true) );
	    }else{
		if ($item['b_active'] != 1) {
		    if( $this->userId == $item['fk_i_user_id'] ) {
			osc_add_flash_error_message( _m('The item hasn\'t been validated. Please validate it in order to show it to the rest of users') );
		    } else {
			osc_add_flash_error_message( _m('This item hasn\'t been validated') );
			$this->redirectTo( osc_base_url(true) );
		    }
		} else if ($item['b_enabled'] == 0) {
		    osc_add_flash_error_message( _m('The item has been suspended') );
		    $this->redirectTo( osc_base_url(true) );
		}
		$mStats = new ItemStats();
		$mStats->increase('i_num_views', $item['pk_i_id']);

		foreach($item['locale'] as $k => $v) {
		    $item['locale'][$k]['s_title'] = osc_apply_filter('item_title',$v['s_title']);
		    $item['locale'][$k]['s_description'] = nl2br(osc_apply_filter('item_description',$v['s_description']));
		}

		$this->_exportVariableToView('items', array($item)) ;

		osc_run_hook('show_item', $item) ;

		$this->doView('item.php') ;
	    }
        }

        function doView($file) {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

