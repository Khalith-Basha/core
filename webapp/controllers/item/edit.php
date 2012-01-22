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
	function __construct() 
	{
		parent::__construct();
		$this->itemManager = ClassLoader::getInstance()->getClassInstance( 'Model_Item' );
		// here allways userId == ''
		if (osc_is_web_user_logged_in()) 
		{
			$this->userId = osc_logged_user_id();
			$this->user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($this->userId);
		}
		else
		{
			$this->userId = null;
			$this->user = null;
		}
	}
	function doModel() 
	{
		$locales = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		switch ($this->action) 
		{
		case 'item_edit': // edit item
			$secret = Params::getParam('secret');
			$id = Params::getParam('id');
			$item = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
			if (count($item) == 1) 
			{
				$item = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByPrimaryKey($id);
				$form = count($this->getSession()->_getForm());
				$keepForm = count($this->getSession()->_getKeepForm());
				if ($form == 0 || $form == $keepForm) 
				{
					$this->getSession()->_dropKeepForm();
				}
				$this->getView()->assign('item', $item);
				osc_run_hook("before_item_edit", $item);
				$this->doView('item-edit.php');
			}
			else
			{
				// add a flash message [ITEM NO EXISTE]
				osc_add_flash_error_message(_m('Sorry, we don\'t have any items with that ID'));
				if ($this->user != null) 
				{
					$this->redirectTo(osc_user_list_items_url());
				}
				else
				{
					$this->redirectTo(osc_base_url());
				}
			}
			break;

		case 'item_edit_post':
			// recoger el secret y el
			$secret = Params::getParam('secret');
			$id = Params::getParam('id');
			$item = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
			if (count($item) == 1) 
			{
				$this->getView()->assign('item', $item[0]);
				$mItems = new ItemActions(false);
				// prepare data for ADD ITEM
				$mItems->prepareData(false);
				// set all parameters into session
				foreach ($mItems->data as $key => $value) 
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
				if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
				{
					if (!osc_check_recaptcha()) 
					{
						osc_add_flash_error_message(_m('The Recaptcha code is wrong'));
						$this->redirectTo(osc_item_post_url());
						return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
						
					}
				}
				$success = $mItems->edit();
				osc_run_hook('edited_item', ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByPrimaryKey($id));
				if ($success == 1) 
				{
					osc_add_flash_ok_message(_m('Great! We\'ve just updated your item'));
					$this->redirectTo(osc_base_url(true) . "?page=item&id=$id");
				}
				else
				{
					osc_add_flash_error_message($success);
					$this->redirectTo(osc_item_edit_url($secret));
				}
			}
			break;
		}
	}
	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		$this->getSession()->_clearVariables();
		osc_run_hook("after_html");
	}
}
