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
define('ABS_PATH', dirname(dirname(__FILE__)));
define('CONTENT_PATH', ABS_PATH . '/components');
define('TRANSLATIONS_PATH', CONTENT_PATH . '/languages');
define('OSC_INSTALLING', 1);
set_include_path(get_include_path() . PATH_SEPARATOR . ABS_PATH . '/library');
require_once 'osc/Logger/Logger.php';
require_once 'osc/Logger/LogDatabase.php';
require_once 'osc/Logger/LogOsclass.php';
require_once 'osc/classes/database/DBConnectionClass.php';
require_once 'osc/classes/database/DBCommandClass.php';
require_once 'osc/classes/database/DBRecordsetClass.php';
require_once 'osc/classes/database/DAO.php';
require_once 'osc/core/Session.php';
require_once 'osc/core/Params.php';
require_once 'osc/model/Preference.php';
require_once 'osc/helpers/hDatabaseInfo.php';
require_once 'osc/helpers/hDefines.php';
require_once 'osc/helpers/hErrors.php';
require_once 'osc/helpers/hLocale.php';
require_once 'osc/helpers/hPreference.php';
require_once 'osc/helpers/hSearch.php';
require_once 'osc/default-constants.php';
require_once 'functions.php';
require_once 'osc/utils.php';
$step = Params::getParam('step');
if (!is_numeric($step)) 
{
	$step = '1';
}
if (is_osclass_installed()) 
{
	$message = 'You appear to have already installed OpenSourceClassifieds. To reinstall please clear your old database tables first.';
	osc_die('OpenSourceClassifieds &raquo; Error', $message);
}
switch ($step) 
{
case 1:
	$requirements = get_requirements();
	$error = check_requirements($requirements);
	break;

case 2:
	if (Params::getParam('save_stats') == '1' || isset($_COOKIE['osclass_save_stats'])) 
	{
		setcookie('osclass_save_stats', 1, time() + (24 * 60 * 60));
	}
	else
	{
		setcookie('osclass_save_stats', 0, time() + (24 * 60 * 60));
	}
	if (Params::getParam('ping_engines') == '1' || isset($_COOKIE['osclass_ping_engines'])) 
	{
		setcookie('osclass_ping_engines', 1, time() + (24 * 60 * 60));
	}
	else
	{
		setcookie('osclass_ping_engines', 0, time() + (24 * 60 * 60));
	}
	break;

case 3:
	if (Params::getParam('dbname') != '') 
	{
		$error = oc_install();
	}
	break;

case 4:
	list($username, $password) = basic_info();
	break;

case 5:
	$password = Params::getParam('password');
	break;

default:
	break;
}
require 'views/header.php';
if ($step == 1) 
{
	require 'views/welcome.php';
}
elseif ($step == 2) 
{
	display_database_config();
}
elseif ($step == 3) 
{
	if (!isset($error["error"])) 
	{
		display_target();
	}
	else
	{
		display_database_error($error, ($step - 1));
	}
}
elseif ($step == 4) 
{
	display_categories($error, $password);
}
elseif ($step == 5) 
{
	// ping engines
	ping_search_engines($_COOKIE['osclass_ping_engines']);
	setcookie('osclass_save_stats', '', time() - 3600);
	setcookie('osclass_ping_engines', '', time() - 3600);
	display_finish($password);
}
require 'views/footer.php';
