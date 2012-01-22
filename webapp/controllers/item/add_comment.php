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
		$locales = $this->getClassLoader()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		$mItem = $this->getClassLoader()->getClassInstance( 'ItemActions', true, array( false ) );
		$itemUrl = '/'; // @TODO FIX $this->getClassLoader()->getClassInstance( 'Url_Item' )->getDetailsUrl( $item );
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
		$this->redirectTo( $itemUrl );
	}
}
