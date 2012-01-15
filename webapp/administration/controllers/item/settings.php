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
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		parent::doModel();
		$this->doView('items/settings.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$iUpdated = 0;
		$enabledRecaptchaItems = Params::getParam('enabled_recaptcha_items');
		$enabledRecaptchaItems = (($enabledRecaptchaItems != '') ? true : false);
		$moderateItems = Params::getParam('moderate_items');
		$moderateItems = (($moderateItems != '') ? true : false);
		$numModerateItems = Params::getParam('num_moderate_items');
		$itemsWaitTime = Params::getParam('items_wait_time');
		$loggedUserItemValidation = Params::getParam('logged_user_item_validation');
		$loggedUserItemValidation = (($loggedUserItemValidation != '') ? true : false);
		$regUserPost = Params::getParam('reg_user_post');
		$regUserPost = (($regUserPost != '') ? true : false);
		$notifyNewItem = Params::getParam('notify_new_item');
		$notifyNewItem = (($notifyNewItem != '') ? true : false);
		$notifyContactItem = Params::getParam('notify_contact_item');
		$notifyContactItem = (($notifyContactItem != '') ? true : false);
		$notifyContactFriends = Params::getParam('notify_contact_friends');
		$notifyContactFriends = (($notifyContactFriends != '') ? true : false);
		$enabledFieldPriceItems = Params::getParam('enableField#f_price@items');
		$enabledFieldPriceItems = (($enabledFieldPriceItems != '') ? true : false);
		$enabledFieldImagesItems = Params::getParam('enableField#images@items');
		$enabledFieldImagesItems = (($enabledFieldImagesItems != '') ? true : false);
		$numImagesItems = Params::getParam('numImages@items');
		if ($numImagesItems == '') 
		{
			$numImagesItems = 0;
		}
		$regUserCanContact = Params::getParam('reg_user_can_contact');
		$regUserCanContact = (($regUserCanContact != '') ? true : false);
		$contactItemAttachment = Params::getParam('item_attachment');
		$contactItemAttachment = (($contactItemAttachment != '') ? true : false);
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledRecaptchaItems), array('s_name' => 'enabled_recaptcha_items'));
		if ($moderateItems) 
		{
			$iUpdated+= Preference::newInstance()->update(array('s_value' => $numModerateItems), array('s_name' => 'moderate_items'));
		}
		else
		{
			$iUpdated+= Preference::newInstance()->update(array('s_value' => '-1'), array('s_name' => 'moderate_items'));
		}
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $loggedUserItemValidation), array('s_name' => 'logged_user_item_validation'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $regUserPost), array('s_name' => 'reg_user_post'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyNewItem), array('s_name' => 'notify_new_item'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyContactItem), array('s_name' => 'notify_contact_item'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $notifyContactFriends), array('s_name' => 'notify_contact_friends'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledFieldPriceItems), array('s_name' => 'enableField#f_price@items'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $enabledFieldImagesItems), array('s_name' => 'enableField#images@items'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $itemsWaitTime), array('s_name' => 'items_wait_time'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $numImagesItems), array('s_name' => 'numImages@items'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $regUserCanContact), array('s_name' => 'reg_user_can_contact'));
		$iUpdated+= Preference::newInstance()->update(array('s_value' => $contactItemAttachment), array('s_name' => 'item_attachment'));
		if ($iUpdated > 0) 
		{
			osc_add_flash_ok_message(_m('Items\' settings have been updated'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=item&action=settings');
	}
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}
