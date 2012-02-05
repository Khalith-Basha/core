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

class CWebAjax extends Controller_Default
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		//specific things for this class
		switch ($this->action) 
		{
		case 'bulk_actions':
			break;

		case 'regions': //Return regions given a countryId
			$regions = Region::newInstance()->findByCountry(Params::getParam("countryId"));
			echo json_encode($regions);
			break;

		case 'cities': //Returns cities given a regionId
			$cities = City::newInstance()->findByRegion(Params::getParam("regionId"));
			echo json_encode($cities);
			break;

		case 'location': // This is the autocomplete AJAX
			$cities = City::newInstance()->ajax(Params::getParam("term"));
			echo json_encode($cities);
			break;

		case 'location_countries': // This is the autocomplete AJAX
			$countries = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->ajax(Params::getParam("term"));
			echo json_encode($countries);
			break;

		case 'location_regions': // This is the autocomplete AJAX
			$regions = Region::newInstance()->ajax(Params::getParam("term"), Params::getParam("country"));
			echo json_encode($regions);
			break;

		case 'location_cities': // This is the autocomplete AJAX
			$cities = City::newInstance()->ajax(Params::getParam("term"), Params::getParam("region"));
			echo json_encode($cities);
			break;

		case 'delete_image': // Delete images via AJAX
			$id = Params::getParam('id');
			$item = Params::getParam('item');
			$code = Params::getParam('code');
			$secret = Params::getParam('secret');
			$json = array();
			if ($this->getSession()->_get('userId') != '') 
			{
				$userId = $this->getSession()->_get('userId');
				$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userId);
			}
			else
			{
				$userId = null;
				$user = null;
			}
			// Check for required fields
			if (!(is_numeric($id) && is_numeric($item) && preg_match('/^([a-z0-9]+)$/i', $code))) 
			{
				$json['success'] = false;
				$json['msg'] = _m("The selected photo couldn't be deleted, the url doesn't exist");
				echo json_encode($json);
				return false;
			}
			$aItem = ClassLoader::getInstance()->getClassInstance( 'Model_Item' )->findByPrimaryKey($item);
			// Check if the item exists
			if (count($aItem) == 0) 
			{
				$json['success'] = false;
				$json['msg'] = _m('The item doesn\'t exist');
				echo json_encode($json);
				return false;
			}
			if (!osc_is_admin_user_logged_in()) 
			{
				// Check if the item belong to the user
				if ($userId != null && $userId != $aItem['fk_i_user_id']) 
				{
					$json['success'] = false;
					$json['msg'] = _m('The item doesn\'t belong to you');
					echo json_encode($json);
					return false;
				}
				// Check if the secret passphrase match with the item
				if ($userId == null && $aItem['fk_i_user_id'] == null && $secret != $aItem['s_secret']) 
				{
					$json['success'] = false;
					$json['msg'] = _m('The item doesn\'t belong to you');
					echo json_encode($json);
					return false;
				}
			}
			// Does id & code combination exist?
			$result = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->existResource($id, $code);
			if ($result > 0) 
			{
				// Delete: file, db table entry
				osc_deleteResource($id);
				ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $item, 's_name' => $code));
				$json['msg'] = _m('The selected photo has been successfully deleted');
				$json['success'] = 'true';
			}
			else
			{
				$json['msg'] = _m("The selected photo couldn't be deleted");
				$json['success'] = 'false';
			}
			echo json_encode($json);
			return true;
			break;

		case 'alerts': // Allow to register to an alert given (not sure it's used on admin)
			$alert = Params::getParam("alert");
			$email = Params::getParam("email");
			$userid = Params::getParam("userid");
			if ($alert != '' && $email != '') 
			{
				if (preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) 
				{
					$secret = osc_genRandomPassword();
					if (Alerts::newInstance()->createAlert($userid, $email, $alert, $secret)) 
					{
						if ((int)$userid > 0) 
						{
							$user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($userid);
							if ($user['b_active'] == 1 && $user['b_enabled'] == 1) 
							{
								Alerts::newInstance()->activate($email, $secret);
								echo '1';
								return true;
							}
							else
							{
								echo '-1';
								return false;
							}
						}
						else
						{
							osc_run_hook('hook_email_alert_validation', $alert, $email, $secret);
						}
						echo "1";
					}
					else
					{
						echo 'Alert creation failed';
					}
					return true;
				}
				else
				{
					echo '-1';
					return false;
				}
			}
			echo 'Missing parameters: alert, email';
			return false;
			break;

		case 'custom': // Execute via AJAX custom file
			$ajaxfile = Params::getParam("ajaxfile");
			if ($ajaxfile != '') 
			{
				require_once osc_plugins_path() . $ajaxfile;
			}
			else
			{
				echo json_encode(array('error' => __('no action defined')));
			}
			break;

		default:
			echo json_encode(array('error' => __('no action defined')));
			break;
		}
		// clear all keep variables into session
		$this->getSession()->_dropKeepForm();
		$this->getSession()->_clearVariables();
	}
}
