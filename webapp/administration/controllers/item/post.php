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
class CAdminItem extends AdministrationController
{
	private $itemManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->itemManager = $this->getClassLoader()->getClassInstance( 'Model_Item' );
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$form = count($this->getSession()->_getForm());
		$keepForm = count($this->getSession()->_getKeepForm());
		if ($form == 0 || $form == $keepForm) 
		{
			$this->getSession()->_dropKeepForm();
		}
		$this->getView()->assign("new_item", TRUE);
		$this->doView('items/frm.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$mItem = new ItemActions(true);
		$mItem->prepareData(true);
		// set all parameters into session
		foreach ($mItem->data as $key => $value) 
		{
			$this->getSession()->_setForm($key, $value);
		}
		$meta = Params::getParam('meta');
		if (is_array($meta)) 
		{
			foreach ($meta as $key => $value) 
			{
				$this->getSession()->_setForm('meta_' . $key, $value);
				$this->getSession()->_keepForm('meta_' . $key);
			}
		}
		$success = $mItem->add();
		if ($success == 1 || $success == 2) 
		{
			osc_add_flash_ok_message(_m('A new item has been added'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=item");
		}
		else
		{
			osc_add_flash_error_message($success, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=item&action=post");
		}
	}
}

