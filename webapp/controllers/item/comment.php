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
		$this->itemManager = Item::newInstance();
		// here allways userId == ''
		if (osc_is_web_user_logged_in()) 
		{
			$this->userId = osc_logged_user_id();
			$this->user = User::newInstance()->findByPrimaryKey($this->userId);
		}
		else
		{
			$this->userId = null;
			$this->user = null;
		}
	}
	function doModel() 
	{
		$locales = OSCLocale::newInstance()->listAllEnabled();
		$this->_exportVariableToView('locales', $locales);
		switch ($this->action) 
		{
		case 'add_comment':
			$mItem = new ItemActions(false);
			$status = $mItem->add_comment();
			switch ($status) 
			{
			case -1:
				$msg = _m('Sorry, we could not save your comment. Try again later');
				osc_add_flash_error_message($msg);
				break;

			case 1:
				$msg = _m('Your comment is awaiting moderation');
				osc_add_flash_info_message($msg);
				break;

			case 2:
				$msg = _m('Your comment has been approved');
				osc_add_flash_ok_message($msg);
				break;

			case 3:
				$msg = _m('Please fill the required fields (name, email)');
				osc_add_flash_warning_message($msg);
				break;

			case 4:
				$msg = _m('Please type a comment');
				osc_add_flash_warning_message($msg);
				break;

			case 5:
				$msg = _m('Your comment has been marked as spam');
				osc_add_flash_error_message($msg);
				break;
			}
			$this->redirectTo(osc_item_url());
			break;

		case 'delete_comment':
			$mItem = new ItemActions(false);
			$status = $mItem->add_comment();
			$itemId = Params::getParam('id');
			$commentId = Params::getParam('comment');
			$item = Item::newInstance()->findByPrimaryKey($itemId);
			if (count($item) == 0) 
			{
				osc_add_flash_error_message(_m('This item doesn\'t exist'));
				$this->redirectTo(osc_base_url(true));
			}
			View::newInstance()->_exportVariableToView('item', $item);
			if ($this->userId == null) 
			{
				osc_add_flash_error_message(_m('You must be logged in to delete a comment'));
				$this->redirectTo(osc_item_url());
			}
			$commentManager = ItemComment::newInstance();
			$aComment = $commentManager->findByPrimaryKey($commentId);
			if (count($aComment) == 0) 
			{
				osc_add_flash_error_message(_m('The comment doesn\'t exist'));
				$this->redirectTo(osc_item_url());
			}
			if ($aComment['b_active'] != 1) 
			{
				osc_add_flash_error_message(_m('The comment is not active, you cannot delete it'));
				$this->redirectTo(osc_item_url());
			}
			if ($aComment['fk_i_user_id'] != $this->userId) 
			{
				osc_add_flash_error_message(_m('The comment was not added by you, you cannot delete it'));
				$this->redirectTo(osc_item_url());
			}
			$commentManager->deleteByPrimaryKey($commentId);
			osc_add_flash_ok_message(_m('The comment has been deleted'));
			$this->redirectTo(osc_item_url());
			break;
		}
	}
	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		Session::newInstance()->_clearVariables();
		osc_run_hook("after_html");
	}
}
