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
class CWebItem extends Controller_Default
{
	private $itemManager;

	public function init() 
	{
		$this->itemManager = ClassLoader::getInstance()->getClassInstance( 'Model_Item' );
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$locales = $classLoader->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$item = $this->itemManager->findByPrimaryKey(Params::getParam('id'));

		$classLoader->loadFile( 'helpers/security' );
		$view = $this->getView();
		$view->assign('locales', $locales);
		$view->assign('item', $item);
		$view->addJavaScript( osc_current_web_theme_js_url('jquery.validate.min.js') );
		$view->addJavaScript( '/static/scripts/send-friend.js' );
		$view->setTitle( __('Send to a friend', 'modern') . ' - ' . osc_item_title( $item ) . ' - ' . osc_page_title( $item ) );
		echo $view->render( 'item/send-friend' );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$item = $this->itemManager->findByPrimaryKey(Params::getParam('id'));
		$this->getView()->assign('item', $item);
		$this->getSession()->_setForm("yourEmail", Params::getParam('yourEmail'));
		$this->getSession()->_setForm("yourName", Params::getParam('yourName'));
		$this->getSession()->_setForm("friendName", Params::getParam('friendName'));
		$this->getSession()->_setForm("friendEmail", Params::getParam('friendEmail'));
		$this->getSession()->_setForm("message_body", Params::getParam('message'));
		if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
		{
			if (!osc_check_recaptcha()) 
			{
				osc_add_flash_error_message(_m('The Recaptcha code is wrong'));
				$this->redirectTo(osc_item_send_friend_url());
				return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
				
			}
		}

		$classLoader = $this->getClassLoader();
		$itemUrls = $classLoader->getClassInstance( 'Url_Item' );

		$classLoader = ClassLoader::getInstance();
		$item = $this->itemManager->findByPrimaryKey(Params::getParam('id'));
		$aItem['item'] = $item;
		ClassLoader::getInstance()->getClassInstance( 'View_Html' )->assign('item', $aItem['item']);
		$aItem['yourName'] = Params::getParam('yourName');
		$aItem['yourEmail'] = Params::getParam('yourEmail');
		$aItem['friendName'] = Params::getParam('friendName');
		$aItem['friendEmail'] = Params::getParam('friendEmail');
		$aItem['s_title'] = Params::getParam('s_title');
		$aItem['message'] = Params::getParam('message');

		$item = $aItem['item'];
		$s_title = $aItem['s_title'];
		$classLoader->getClassInstance( 'View_Html' )->assign('item', $item);
		osc_run_hook('hook_email_send_friend', $aItem);
		$item_url = $classLoader->getClassInstance( 'Url_Item' )->getDetailsUrl( $item );
		$item_url = '<a href="' . $item_url . '" >' . $item_url . '</a>';
		Params::setParam('item_url', $item_url);
		osc_add_flash_ok_message(sprintf(_m('We just send your message to %s'), $aItem['friendName']));

		$success = true;
		if ($success) 
		{
			$this->getSession()->_clearVariables();
			$this->redirectTo( $itemUrls->getDetailsUrl( $item ) );
		}
		else
		{
			$this->redirectTo( $itemUrls->osc_item_send_friend_url( $item ) );
		}
	}
}

