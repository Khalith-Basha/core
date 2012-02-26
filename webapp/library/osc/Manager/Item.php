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

class Manager_Item
{
	private $manager = null;
	private $is_admin;
	private $data;
	function __construct($is_admin) 
	{
		$this->is_admin = $is_admin;
		$classLoader = ClassLoader::getInstance();
		$classLoader->loadFile( 'helpers/filtering' );
		$classLoader->loadFile( 'helpers/string' );
		$this->manager = $classLoader->getClassInstance( 'Model_Item' );
	}
	/**
	 * @return boolean
	 */
	public function add() 
	{
		$success = true;
		$aItem = $this->data;
		$code = osc_genRandomPassword();
		$flash_error = '';
		// Initiate HTML Purifier
		require_once 'htmlpurifier/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style]');
		$config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
		$config->set('Cache.SerializerPath', ABS_PATH . '/components/uploads');
		$purifier = new HTMLPurifier($config);
		// Requires email validation?
		$has_to_validate = (osc_moderate_items() != - 1) ? true : false;
		// Check status
		$active = $aItem['active'];
		// Sanitize
		foreach (@$aItem['title'] as $key => $value) 
		{
			$aItem['title'][$key] = strip_tags(trim($value));
		}
		foreach (@$aItem['description'] as $key => $value) 
		{
			$aItem['description'][$key] = $purifier->purify($value);
		}
		$aItem['price'] = !is_null($aItem['price']) ? strip_tags(trim($aItem['price'])) : $aItem['price'];
		$contactName = osc_sanitize_name(strip_tags(trim($aItem['contactName'])));
		$contactEmail = strip_tags(trim($aItem['contactEmail']));
		$aItem['cityArea'] = osc_sanitize_name(strip_tags(trim($aItem['cityArea'])));
		$aItem['address'] = osc_sanitize_name(strip_tags(trim($aItem['address'])));
		// Anonymous
		$contactName = (osc_validate_text($contactName, 3)) ? $contactName : __("Anonymous");
		// Validate
		if (!$this->checkAllowedExt($aItem['photos'])) 
		{
			$flash_error.= _m("Image with incorrect extension.") . PHP_EOL;
		}
		if (!$this->checkSize($aItem['photos'])) 
		{
			$flash_error.= _m("Images too big. Max. size ") . osc_max_size_kb() . " Kb" . PHP_EOL;
		}
		$title_message = '';
		foreach (@$aItem['title'] as $key => $value) 
		{
			if (osc_validate_text($value, 1) && osc_validate_max($value, 100)) 
			{
				$title_message = '';
				break;
			}
			$title_message.= (!osc_validate_text($value, 1) ? _m("Title too short.") . PHP_EOL : '') . (!osc_validate_max($value, 100) ? _m("Title too long.") . PHP_EOL : '');
		}
		$flash_error.= $title_message;
		$desc_message = '';
		foreach (@$aItem['description'] as $key => $value) 
		{
			if (osc_validate_text($value, 3) && osc_validate_max($value, 5000)) 
			{
				$desc_message = '';
				break;
			}
			$desc_message.= (!osc_validate_text($value, 3) ? _m("Description too short.") . PHP_EOL : '') . (!osc_validate_max($value, 5000) ? _m("Description too long.") . PHP_EOL : '');
		}
		$flash_error.= $desc_message;
		$flash_error.= (
			(!osc_validate_category($aItem['catId'])) ? _m("Category invalid.") . PHP_EOL : '') .
			((!osc_validate_number($aItem['price'])) ? _m("Price must be number.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['price'], 15)) ? _m("Price too long.") . PHP_EOL : '') .
			((!osc_validate_max($contactName, 35)) ? _m("Name too long.") . PHP_EOL : '') .
			((!osc_validate_email($contactEmail)) ? _m("Email invalid.") . PHP_EOL : '') .
			((!osc_validate_text($aItem['countryName'], 3, false)) ? _m("Country too short.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['countryName'], 50)) ? _m("Country too long.") . PHP_EOL : '') .
			((!osc_validate_text($aItem['regionName'], 3, false)) ? _m("Region too short.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['regionName'], 50)) ? _m("Region too long.") . PHP_EOL : '') .
			((!osc_validate_text($aItem['cityName'], 3, false)) ? _m("City too short.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['cityName'], 50)) ? _m("City too long.") . PHP_EOL : '') .
			((!osc_validate_text($aItem['cityArea'], 3, false)) ? _m("Municipality too short.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['cityArea'], 50)) ? _m("Municipality too long.") . PHP_EOL : '') .
			((!osc_validate_text($aItem['address'], 3, false)) ? _m("Address too short.") . PHP_EOL : '') .
			((!osc_validate_max($aItem['address'], 100)) ? _m("Address too long.") . PHP_EOL : '') .
			((((time() - ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('last_submit_item')) < osc_items_wait_time()) && !$this->is_admin) ? _m("Too fast. You should wait a little to publish your ad.") . PHP_EOL : '');
		$meta = Params::getParam("meta");
		if ($meta != '' && count($meta) > 0) 
		{
			$mField = Field::newInstance();
			foreach ($meta as $k => $v) 
			{
				if ($v == '') 
				{
					$field = $mField->findByPrimaryKey($k);
					if ($field['b_required'] == 1) 
					{
						$flash_error.= sprintf(_m("%s field is required."), $field['s_name']);
					}
				}
			}
		};
		$this->updateItemStatus($aItem);
		// hook pre add or edit
		osc_run_hook('pre_item_post');
		// Handle error
		if ($flash_error) 
		{
			return $flash_error;
		}
		else
		{
			$this->manager->insert(
				array(
					'fk_i_user_id' => $aItem['userId'],
					'fk_i_category_id' => $aItem['catId'],
					'i_price' => $aItem['price'],
					'fk_c_currency_code' => $aItem['currency'],
					's_contact_name' => $contactName,
					's_contact_email' => $contactEmail,
					's_secret' => $code,
					'b_active' => $aItem['b_active'],
					'b_enabled' => $aItem['b_enabled'],
					'b_show_email' => $aItem['showEmail'],
					'status' => $aItem['status']
				)
			);
			if (!$this->is_admin) 
			{
				// Track spam delay: Session
				ClassLoader::getInstance()->getClassInstance( 'Session' )->_set('last_submit_item', time());
				// Track spam delay: Cookie
				ClassLoader::getInstance()->getClassInstance( 'Cookie' )->set_expires(osc_time_cookie());
				ClassLoader::getInstance()->getClassInstance( 'Cookie' )->push('last_submit_item', time());
				ClassLoader::getInstance()->getClassInstance( 'Cookie' )->set();
			}
			$itemId = $this->manager->dao->insertedId();
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('item', 'add', $itemId, current(array_values($aItem['title'])), $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
			Params::setParam('itemId', $itemId);
			// INSERT title and description locales
			$this->insertItemLocales('ADD', $aItem['title'], $aItem['description'], $itemId);
			// INSERT location item
			$location = array('fk_i_item_id' => $itemId, 'fk_c_country_code' => $aItem['countryId'], 's_country' => $aItem['countryName'], 'fk_i_region_id' => $aItem['regionId'], 's_region' => $aItem['regionName'], 'fk_i_city_id' => $aItem['cityId'], 's_city' => $aItem['cityName'], 's_city_area' => $aItem['cityArea'], 's_address' => $aItem['address']);
			$locationManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemLocation' );
			$locationManager->insert($location);
			$this->uploadItemResources($aItem['photos'], $itemId);
			/**
			 * META FIELDS
			 */
			if ($meta != '' && count($meta) > 0) 
			{
				$mField = Field::newInstance();
				foreach ($meta as $k => $v) 
				{
					$mField->replace($itemId, $k, $v);
				}
			}
			osc_run_hook('item_form_post', $aItem['catId'], $itemId);
			// We need at least one record in t_item_stats
			$mStats = ClassLoader::getInstance()->getClassInstance( 'Model_ItemStats' );
			$mStats->emptyRow($itemId);
			$item = $this->manager->findByPrimaryKey($itemId);
			$aItem['item'] = $item;
			osc_run_hook('after_item_post');
			ClassLoader::getInstance()->getClassInstance( 'Session' )->_set('last_publish_time', time());
			if (!$this->is_admin) 
			{
				$this->sendEmails($aItem);
			}
			if ($active == 'INACTIVE') 
			{
				return 1;
			}
			else
			{
				if ($aItem['userId'] != null) 
				{
					$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($aItem['userId']);
					if ($user) 
					{
						ClassLoader::getInstance()->getClassInstance( 'Model_User' )->update(array('i_items' => $user['i_items'] + 1), array('pk_i_id' => $user['pk_i_id']));
					}
				}
				ClassLoader::getInstance()->getClassInstance( 'Model_CategoryStats' )->increaseNumItems($aItem['catId']);
				return 2;
			}
		}
		return $success;
	}

	/**
	 * Set the item status based on words and other filters.
	 */
	protected function updateItemStatus( array &$item )
	{
		$conn = ClassLoader::getInstance()->getClassInstance( 'Database_Connection' );

		$bwList = getBadWordsList( $conn->getResource() );

		$item['status'] = null;

		foreach( $bwList as $bwStatus => $badWords )
		{
			foreach( $badWords as $badWord )
			{
				foreach( $item['title'] as $title )
				{
					if( textHasWord( $title, $badWord ) )
					{
						$item['status'] = $bwStatus;
						break;
					}
				}
			}
		}

		$item['b_active'] = $item['b_enabled'] = is_null( $item['status'] );
	}

	function edit() 
	{
		$aItem = $this->data;
		$flash_error = '';
		// Initiate HTML Purifier
		require_once 'htmlpurifier/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style]');
		$config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
		$config->set('Cache.SerializerPath', ABS_PATH . '/components/uploads');
		$purifier = new HTMLPurifier($config);
		// Sanitize
		foreach (@$aItem['title'] as $key => $value) 
		{
			$aItem['title'][$key] = strip_tags(trim($value));
		}
		foreach (@$aItem['description'] as $key => $value) 
		{
			$aItem['description'][$key] = $purifier->purify($value);
		}
		$aItem['price'] = !is_null($aItem['price']) ? strip_tags(trim($aItem['price'])) : $aItem['price'];
		$aItem['cityArea'] = osc_sanitize_name(strip_tags(trim($aItem['cityArea'])));
		$aItem['address'] = osc_sanitize_name(strip_tags(trim($aItem['address'])));
		// Validate
		if (!$this->checkAllowedExt($aItem['photos'])) 
		{
			$flash_error.= _m("Image with incorrect extension.") . PHP_EOL;
		}
		if (!$this->checkSize($aItem['photos'])) 
		{
			$flash_error.= _m("Images too big. Max. size ") . osc_max_size_kb() . " Kb" . PHP_EOL;
		}
		$title_message = '';
		$td_message = '';
		foreach (@$aItem['title'] as $key => $value) 
		{
			if (osc_validate_text($value, 1) && osc_validate_max($value, 100)) 
			{
				$td_message = '';
				break;
			}
			$td_message.= (!osc_validate_text($value, 1) ? _m("Title too short.") . PHP_EOL : '') . (!osc_validate_max($value, 100) ? _m("Title too long.") . PHP_EOL : '');
		}
		$flash_error.= $td_message;
		$desc_message = '';
		foreach (@$aItem['description'] as $key => $value) 
		{
			if (osc_validate_text($value, 3) && osc_validate_max($value, 5000)) 
			{
				$desc_message = '';
				break;
			}
			$desc_message.= (!osc_validate_text($value, 3) ? _m("Description too short.") . PHP_EOL : '') . (!osc_validate_max($value, 5000) ? _m("Description too long.") . PHP_EOL : '');
		}
		$flash_error.= $desc_message;
		$flash_error.= ((!osc_validate_category($aItem['catId'])) ? _m("Category invalid.") . PHP_EOL : '') . ((!osc_validate_number($aItem['price'])) ? _m("Price must be number.") . PHP_EOL : '') . ((!osc_validate_max($aItem['price'], 15)) ? _m("Price too long.") . PHP_EOL : '') . ((!osc_validate_text($aItem['countryName'], 3, false)) ? _m("Country too short.") . PHP_EOL : '') . ((!osc_validate_max($aItem['countryName'], 50)) ? _m("Country too long.") . PHP_EOL : '') . ((!osc_validate_text($aItem['regionName'], 3, false)) ? _m("Region too short.") . PHP_EOL : '') . ((!osc_validate_max($aItem['regionName'], 50)) ? _m("Region too long.") . PHP_EOL : '') . ((!osc_validate_text($aItem['cityName'], 3, false)) ? _m("City too short.") . PHP_EOL : '') . ((!osc_validate_max($aItem['cityName'], 50)) ? _m("City too long.") . PHP_EOL : '') . ((!osc_validate_text($aItem['cityArea'], 3, false)) ? _m("Municipality too short.") . PHP_EOL : '') . ((!osc_validate_max($aItem['cityArea'], 50)) ? _m("Municipality too long.") . PHP_EOL : '') . ((!osc_validate_text($aItem['address'], 3, false)) ? _m("Address too short.") . PHP_EOL : '') . ((!osc_validate_max($aItem['address'], 100)) ? _m("Address too long.") . PHP_EOL : '');
		$meta = Params::getParam("meta");
		if ($meta != '' && count($meta) > 0) 
		{
			$mField = Field::newInstance();
			foreach ($meta as $k => $v) 
			{
				if ($v == '') 
				{
					$field = $mField->findByPrimaryKey($k);
					if ($field['b_required'] == 1) 
					{
						$flash_error.= sprintf(_m("%s field is required."), $field['s_name']);
					}
				}
			}
		};
		// hook pre add or edit
		osc_run_hook('pre_item_post');
		// Handle error
		if ($flash_error) 
		{
			return $flash_error;
		}
		else
		{
			$location = array('fk_c_country_code' => $aItem['countryId'], 's_country' => $aItem['countryName'], 'fk_i_region_id' => $aItem['regionId'], 's_region' => $aItem['regionName'], 'fk_i_city_id' => $aItem['cityId'], 's_city' => $aItem['cityName'], 's_city_area' => $aItem['cityArea'], 's_address' => $aItem['address']);
			$locationManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemLocation' );
			$locationManager->update($location, array('fk_i_item_id' => $aItem['idItem']));
			// Update category numbers
			$old_item = $this->manager->findByPrimaryKey($aItem['idItem']);
			if ($old_item['fk_i_category_id'] != $aItem['catId']) 
			{
				CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->increaseNumItems($aItem['catId']);
				CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->decreaseNumItems($old_item['fk_i_category_id']);
			}
			unset($old_item);
			$result = $this->manager->update(array('dt_mod_date' => date('Y-m-d H:i:s'), 'fk_i_category_id' => $aItem['catId'], 'i_price' => $aItem['price'], 'fk_c_currency_code' => $aItem['currency']), array('pk_i_id' => $aItem['idItem'], 's_secret' => $aItem['secret']));
			// UPDATE title and description locales
			$this->insertItemLocales('EDIT', $aItem['title'], $aItem['description'], $aItem['idItem']);
			// UPLOAD item resources
			$this->uploadItemResources($aItem['photos'], $aItem['idItem']);
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('item', 'edit', $aItem['idItem'], current(array_values($aItem['title'])), $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
			/**
			 * META FIELDS
			 */
			if ($meta != '' && count($meta) > 0) 
			{
				$mField = Field::newInstance();
				foreach ($meta as $k => $v) 
				{
					$mField->replace($aItem['idItem'], $k, $v);
				}
			}
			osc_run_hook('item_edit_post', $aItem['catId'], $aItem['idItem']);
			return 1;
		}
		return 0;
	}
	/**
	 * Activetes an item
	 * @param <type> $secret
	 * @param <type> $id
	 * @return boolean
	 */
	public function activate($id, $secret) 
	{
		$item = $this->manager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' ", $secret, $id);
		if ($item[0]['b_enabled'] == 1 && $item[0]['b_active'] == 0) 
		{
			$result = $this->manager->update(array('b_active' => 1), array('s_secret' => $secret, 'pk_i_id' => $id));
			if ($item[0]['fk_i_user_id'] != null) 
			{
				$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($item[0]['fk_i_user_id']);
				if ($user) 
				{
					ClassLoader::getInstance()->getClassInstance( 'Model_User' )->update(array('i_items' => $user['i_items'] + 1), array('pk_i_id' => $user['pk_i_id']));
				}
			}
			osc_run_hook('activate_item', $id);
			CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->increaseNumItems($item[0]['fk_i_category_id']);
			return $result;
		}
		else
		{
			return false;
		}
	}
	public function deactivate($id) 
	{
		$item = $this->manager->findByPrimaryKey($id);
		if ($item['b_active'] == 1) 
		{
			$result = $this->manager->update(array('b_active' => 0), array('pk_i_id' => $id));
			osc_run_hook('deactivate_item', $id);
			if ($item['b_enabled'] == 1) 
			{
				CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->decreaseNumItems($item['fk_i_category_id']);
			}
			return true;
		}
		return false;
	}
	public function enable($id) 
	{
		$item = $this->manager->findByPrimaryKey($id);
		if ($item['b_enabled'] == 0) 
		{
			$result = $this->manager->update(array('b_enabled' => 1), array('pk_i_id' => $id));
			osc_run_hook('enable_item', $id);
			if ($item['b_active'] == 1) 
			{
				CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->increaseNumItems($item['fk_i_category_id']);
			}
			return true;
		}
		return false;
	}
	public function disable($id) 
	{
		$item = $this->manager->findByPrimaryKey($id);
		if ($item['b_enabled'] == 1) 
		{
			$result = $this->manager->update(array('b_enabled' => 0), array('pk_i_id' => $id));
			osc_run_hook('disable_item', $id);
			if ($item['b_active'] == 1) 
			{
				CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->decreaseNumItems($item['fk_i_category_id']);
			}
			return true;
		}
		return false;
	}
	public function premium($id, $on = true) 
	{
		if ($on) 
		{
			$this->manager->update(array('b_premium' => '1'), array('pk_i_id' => $id));
			osc_run_hook("item_premium_on", $id);
		}
		else
		{
			$this->manager->update(array('b_premium' => '0'), array('pk_i_id' => $id));
			osc_run_hook("item_premium_off", $id);
		}
	}
	/**
	 *
	 * @param <type> $secret
	 * @param <type> $itemId
	 */
	public function delete($secret, $itemId) 
	{
		$item = $this->manager->findByPrimaryKey($itemId);
		if ($item['s_secret'] == $secret) 
		{
			$this->deleteResourcesFromHD($itemId);
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('item', 'delete', $itemId, $item['s_title'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
			return $this->manager->deleteByPrimaryKey($itemId);
		}
		return false;
	}
	/**
	 * Delete resources from the hard drive
	 * @param <type> $itemId
	 */
	public function deleteResourcesFromHD($itemId) 
	{
		$resources = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->getAllResources($itemId);
		foreach ($resources as $resource) 
		{
			osc_deleteResource($resource['pk_i_id']);
		}
	}
	/**
	 * Mark an item
	 * @param <type> $id
	 * @param <type> $as
	 */
	public function mark($id, $as) 
	{
		switch ($as) 
		{
		case 'spam':
			$column = 'i_num_spam';
			break;

		case 'badcat':
			$column = 'i_num_bad_classified';
			break;

		case 'offensive':
			$column = 'i_num_offensive';
			break;

		case 'repeated':
			$column = 'i_num_repeated';
			break;

		case 'expired':
			$column = 'i_num_expired';
			break;
		}
		ClassLoader::getInstance()->getClassInstance( 'Model_ItemStats' )->increase($column, $id);
	}
	public function add_comment() 
	{
		$classLoader = ClassLoader::getInstance();
			$item = $this->manager->findByPrimaryKey(Params::getParam('id'));
			$aItem['item'] = $item;
			ClassLoader::getInstance()->getClassInstance( 'View_Html' )->assign('item', $aItem['item']);
			$aItem['authorName'] = Params::getParam('authorName');
			$aItem['authorEmail'] = Params::getParam('authorEmail');
			$aItem['body'] = Params::getParam('body');
			$aItem['title'] = Params::getParam('title');
			$aItem['id'] = Params::getParam('id');
			$aItem['userId'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('userId');
			if ($aItem['userId'] == '') 
			{
				$aItem['userId'] = NULL;
			}

		$authorName = trim($aItem['authorName']);
		$authorName = strip_tags($authorName);
		$authorEmail = trim($aItem['authorEmail']);
		$authorEmail = strip_tags($authorEmail);
		$body = trim($aItem['body']);
		$body = strip_tags($body);
		$title = $aItem['title'];
		$itemId = $aItem['id'];
		$userId = $aItem['userId'];
		$status_num = - 1;
		$item = $this->manager->findByPrimaryKey($itemId);
		$classLoader->getClassInstance( 'View_Html' )->assign('item', $item);
		$itemURL = $classLoader->getClassInstance( 'Url_Item' )->getDetailsUrl( $item );
		$itemURL = '<a href="' . $itemURL . '" >' . $itemURL . '</a>';
		$session = $classLoader->getClassInstance( 'Session' );
		Params::setParam('itemURL', $itemURL);
		if ($authorName == '' || !preg_match('|^.*?@.{2,}\..{2,3}$|', $authorEmail)) 
		{
			$session->_setForm('commentAuthorName', $authorName);
			$session->_setForm('commentTitle', $title);
			$session->_setForm('commentBody', $body);
			return 3;
		}
		if (($body == '')) 
		{
			$session->_setForm('commentAuthorName', $authorName);
			$session->_setForm('commentAuthorEmail', $authorEmail);
			$session->_setForm('commentTitle', $title);
			return 4;
		}
		$num_moderate_comments = osc_moderate_comments();
		if ($userId == null) 
		{
			$num_comments = 0;
		}
		else
		{
			$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userId);
			$num_comments = $user['i_comments'];
		}
		if ($num_moderate_comments == - 1 || ($num_moderate_comments != 0 && $num_comments >= $num_moderate_comments)) 
		{
			$status = 'ACTIVE';
			$status_num = 2;
		}
		else
		{
			$status = 'INACTIVE';
			$status_num = 1;
		}
		if( osc_akismet_key() )
		{
			$indexUrls = $classLoader->getClassInstance( 'Url_Index' );
			require_once 'Akismet.class.php';
			$akismet = new Akismet( $indexUrls->getBaseUrl(), osc_akismet_key() );
			$akismet->setCommentAuthor($authorName);
			$akismet->setCommentAuthorEmail($authorEmail);
			$akismet->setCommentContent($body);
			$akismet->setPermalink($itemURL);
			$status = $akismet->isCommentSpam() ? 'SPAM' : $status;
			if ($status == 'SPAM') 
			{
				$status_num = 5;
			}
		}
		$mComments = ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' );
		$aComment = array('dt_pub_date' => date('Y-m-d H:i:s'), 'fk_i_item_id' => $itemId, 's_author_name' => $authorName, 's_author_email' => $authorEmail, 's_title' => $title, 's_body' => $body, 'b_active' => ($status == 'ACTIVE' ? 1 : 0), 'b_enabled' => 1, 'fk_i_user_id' => $userId);
		if ($mComments->insert($aComment)) 
		{
			$commentID = $mComments->dao->insertedId();
			if ($status_num == 2 && $userId != null) 
			{ // COMMENT IS ACTIVE
				$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userId);
				if ($user) 
				{
					ClassLoader::getInstance()->getClassInstance( 'Model_User' )->update(array('i_comments' => $user['i_comments'] + 1), array('pk_i_id' => $user['pk_i_id']));
				}
			}
			//Notify admin
			if (osc_notify_new_comment()) 
			{
				osc_run_hook('hook_email_new_comment_admin', $aItem);
			}
			//Notify user
			if (osc_notify_new_comment_user()) 
			{
				osc_run_hook('hook_email_new_comment_user', $aItem);
			}
			osc_run_hook('add_comment', $commentID);
			return $status_num;
		}
		return -1;
	}
	/**
	 * Return an array with all data necessary for do the action (ADD OR EDIT)
	 * @param <type> $is_add
	 * @return array
	 */
	public function prepareData($is_add) 
	{
		$aItem = array();
		if ($is_add) 
		{ // ADD
			$userId = null;
			if ($this->is_admin) 
			{
				if (Params::getParam('userId') != '') 
				{
					$userId = Params::getParam('userId');
				}
			}
			else
			{
				$userId = ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('userId');
				if ($userId == '') 
				{
					$userId = NULL;
				}
			}
			if ($this->is_admin) 
			{
				$active = 'ACTIVE';
			}
			else
			{
				if (osc_moderate_items() > 0) 
				{ // HAS TO VALIDATE
					if (!osc_is_web_user_logged_in()) 
					{ // NO USER IS LOGGED, VALIDATE
						$active = 'INACTIVE';
					}
					else
					{ // USER IS LOGGED
						if (osc_logged_user_item_validation()) 
						{ //USER IS LOGGED, BUT NO NEED TO VALIDATE
							$active = 'ACTIVE';
						}
						else
						{ // USER IS LOGGED, NEED TO VALIDATE, CHECK NUMBER OF PREVIOUS ITEMS
							$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey(osc_logged_user_id());
							if ($user['i_items'] < osc_moderate_items()) 
							{
								$active = 'INACTIVE';
							}
							else
							{
								$active = 'ACTIVE';
							}
						}
					}
				}
				else if (osc_moderate_items() == 0) 
				{
					if (osc_is_web_user_logged_in() && osc_logged_user_item_validation()) 
					{
						$active = 'ACTIVE';
					}
					else
					{
						$active = 'INACTIVE';
					}
				}
				else
				{
					$active = 'ACTIVE';
				}
			}
			if ($userId != null) 
			{
				$data = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userId);
				$aItem['contactName'] = $data['s_name'];
				$aItem['contactEmail'] = $data['s_email'];
				Params::setParam('contactName', $data['s_name']);
				Params::setParam('contactEmail', $data['s_email']);
			}
			else
			{
				$aItem['contactName'] = Params::getParam('contactName');
				$aItem['contactEmail'] = Params::getParam('contactEmail');
			}
			$aItem['active'] = $active;
			$aItem['userId'] = $userId;
		}
		else
		{ // EDIT
			$aItem['secret'] = Params::getParam('secret');
			$aItem['idItem'] = Params::getParam('id');
			$userId = Params::getParam('userId');
			if ($userId != null) 
			{
				$data = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userId);
				$aItem['contactName'] = $data['s_name'];
				$aItem['contactEmail'] = $data['s_email'];
				Params::setParam('contactName', $data['s_name']);
				Params::setParam('contactEmail', $data['s_email']);
			}
			else
			{
				$aItem['contactName'] = Params::getParam('contactName');
				$aItem['contactEmail'] = Params::getParam('contactEmail');
			}
		}
		// get params
		$aItem['catId'] = Params::getParam('catId');
		$aItem['countryId'] = Params::getParam('countryId');
		$aItem['country'] = Params::getParam('country');
		$aItem['region'] = Params::getParam('region');
		$aItem['regionId'] = Params::getParam('regionId');
		$aItem['city'] = Params::getParam('city');
		$aItem['cityId'] = Params::getParam('cityId');
		$aItem['price'] = (Params::getParam('price') != '') ? Params::getParam('price') : null;
		$aItem['cityArea'] = Params::getParam('cityArea');
		$aItem['address'] = Params::getParam('address');
		$aItem['currency'] = Params::getParam('currency');
		$aItem['showEmail'] = (Params::getParam('showEmail') != '') ? 1 : 0;
		$aItem['title'] = Params::getParam('title');
		$aItem['description'] = Params::getParam('description');
		$aItem['photos'] = Params::getFiles('photos');

		$classLoader = ClassLoader::getInstance();
		$country = $classLoader->getClassInstance( 'Model_Country' )->findByCode($aItem['countryId']);
		if (count($country) > 0) 
		{
			$countryId = $country['pk_c_code'];
			$countryName = $country['s_name'];
		}
		else
		{
			$countryId = null;
			$countryName = $aItem['country'];
		}
		$aItem['countryId'] = $countryId;
		$aItem['countryName'] = $countryName;
		if ($aItem['regionId'] != '') 
		{
			if (intval($aItem['regionId'])) 
			{
				$modelRegion = $classLoader->getClassInstance( 'Model_Region' );
				$region = $modelRegion->findByPrimaryKey($aItem['regionId']);
				if (count($region) > 0) 
				{
					$regionId = $region['pk_i_id'];
					$regionName = $region['s_name'];
				}
			}
		}
		else
		{
			$regionId = null;
			$regionName = $aItem['region'];
			if ($aItem['countryId'] != '') 
			{
				$modelRegion = $classLoader->getClassInstance( 'Model_Region' );
				$auxRegion = $modelRegion->findByName($aItem['region'], $aItem['countryId']);
				if ($auxRegion) 
				{
					$regionId = $auxRegion['pk_i_id'];
					$regionName = $auxRegion['s_name'];
				}
			}
		}
		$aItem['regionId'] = $regionId;
		$aItem['regionName'] = $regionName;
		if ($aItem['cityId'] != '') 
		{
			if (intval($aItem['cityId'])) 
			{
				$modelCity = $classLoader->getClassInstance( 'Model_City' );
				$city = $modelCity->findByPrimaryKey($aItem['cityId']);
				if (count($city) > 0) 
				{
					$cityId = $city['pk_i_id'];
					$cityName = $city['s_name'];
				}
			}
		}
		else
		{
			$cityId = null;
			$cityName = $aItem['city'];
			if ($aItem['countryId'] != '') 
			{
				$modelCity = $classLoader->getClassInstance( 'Model_City' );
				$auxCity = $modelCity->findByName($aItem['city'], $aItem['regionId']);
				if ($auxCity) 
				{
					$cityId = $auxCity['pk_i_id'];
					$cityName = $auxCity['s_name'];
				}
			}
		}
		$aItem['cityId'] = $cityId;
		$aItem['cityName'] = $cityName;
		if ($aItem['cityArea'] == '') 
		{
			$aItem['cityArea'] = null;
		}
		if ($aItem['address'] == '') 
		{
			$aItem['address'] = null;
		}
		if (!is_null($aItem['price'])) 
		{
			$price = str_replace(osc_locale_thousands_sep(), '', trim($aItem['price']));
			$price = str_replace(osc_locale_dec_point(), '.', $price);
			$aItem['price'] = $price * 1000000;
			//$aItem['price'] = (float) $aItem['price'];
			
		}
		if ($aItem['catId'] == '') 
		{
			$aItem['catId'] = 0;
		}
		if ($aItem['currency'] == '') 
		{
			$aItem['currency'] = null;
		}
		$this->data = $aItem;
	}
	function insertItemLocales($type, $title, $description, $itemId) 
	{
		foreach ($title as $k => $_data) 
		{
			$_title = $title[$k];
			$_description = $description[$k];
			if ($type == 'ADD') 
			{
				$this->manager->insertLocale($itemId, $k, $_title, $_description, $_title . " " . $_description);
			}
			else if ($type == 'EDIT') 
			{
				$this->manager->updateLocaleForce($itemId, $k, $_title, $_description);
			}
		}
	}
	private function checkSize($aResources) 
	{
		$success = true;
		if ($aResources != '') 
		{
			// get allowedExt
			$maxSize = osc_max_size_kb() * 1024;
			foreach ($aResources['error'] as $key => $error) 
			{
				$bool_img = false;
				if ($error == UPLOAD_ERR_OK) 
				{
					$size = $aResources['size'][$key];
					if ($size >= $maxSize) 
					{
						$success = false;
					}
				}
			}
			if (!$success) 
			{
				osc_add_flash_error_message(_m("One of the files you tried to upload exceeds the maximum size"));
			}
		}
		return $success;
	}
	private function checkAllowedExt($aResources) 
	{
		$success = true;
		ClassLoader::getInstance()->loadFile( 'helpers/mimes' );
		$mimes = osc_getMimes();
		if ($aResources != '') 
		{
			// get allowedExt
			$aMimesAllowed = array();
			$aExt = explode(',', osc_allowed_extension());
			foreach ($aExt as $ext) 
			{
				$mime = $mimes[$ext];
				if (is_array($mime)) 
				{
					foreach ($mime as $aux) 
					{
						if (!in_array($aux, $aMimesAllowed)) 
						{
							array_push($aMimesAllowed, $aux);
						}
					}
				}
				else
				{
					if (!in_array($mime, $aMimesAllowed)) 
					{
						array_push($aMimesAllowed, $mime);
					}
				}
			}
			foreach ($aResources['error'] as $key => $error) 
			{
				$bool_img = false;
				if ($error == UPLOAD_ERR_OK) 
				{
					// check mime file
					$fileMime = $aResources['type'][$key];
					if (in_array($fileMime, $aMimesAllowed)) 
					{
						$bool_img = true;
					}
					if (!$bool_img && $success) 
					{
						$success = false;
					}
				}
			}
			if (!$success) 
			{
				osc_add_flash_error_message(_m("The file you tried to upload does not have an allowed extension"));
			}
		}
		return $success;
	}
	public function uploadItemResources($aResources, $itemId) 
	{
		if ($aResources != '') 
		{
			$wat = ClassLoader::getInstance()->getClassInstance( 'Image_Watermark' );
			$itemResourceManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' );
			$numImagesItems = osc_max_images_per_item();
			$numImages = $itemResourceManager->countResources($itemId);
			foreach ($aResources['error'] as $key => $error) 
			{
				if ($numImagesItems == 0 || ($numImagesItems > 0 && $numImages < $numImagesItems)) 
				{
					if ($error == UPLOAD_ERR_OK) 
					{
						$numImages++;
						$tmpName = $aResources['tmp_name'][$key];
						$itemResourceManager->insert(array('fk_i_item_id' => $itemId));
						$resourceId = $itemResourceManager->dao->insertedId();
						// Create normal size
						$normal_path = $path = osc_content_path() . '/uploads/' . $resourceId . '.jpg';
						$size = explode('x', osc_normal_dimensions());
						$imageResizer = ClassLoader::getInstance()->getClassInstance( 'Image_Resizer' );
						$imageResizer->fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($path);
						if (osc_is_watermark_text()) 
						{
							$wat->doWatermarkText($path, osc_watermark_text_color(), osc_watermark_text(), 'image/jpeg');
						}
						elseif (osc_is_watermark_image()) 
						{
							$wat->doWatermarkImage($path, 'image/jpeg');
						}
						// Create preview
						$path = osc_content_path() . '/uploads/' . $resourceId . '_preview.jpg';
						$size = explode('x', osc_preview_dimensions());
						$imageResizer->fromFile($normal_path)->resizeTo($size[0], $size[1])->saveToFile($path);
						// Create thumbnail
						$path = osc_content_path() . '/uploads/' . $resourceId . '_thumbnail.jpg';
						$size = explode('x', osc_thumbnail_dimensions());
						$imageResizer->fromFile($normal_path)->resizeTo($size[0], $size[1])->saveToFile($path);
						if (osc_keep_original_image()) 
						{
							$path = osc_content_path() . '/uploads/' . $resourceId . '_original.jpg';
							move_uploaded_file($tmpName, $path);
						}
						$s_path = 'components/uploads/';
						$resourceType = 'image/jpeg';
						$itemResourceManager->update(array('s_path' => $s_path, 's_name' => osc_genRandomPassword(), 's_extension' => 'jpg', 's_content_type' => $resourceType), array('pk_i_id' => $resourceId, 'fk_i_item_id' => $itemId));
						osc_run_hook('uploaded_file', ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->findByPrimaryKey($resourceId));
					}
				}
			}
			unset($itemResourceManager);
		}
	}
	public function sendEmails( array $aItem )
	{
		$item = $aItem['item'];
		ClassLoader::getInstance()->getClassInstance( 'View_Html' )->assign('item', $item);
		/**
		 * Send email to non-reg user requesting item activation
		 */
		if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('userId') == '' && $aItem['active'] == 'INACTIVE') 
		{
			osc_run_hook('hook_email_item_validation_non_register_user', $item);
		}
		else if ($aItem['active'] == 'INACTIVE') 
		{ //  USER IS REGISTERED
			osc_run_hook('hook_email_item_validation', $item);
		}
		else if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_get('userId') == '') 
		{ // USER IS NOT REGISTERED
			osc_run_hook('hook_email_new_item_non_register_user', $item);
		}
		/**
		 * Send email to admin about the new item
		 */
		if (osc_notify_new_item()) 
		{
			osc_run_hook('hook_email_admin_new_item', $item);
		}
	}

	public function getData()
	{
		return $this->data;
	}
}
