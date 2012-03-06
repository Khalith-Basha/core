<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
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

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$locales = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		switch ($this->action) 
		{
		case 'delete_comment':
			$mItem = new ItemActions(false);
			$status = $mItem->add_comment();
			$itemId = Params::getParam('id');
			$commentId = Params::getParam('comment');
			$item = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByPrimaryKey($itemId);
			if (count($item) == 0) 
			{
				osc_add_flash_error_message(_m('This item doesn\'t exist'));
				$this->redirectTo(osc_base_url(true));
			}
			View::newInstance()->assign('item', $item);
			if ($this->userId == null) 
			{
				$this->getSession()->addFlashMessage( _m('You must be logged in to delete a comment'), 'ERROR' );
				$this->redirectTo(osc_item_url());
			}
			$commentManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' );
			$aComment = $commentManager->findByPrimaryKey($commentId);
			if (count($aComment) == 0) 
			{
				osc_add_flash_error_message(_m('The comment doesn\'t exist'));
				$this->redirectTo(osc_item_url());
			}
			if ($aComment['b_active'] != 1) 
			{
				$this->getSession()->addFlashMessage( _m('The comment is not active, you cannot delete it'), 'ERROR' );
				$this->redirectTo(osc_item_url());
			}
			if ($aComment['fk_i_user_id'] != $this->userId) 
			{
				$this->getSession()->addFlashMessage( _m('The comment was not added by you, you cannot delete it'), 'ERROR' );
				$this->redirectTo(osc_item_url());
			}
			$commentManager->deleteByPrimaryKey($commentId);
			osc_add_flash_ok_message(_m('The comment has been deleted'));
			$this->redirectTo(osc_item_url());
			break;
		}
	}
}

