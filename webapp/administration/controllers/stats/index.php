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
class CAdminStats extends AdministrationController
{
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		
	}

	function doModel() 
	{
		parent::doModel();
		$reports = array();
		if (Params::getParam('type_stat') == 'week') 
		{
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['views'] = 0;
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['spam'] = 0;
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['repeated'] = 0;
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['bad_classified'] = 0;
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['offensive'] = 0;
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['expired'] = 0;
			}
		}
		else if (Params::getParam('type_stat') == 'month') 
		{
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['views'] = 0;
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['spam'] = 0;
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['repeated'] = 0;
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['bad_classified'] = 0;
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['offensive'] = 0;
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['expired'] = 0;
			}
		}
		else
		{
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['views'] = 0;
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['spam'] = 0;
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['repeated'] = 0;
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['bad_classified'] = 0;
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['offensive'] = 0;
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['expired'] = 0;
			}
		}
		$max = array();
		$max['views'] = 0;
		$max['other'] = 0;
		foreach ($stats_reports as $report) 
		{
			$reports[$report['d_date']]['views'] = $report['views'];
			$reports[$report['d_date']]['spam'] = $report['spam'];
			$reports[$report['d_date']]['repeated'] = $report['repeated'];
			$reports[$report['d_date']]['bad_classified'] = $report['bad_classified'];
			$reports[$report['d_date']]['offensive'] = $report['offensive'];
			$reports[$report['d_date']]['expired'] = $report['expired'];
			if ($report['views'] > $max['views']) 
			{
				$max['views'] = $report['views'];
			}
			if ($report['spam'] > $max['other']) 
			{
				$max['other'] = $report['spam'];
			}
			if ($report['repeated'] > $max['other']) 
			{
				$max['other'] = $report['repeated'];
			}
			if ($report['bad_classified'] > $max['other']) 
			{
				$max['other'] = $report['bad_classified'];
			}
			if ($report['offensive'] > $max['other']) 
			{
				$max['other'] = $report['offensive'];
			}
			if ($report['expired'] > $max['other']) 
			{
				$max['other'] = $report['expired'];
			}
		}
		$this->getView()->_exportVariableToView("reports", $reports);
		$this->getView()->_exportVariableToView("max", $max);
		$this->doView("stats/reports.php");
	}
}

