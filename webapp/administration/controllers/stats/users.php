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
class CAdminStats extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$users = array();
		if (Params::getParam('type_stat') == 'week') 
		{
			$stats_users = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
			for ($k = 10; $k >= 0; $k--) 
			{
				$users[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
			}
		}
		else if (Params::getParam('type_stat') == 'month') 
		{
			$stats_users = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
			for ($k = 10; $k >= 0; $k--) 
			{
				$users[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
			}
		}
		else
		{
			$stats_users = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_users_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
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
		$item = ClassLoader::getInstance()->getClassInstance( 'Stats' )->items_by_user();
		$this->getView()->assign("users_by_country", ClassLoader::getInstance()->getClassInstance( 'Stats' )->users_by_country());
		$this->getView()->assign("users_by_region", ClassLoader::getInstance()->getClassInstance( 'Stats' )->users_by_region());
		$this->getView()->assign("item", (!isset($item[0]['avg']) || !is_numeric($item[0]['avg'])) ? 0 : $item[0]['avg']);
		$this->getView()->assign("latest_users", ClassLoader::getInstance()->getClassInstance( 'Stats' )->latest_users());
		$this->getView()->assign("users", $users);
		$this->getView()->assign("max", $max);
		$this->doView("stats/users.php");
	}
}

