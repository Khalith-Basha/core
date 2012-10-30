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

require 'osc/constants.php';
require 'osc/ClassLoader.php';

$classLoader = ClassLoader::getInstance();
$classLoader->addSearchPath( LIBRARY_PATH . '/osc' );
$classLoader->addSearchPath( LIBRARY_PATH . '/cuore_framework' );

$classLoader->loadFile( 'helpers/urls' );

$classLoader->loadFile( 'Logging/Logger' );
$classLoader->loadFile( 'Database/Command' );
$classLoader->loadFile( 'Database/Collection' );
$classLoader->loadFile( 'Database/DAO' );
$classLoader->loadFile( 'Session' );
$classLoader->loadFile( 'Params' );
$classLoader->loadFile( 'Model/Preference' );
$classLoader->loadFile( 'helpers/locales' );
$classLoader->loadFile( 'helpers/preference' );
$classLoader->loadFile( 'helpers/search' );
$classLoader->loadFile( 'helpers/paths' );

require_once 'library/steps.php';

// List of steps on the installer.
$steps = array(
	1 => 'Welcome and requirements',
	2 => 'Database',
	3 => 'Target',
	4 => 'Categories',
	5 => 'Congratulations'
);
$numSteps = count( $steps );

$step = intval( Params::getParam('step') );
if( $step < 1 || $step > $numSteps ) 
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
		if (Params::getParam('ping_engines') == '1' || isset($_COOKIE['osc_ping_engines'])) 
		{
			setcookie('osc_ping_engines', 1, time() + (24 * 60 * 60));
		}
		else
		{
			setcookie('osc_ping_engines', 0, time() + (24 * 60 * 60));
		}
		showView( 'views/db_config.php' );
		break;

	case 3:
		$error = false;
		if( Params::getParam('dbname') != '')
		{
			try
			{
				$error = oc_install();
			}
			catch( Exception $e )
			{
				var_dump($e);
				$error = $e->getMessage();
			}
		}
		if( false === $error )
		{
			showView( 'views/target.php' );
		}
		else
		{
			showView( 'views/db_error.php' );
		}

		break;

	case 4:
		list( $username, $password ) = basic_info();
		$categories = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->toTreeAll();
		$numCols = 3;
		$catsPerCol = ceil(count($categories) / $numCols);
		showView( 'views/categories.php' );
		break;

	case 5:
		$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
		$dbConfig = $config->getConfig( 'database' );
		
		define( 'DB_HOST', $dbConfig['host'] );
		define( 'DB_USER', $dbConfig['user'] );
		define( 'DB_PASS', $dbConfig['password'] );
		define( 'DB_NAME', $dbConfig['name'] );

		$password = Params::getParam('password');
		ping_search_engines($_COOKIE['osc_ping_engines']);
		setcookie('osc_ping_engines', '', time() - 3600);
		$data = savePreferences( $password );
		showView( 'views/finish.php', array( 'data' => $data ) );
		break;
}

