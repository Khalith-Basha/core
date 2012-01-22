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

class CAdminAdmins extends AdministrationController
{
	private $adminManager ;

	function __construct()
	{
	    parent::__construct() ;

	    $this->adminManager = ClassLoader::getInstance()->getClassInstance( 'Model_Admin' ) ;
	}

	function doModel()
	{
	    parent::doModel() ;

    switch ($this->action)
    {
    case 'delete':
	    // deleting and admin
			    $isDeleted = false;
			    $adminId   = Params::getParam('id');

			    if(!is_array($adminId)) {
				osc_add_flash_error_message( _m('The admin id isn\'t in the correct format'), 'admin');
				$this->redirectTo(osc_admin_base_url(true).'?page=admins');
			    }

			    // Verification to avoid an administrator trying to remove to itself
			    if(in_array($this->getSession()->_get('adminId'), $adminId)) {
				osc_add_flash_error_message( _m('The operation hasn\'t been completed. You\'re trying to remove yourself!'), 'admin');
				$this->redirectTo(osc_admin_base_url(true).'?page=admins');
			    }

			    $isDeleted = $this->adminManager->delete(array('pk_i_id IN (' . implode(', ', $adminId) . ')')) ;

			    if($isDeleted) {
				osc_add_flash_ok_message( _m('The admin has been deleted correctly'), 'admin');
			    } else {
				osc_add_flash_error_message( _m('The admin couldn\'t be deleted'), 'admin');
			    }
			    $this->redirectTo(osc_admin_base_url(true).'?page=admins') ;
	break;
	default:            // calling manage admins view
			    $admins = $this->adminManager->listAll();

			    $this->_exportVariableToView("admins", $admins);
			    $this->doView('admins/index.php');
	break;
    }
}
}

