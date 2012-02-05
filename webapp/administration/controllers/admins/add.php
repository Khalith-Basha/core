<?php

/*
*      OSCLass – software for creating and publishing online classified
*                           advertising platforms
*
*                        Copyright (C) 2010 OSCLASS
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

class CAdminAdmins extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->doView( 'admins/add.php' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$this->adminManager = ClassLoader::getInstance()->getClassInstance( 'Model_Admin' ) ;
		    // adding a new admin
		    $sPassword = Params::getParam('s_password');
		    $sName     = Params::getParam('s_name');
		    $sEmail    = Params::getParam('s_email');
		    $sUserName = Params::getParam('s_username');

		    // cleaning parameters
		    $sPassword = strip_tags($sPassword);
		    $sPassword = trim($sPassword);
		    $sName     = strip_tags($sName);
		    $sName     = trim($sName);
		    $sEmail    = strip_tags($sEmail);
		    $sEmail    = trim($sEmail);
		    $sUserName = strip_tags($sUserName);
		    $sUserName = trim($sUserName);
		    
		    // Checks for legit data
		    if( !osc_validate_email($sEmail, true) ) {
			osc_add_flash_error_message( _m("Email invalid"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }
		    if( !osc_validate_username($sUserName) ) {
			osc_add_flash_error_message( _m("Username invalid"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }
		    if($sName=='') {
			osc_add_flash_error_message( _m("Real Name invalid"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }
		    if($sPassword=='') {
			osc_add_flash_error_message( _m("Password invalid"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }
		    $admin = $this->adminManager->findByEmail($sEmail);
		    if($admin) {
			osc_add_flash_error_message( _m("Email already in use"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }
		    $admin = $this->adminManager->findByUsername($sUserName);
		    if($admin) {
			osc_add_flash_error_message( _m("Username already in use"), 'admin');
			$this->redirectTo(osc_admin_base_url(true).'?page=admins&action=add');
		    }

		    $array = array('s_password' =>  sha1($sPassword)
				  ,'s_name'     =>  $sName
				  ,'s_email'    =>  $sEmail
				  ,'s_username' =>  $sUserName);

		    $isInserted = $this->adminManager->insert($array);

		    if($isInserted) {
			osc_add_flash_ok_message( _m('The admin has been added'), 'admin');
		    } else {
			osc_add_flash_error_message( _m('There have been an error adding a new admin'), 'admin') ;
		    }
		    $this->redirectTo(osc_admin_base_url(true).'?page=admins');
	}
}

