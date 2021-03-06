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

class Manager_User
{
	private $is_admin;
	private $manager;

	public function __construct($is_admin) 
	{
		$this->is_admin = $is_admin;
		$this->manager = new \Osc\Model\User;
	}

	public function add() 
	{
		if ((osc_recaptcha_private_key() != '') && !$this->is_admin) 
		{
			if (!$this->recaptcha()) 
			{
				return 4;
			}
		}
		if (Params::getParam('s_password') == '') 
		{
			return 6;
		}
		if (Params::getParam('s_password') != Params::getParam('s_password2')) 
		{
			return 7;
		}
		$input = $this->prepareData(true);

		ClassLoader::getInstance()->loadFile( 'helpers/sanitize' );
		if (!osc_validate_email($input['s_email'])) 
		{
			return 5;
		}
		$email_taken = $this->manager->findByEmail($input['s_email']);
		if ($email_taken != null) 
		{
			return 3;
		}

		$this->assignDefaultRole( $input );

		$this->manager->insert($input);
		$userId = $this->manager->dbCommand->insertedId();
		if (is_array(Params::getParam('s_info'))) 
		{
			foreach (Params::getParam('s_info') as $key => $value) 
			{
				$this->manager->updateDescription($userId, $key, $value);
			}
		}
		ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'add', $userId, $input['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : $userId);
		osc_run_hook('user_register_completed', $userId);
		$user = $this->manager->findByPrimaryKey($userId);
		if (osc_notify_new_user() && !$this->is_admin) 
		{
			osc_run_hook('hook_email_admin_new_user', $user);
		}
		if (osc_user_validation_enabled() && !$this->is_admin) 
		{
			osc_run_hook('hook_email_user_validation', $user, $input);
			return 1;
		}
		$userModel = new \Osc\Model\User;
		$userModel->update(array('b_active' => '1'), array('pk_i_id' => $userId));
		return 2;
	}
	
	public function edit($userId) 
	{
		$input = $this->prepareData( false );
		$this->manager->update( $input, array('pk_i_id' => $userId) );
		if ($this->is_admin) 
		{
			ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->update(array('s_contact_name' => $input['s_name'], 's_contact_email' => $input['s_email']), array('fk_i_user_id' => $userId));
			ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->update(array('s_author_name' => $input['s_name'], 's_author_email' => $input['s_email']), array('fk_i_user_id' => $userId));
			ClassLoader::getInstance()->getClassInstance( 'Model_Alerts' )->update(array('s_email' => $input['s_email']), array('fk_i_user_id' => $userId));
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'edit', $userId, $input['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		}
		else
		{
			ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->update(array('s_contact_name' => $input['s_name']), array('fk_i_user_id' => $userId));
			ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->update(array('s_author_name' => $input['s_name']), array('fk_i_user_id' => $userId));
			$user = $this->manager->findByPrimaryKey($userId);
			ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'edit', $userId, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		}
		ClassLoader::getInstance()->getClassInstance( 'Session' )->_set('userName', $input['s_name']);
		$phone = ($input['s_phone_mobile']) ? $input['s_phone_mobile'] : $input['s_phone_land'];
		ClassLoader::getInstance()->getClassInstance( 'Session' )->_set('userPhone', $phone);
		if (is_array(Params::getParam('s_info'))) 
		{
			foreach (Params::getParam('s_info') as $key => $value) 
			{
				$this->manager->updateDescription($userId, $key, $value);
			}
		}
		if ($this->is_admin) 
		{
			$iUpdated = 0;
			if ((Params::getParam("b_enabled") != '') && (Params::getParam("b_enabled") == 1)) 
			{
				$iUpdated+= $this->manager->update(array('b_enabled' => 1), array('pk_i_id' => $userId));
			}
			else
			{
				$iUpdated+= $this->manager->update(array('b_enabled' => 0), array('pk_i_id' => $userId));
			}
			if ((Params::getParam("b_active") != '') && (Params::getParam("b_active") == 1)) 
			{
				$iUpdated+= $this->manager->update(array('b_active' => 1), array('pk_i_id' => $userId));
			}
			else
			{
				$iUpdated+= $this->manager->update(array('b_active' => 0), array('pk_i_id' => $userId));
			}
			if ($iUpdated > 0) 
			{
				return 2;
			}
		}
		return 0;
	}
	function recover_password() 
	{
		$userModel = new \Osc\Model\User;
		$user = $userModel->findByEmail(Params::getParam('s_email'));
		ClassLoader::getInstance()->getClassInstance( 'Session' )->_set('recover_time', time());
		if ((osc_recaptcha_private_key() != '') && Params::existParam("recaptcha_challenge_field")) 
		{
			if (!$this->recaptcha()) 
			{
				return 2; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
				
			}
		}
		if (!$user || ($user['b_enabled'] == 0)) 
		{
			return 1;
		}
		$code = osc_genRandomPassword(30);
		$date = date('Y-m-d H:i:s');
		$userModel->update(array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR']), array('pk_i_id' => $user['pk_i_id']));
		$password_url = osc_forgot_user_password_confirm_url($user['pk_i_id'], $code);
		osc_run_hook('hook_email_user_forgot_password', $user, $password_url);
		return 0;
	}
	public function recaptcha() 
	{
		require_once 'recaptchalib.php';
		if (Params::getParam("recaptcha_challenge_field") != '') 
		{
			$resp = recaptcha_check_answer(osc_recaptcha_private_key(), $_SERVER["REMOTE_ADDR"], Params::getParam("recaptcha_challenge_field"), Params::getParam("recaptcha_response_field"));
			return $resp->is_valid;
		}
		return false;
	}

	public function prepareData($is_add) 
	{
		$classLoader = ClassLoader::getInstance();
		$classLoader->loadFile( 'helpers/security' );
		$input = array();
		if ($is_add) 
		{
			$input['s_secret'] = osc_genRandomPassword();
		}
		else
		{
			$input['mod_date'] = date('Y-m-d H:i:s');
		}
		//only for administration, in the public website this two params are edited separately
		if ($this->is_admin || $is_add) 
		{
			$input['s_email'] = Params::getParam('s_email');
			if (Params::getParam('s_password') != Params::getParam('s_password2')) 
			{
				return 1;
			}
			//if we want to change the password
			if (Params::getParam('s_password') != '') 
			{
				$input['s_password'] = sha1(Params::getParam('s_password'));
			}
		}
		$input['s_name'] = Params::getParam('s_name');
		$input['s_website'] = Params::getParam('s_website');
		$input['s_phone_land'] = Params::getParam('s_phone_land');
		$input['s_phone_mobile'] = Params::getParam('s_phone_mobile');
		//locations...
		$country = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->findByCode(Params::getParam('countryId'));
		if (count($country) > 0) 
		{
			$countryId = $country['pk_c_code'];
			$countryName = $country['s_name'];
		}
		else
		{
			$countryId = null;
			$countryName = null;
		}
		if (intval(Params::getParam('regionId'))) 
		{
			$region = $classLoader->getClassInstance( 'Model_Region' )
				->findByPrimaryKey(Params::getParam('regionId'));
			if (count($region) > 0) 
			{
				$regionId = $region['pk_i_id'];
				$regionName = $region['s_name'];
			}
		}
		else
		{
			$regionId = null;
			$regionName = Params::getParam('region');
		}
		if (intval(Params::getParam('cityId'))) 
		{
			$city = $classLoader->getClassInstance( 'Model_City' )
				->findByPrimaryKey(Params::getParam('cityId'));
			if (count($city) > 0) 
			{
				$cityId = $city['pk_i_id'];
				$cityName = $city['s_name'];
			}
		}
		else
		{
			$cityId = null;
			$cityName = Params::getParam('city');
		}
		$input['fk_c_country_code'] = $countryId;
		$input['s_country'] = $countryName;
		$input['fk_i_region_id'] = $regionId;
		$input['s_region'] = $regionName;
		$input['fk_i_city_id'] = $cityId;
		$input['s_city'] = $cityName;
		$input['s_city_area'] = Params::getParam('cityArea');
		$input['s_address'] = Params::getParam('address');
		$input['b_company'] = (Params::getParam('b_company') != '' && Params::getParam('b_company') != 0) ? 1 : 0;
		return ($input);
	}
	public function activate($user_id) 
	{
		$user = $this->manager->findByPrimaryKey($user_id);
		if (!$user) 
		{
			return false;
		}
		$this->manager->update(array('b_active' => 1), array('pk_i_id' => $user_id));
		if (!$this->is_admin) 
		{
			osc_run_hook('hook_email_admin_new_user', $user);
		}
		ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'activate', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		if ($user['b_enabled'] == 1) 
		{
			$mItem = ClassLoader::getInstance()->getClassInstance( 'Manager_Item', false, array( true ) );
			$items = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByUserID($user_id);
			foreach ($items as $item) 
			{
				$mItem->enable($item['pk_i_id']);
			}
		}
		return true;
	}
	public function deactivate($user_id) 
	{
		$user = $this->manager->findByPrimaryKey($user_id);
		if (!$user) 
		{
			return false;
		}
		$this->manager->update(array('b_active' => 0), array('pk_i_id' => $user_id));
		ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'deactivate', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		if ($user['b_enabled'] == 1) 
		{
			$mItem = ClassLoader::getInstance()->getClassInstance( 'Manager_Item', false, array( true ) );
			$items = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByUserID($user_id);
			foreach ($items as $item) 
			{
				$mItem->disable($item['pk_i_id']);
			}
		}
		return true;
	}
	public function enable($user_id) 
	{
		$user = $this->manager->findByPrimaryKey($user_id);
		if (!$user) 
		{
			return false;
		}
		$this->manager->update(array('b_enabled' => 1), array('pk_i_id' => $user_id));
		ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'enable', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		if ($user['b_active'] == 1) 
		{
			$mItem = ClassLoader::getInstance()->getClassInstance( 'Manager_Item', false, array( true ) );
			$items = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByUserID($user_id);
			foreach ($items as $item) 
			{
				$mItem->enable($item['pk_i_id']);
			}
		}
		return true;
	}
	public function disable($user_id) 
	{
		$user = $this->manager->findByPrimaryKey($user_id);
		if (!$user) 
		{
			return false;
		}
		$this->manager->update(array('b_enabled' => 0), array('pk_i_id' => $user_id));
		ClassLoader::getInstance()->getClassInstance( 'Logging_Logger' )->insertLog('user', 'disable', $user_id, $user['s_email'], $this->is_admin ? 'admin' : 'user', $this->is_admin ? osc_logged_admin_id() : osc_logged_user_id());
		if ($user['b_active'] == 1) 
		{
			$mItem = ClassLoader::getInstance()->getClassInstance( 'Manager_Item', false, array( true ) );
			$items = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByUserID($user_id);
			foreach ($items as $item) 
			{
				$mItem->disable($item['pk_i_id']);
			}
		}
		return true;
	}
	public function bootstrap_login($user_id) 
	{
		$classLoader = ClassLoader::getInstance();
		$userModel = new \Osc\Model\User;
		$user = $userModel->findByPrimaryKey($user_id);
		if (!$user) 
		{
			return 0;
		}
		if (!$user['b_active']) 
		{
			return 1;
		}
		if (!$user['b_enabled']) 
		{
			return 2;
		}

		$session = $classLoader->getClassInstance( 'Session' );
		$phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];

		$session->_set('userId', $user['pk_i_id']);
		$session->_set('userName', $user['s_name']);
		$session->_set('userEmail', $user['s_email']);
		$session->_set('userPhone', $phone);
		return 3;
	}

	/**
	 * @TODO Remove the hardcoded constant in this method.
	 */
	protected function assignDefaultRole( array &$user )
	{
		$user['role_id'] = 2;
	}
}
