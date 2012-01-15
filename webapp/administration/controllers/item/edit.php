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
class CAdminItem extends AdminSecBaseModel
{
	private $itemManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->itemManager = Item::newInstance();
	}

	function doModel() 
	{
		parent::doModel();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = Params::getParam('id');
		$item = Item::newInstance()->findByPrimaryKey($id);
		if (count($item) <= 0) 
		{
			$this->redirectTo(osc_admin_base_url(true) . "?page=item");
		}
		$form = count(Session::newInstance()->_getForm());
		$keepForm = count(Session::newInstance()->_getKeepForm());
		if ($form == 0 || $form == $keepForm) 
		{
			Session::newInstance()->_dropKeepForm();
		}
		$this->_exportVariableToView("item", $item);
		$this->_exportVariableToView("new_item", FALSE);
		osc_current_admin_theme_path( 'items/frm.php' );
		Session::newInstance()->_clearVariables();
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$mItems = new ItemActions(true);
		$mItems->prepareData(false);
		// set all parameters into session
		foreach ($mItems->getData() as $key => $value) 
		{
			Session::newInstance()->_setForm($key, $value);
		}
		$meta = Params::getParam('meta');
		if (is_array($meta)) 
		{
			foreach ($meta as $key => $value) 
			{
				Session::newInstance()->_setForm('meta_' . $key, $value);
				Session::newInstance()->_keepForm('meta_' . $key);
			}
		}
		$success = $mItems->edit();
		if ($success == 1) 
		{
			$id = Params::getParam('userId');
			if ($id != '') 
			{
				$user = User::newInstance()->findByPrimaryKey($id);
				Item::newInstance()->update(array('fk_i_user_id' => $id, 's_contact_name' => $user['s_name'], 's_contact_email' => $user['s_email']), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret')));
			}
			else
			{
				Item::newInstance()->update(array('fk_i_user_id' => NULL, 's_contact_name' => Params::getParam('contactName'), 's_contact_email' => Params::getParam('contactEmail')), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret')));
			}
			osc_add_flash_ok_message(_m('Changes saved correctly'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=item");
		}
		else
		{
			osc_add_flash_error_message($success, 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=item&action=edit&id=" . Params::getParam('id'));
		}
	}
}
