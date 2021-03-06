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
class CWebItem extends Controller_Default
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

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$locales = $this->getClassLoader()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		$mItem = $this->getClassLoader()->getClassInstance( 'Manager_Item', true, array( false ) );
		$item = $this->getClassLoader()->getClassInstance( 'Model_Item' )->findByPrimaryKey(Params::getParam('id'));
		$itemUrl = $this->getClassLoader()->getClassInstance( 'Url_Item' )->getDetailsUrl( $item );
		$status = $mItem->add_comment();

		$session = $this->getSession();
		switch ($status)
		{
		case -1:
			$msg = _m('Sorry, we could not save your comment. Try again later');
			$session->addFlashMessage( $msg, 'ERROR' );
			break;

		case 1:
			$msg = _m('Your comment is awaiting moderation');
			$this->getSession()->addFlashMessage( $msg, 'INFO' );
			break;

		case 2:
			$msg = _m('Your comment has been approved');
			$session->addFlashMessage( $msg );
			break;

		case 3:
			$msg = _m('Please fill the required fields (name, email)');
			$session->addFlashMessage( $msg, 'WARNING' );
			break;

		case 4:
			$msg = _m('Please type a comment');
			$session->addFlashMessage( $msg, 'WARNING' );
			break;

		case 5:
			$msg = _m('Your comment has been marked as spam');
			$session->addFlashMessage( $msg, 'ERROR' );
			break;
		}
		$this->redirectTo( $itemUrl );
	}
}

