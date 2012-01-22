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
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		
	}

	function doModel() 
	{
		parent::doModel();
		$comments = array();
		if (Params::getParam('type_stat') == 'week') 
		{
			$stats_comments = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
			for ($k = 10; $k >= 0; $k--) 
			{
				$comments[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
			}
		}
		else if (Params::getParam('type_stat') == 'month') 
		{
			$stats_comments = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
			for ($k = 10; $k >= 0; $k--) 
			{
				$comments[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
			}
		}
		else
		{
			$stats_comments = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
			for ($k = 10; $k >= 0; $k--) 
			{
				$comments[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ] = 0;
			}
		}
		$max = 0;
		foreach ($stats_comments as $comment) 
		{
			$comments[$comment['d_date']] = $comment['num'];
			if ($comment['num'] > $max) 
			{
				$max = $comment['num'];
			}
		}
		$this->getView()->_exportVariableToView("comments", $comments);
		$this->getView()->_exportVariableToView("latest_comments", ClassLoader::getInstance()->getClassInstance( 'Stats' )->latest_comments());
		$this->getView()->_exportVariableToView("max", $max);
		$this->doView("stats/comments.php");
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}
