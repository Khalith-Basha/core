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

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$locales = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->getView()->assign('locales', $locales);
		$item = $this->itemManager->findByPrimaryKey(Params::getParam('id'));
		if (empty($item)) 
		{
			osc_add_flash_error_message(_m('This item doesn\'t exist'));
			$this->redirectTo(osc_base_url(true));
		}
		else
		{
			$view = $this->getView();
			$view->assign( 'item', $item );
			$view->addJavaScript( osc_current_web_theme_js_url('jquery.validate.min.js') );
			$view->addJavaScript( '/static/scripts/contact.js' );
			if (osc_item_is_expired()) 
			{
				osc_add_flash_error_message(_m('We\'re sorry, but the item has expired. You can\'t contact the seller'));
				$this->redirectTo(osc_item_url());
			}
			if (osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact()) 
			{
				echo $view->render( 'item/contact' );
			}
			else
			{
				osc_add_flash_error_message(_m('You can\'t contact the seller, only registered users can'));
				$this->redirectTo(osc_item_url());
			}
		}
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$item = $this->itemManager->findByPrimaryKey(Params::getParam('id'));
		$this->getView()->assign('item', $item);
		if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
		{
			if (!osc_check_recaptcha()) 
			{
				osc_add_flash_error_message(_m('The Recaptcha code is wrong'));
				$this->getSession()->_setForm("yourEmail", Params::getParam('yourEmail'));
				$this->getSession()->_setForm("yourName", Params::getParam('yourName'));
				$this->getSession()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
				$this->getSession()->_setForm("message_body", Params::getParam('message'));
				$this->redirectTo(osc_item_url());
				return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
				
			}
		}
		$category = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->findByPrimaryKey($item['fk_i_category_id']);
		if ($category['i_expiration_days'] > 0) 
		{
			$item_date = strtotime($item['pub_date']) + ($category['i_expiration_days'] * (24 * 3600));
			$date = time();
			if ($item_date < $date && $item['b_premium'] != 1) 
			{
				// The item is expired, we can not contact the seller
				osc_add_flash_error_message(_m('We\'re sorry, but the item has expired. You can\'t contact the seller'));
				$this->redirectTo(osc_item_url());
			}
		}
		$mItem = new ItemActions(false);
		$result = $mItem->contact();
		if (is_string($result)) 
		{
			osc_add_flash_error_message($result);
		}
		else
		{
			osc_add_flash_ok_message(_m('We\'ve just sent an e-mail to the seller'));
		}
		$this->redirectTo(osc_item_url());
	}

	function doView($file) 
	{
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		$this->getSession()->_clearVariables();
		osc_run_hook("after_html");
	}
}
