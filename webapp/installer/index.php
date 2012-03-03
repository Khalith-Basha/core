<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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
define( 'LIBRARY_PATH', ABS_PATH . '/library' );
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH );

require 'osc/ClassLoader.php';

$classLoader = ClassLoader::getInstance();
$classLoader->addSearchPath( LIBRARY_PATH . '/osc' );

$classLoader->loadFile( 'helpers/urls' );

require_once 'osc/Logging/Logger.php';
require_once 'osc/Database/Connection.php';
require_once 'osc/Database/Command.php';
require_once 'osc/Database/Collection.php';
require_once 'osc/Database/DAO.php';
require_once 'osc/Session.php';
require_once 'osc/Params.php';
require_once 'osc/Model/Preference.php';
require_once 'osc/helpers/locales.php';
require_once 'osc/helpers/preference.php';
require_once 'osc/helpers/search.php';
require_once 'osc/helpers/paths.php';
require_once 'library/steps.php';
$step = Params::getParam('step');
if (!is_numeric($step)) 
{
	$step = 1;
}
if (is_osc_installed()&&false) 
{
	$message = 'You appear to have already installed OpenSourceClassifieds. To reinstall please clear your old database tables first.';
	osc_die('OpenSourceClassifieds &raquo; Error', $message);
}

if( $step >= 4 )
{
	$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
	$databaseConfig = $config->getConfig( 'database' );
	if( !defined( 'DB_TABLE_PREFIX' ) )
		define( 'DB_TABLE_PREFIX', $databaseConfig['tablePrefix'] );
	$conn = ClassLoader::getInstance()
		->getClassInstance(
			'Database_Connection',
			true,
			array( $databaseConfig['host'], $databaseConfig['user'], $databaseConfig['password'], $databaseConfig['name'], $databaseConfig['tablePrefix'] )
		);
}

function showView( $file, array $extraParams = array() )
{
	extract( $GLOBALS );
	extract( $extraParams );
	require 'views/header.php';
	require $file;
	require 'views/footer.php';
}

switch ($step) 
{
	case 1:
		require 'library/requirements.php';
		$requirements = array(
			'mandatory' => getMandatoryRequirements(),
			'optional' => getOptionalRequirements()
		);
		$error = checkRequirements( $requirements['mandatory'] );
		showView( 'views/welcome.php' );
		break;

	case 2:
		if (Params::getParam('ping_engines') == '1' || isset($_COOKIE['osclass_ping_engines'])) 
		{
			setcookie('osclass_ping_engines', 1, time() + (24 * 60 * 60));
		}
		else
		{
			setcookie('osclass_ping_engines', 0, time() + (24 * 60 * 60));
		}
		showView( 'views/db_config.php' );
		break;

	case 3:
		if (Params::getParam('dbname') != '') 
		{
			$error = oc_install();
		}
		if (!isset($error["error"])) 
		{
			showView( 'views/target.php' );
		}
		else
		{
			showView( 'views/db_error.php' );
		}

		break;

	case 4:
		list($username, $password) = basic_info();
		$categories = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->toTreeAll();
		$numCols = 3;
		$catsPerCol = ceil(count($categories) / $numCols);
		showView( 'views/categories.php' );
		break;

	case 5:
		$password = Params::getParam('password');
		ping_search_engines($_COOKIE['osclass_ping_engines']);
		setcookie('osclass_ping_engines', '', time() - 3600);
		$data = savePreferences( $password );
		showView( 'views/finish.php', array( 'data' => $data ) );
		break;
}

