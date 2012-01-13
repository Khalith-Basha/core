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
class CAdminStats extends AdminSecBaseModel
{
	//specific for this class
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		
	}
	//Business Layer...
	function doModel() 
	{
		parent::doModel();
			$users = array();
			if (Params::getParam('type_stat') == 'week') 
			{
				$stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
				for ($k = 10; $k >= 0; $k--) 
				{
					$users[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
				}
			}
			else if (Params::getParam('type_stat') == 'month') 
			{
				$stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
				for ($k = 10; $k >= 0; $k--) 
				{
					$users[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
				}
			}
			else
			{
				$stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
				for ($k = 10; $k >= 0; $k--) 
				{
					$users[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ] = 0;
				}
			}
			$max = 0;
			foreach ($stats_users as $user) 
			{
				$users[$user['d_date']] = $user['num'];
				if ($user['num'] > $max) 
				{
					$max = $user['num'];
				}
			}
			$item = Stats::newInstance()->items_by_user();
			$this->_exportVariableToView("users_by_country", Stats::newInstance()->users_by_country());
			$this->_exportVariableToView("users_by_region", Stats::newInstance()->users_by_region());
			$this->_exportVariableToView("item", (!isset($item[0]['avg']) || !is_numeric($item[0]['avg'])) ? 0 : $item[0]['avg']);
			$this->_exportVariableToView("latest_users", Stats::newInstance()->latest_users());
			$this->_exportVariableToView("users", $users);
			$this->_exportVariableToView("max", $max);
			$this->doView("stats/users.php");
	}
	//hopefully generic...
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}
