<?php

define( 'APP_NAME', 'OpenSourceClassifieds' );
define( 'APP_VERSION', '1.0' );

define( 'ABS_PATH', dirname( __FILE__ ) );
define( 'CONTENT_PATH', ABS_PATH . '/components' );
define( 'THEMES_PATH', CONTENT_PATH . '/themes' );
define( 'PLUGINS_PATH', CONTENT_PATH . '/plugins' );
define( 'TRANSLATIONS_PATH', CONTENT_PATH . '/languages' );
define( 'OVERRIDE_LIBRARY_PATH', ABS_PATH . '/override/library' );
define( 'LIBRARY_PATH', ABS_PATH . '/library' );

set_include_path( get_include_path() . PATH_SEPARATOR . LIBRARY_PATH );

require 'osc/ClassLoader.php';

$classLoader = ClassLoader::getInstance();
$classLoader->addSearchPath( OVERRIDE_LIBRARY_PATH . '/osc', 'Override_' );
$classLoader->addSearchPath( LIBRARY_PATH . '/osc' );

$config = $classLoader->getClassInstance( 'Config' );
if( false === $config->hasConfig( 'database' ) )
{
	$configPath = $config->getConfigPath( 'database' );
	require_once 'osc/helpers/hErrors.php';
	$title = 'OpenSourceClassifieds &raquo; Error';
	$message = 'There doesn\'t seem to be a <code>' . $configPath . '</code> file. OpenSourceClassifieds isn\'t installed. <a href="http://forums.opensourceclassifieds.org/">Need more help?</a></p>';
	$message.= '<p><a class="button" href="' . osc_get_absolute_url() . '/installer/index.php">Install</a></p>';
	osc_die($title, $message);
}

$classLoader->loadFile( 'plugins' );
$classLoader->loadFile( 'formatting' );
$classLoader->loadFile( 'TypedArray' );
$classLoader->loadFile( 'Url/Abstract' );
$classLoader->loadFile( 'Form/Form' );
$classLoader->loadFile( 'core/Controller' );
$classLoader->loadFile( 'core/SecureController' );
$classLoader->loadFile( 'core/UserController' );
$classLoader->loadFile( 'core/Params' );
$classLoader->loadFile( 'Database/DAO' );
$classLoader->loadFile( 'utils' );
$classLoader->loadFile( 'Model/Preference' );
$classLoader->loadFile( 'Model/Locale' );
$classLoader->loadFile( 'helpers/hCategories' );
$classLoader->loadFile( 'helpers/hPreference' );
$classLoader->loadFile( 'helpers/hLocale' );
$classLoader->loadFile( 'helpers/hTranslations' );
$classLoader->loadFile( 'helpers/hDefines' );
$classLoader->loadFile( 'helpers/hPlugins' );
$classLoader->loadFile( 'helpers/hItems' );
$classLoader->loadFile( 'helpers/hUtils' );
$classLoader->loadFile( 'helpers/hSearch' );
$classLoader->loadFile( 'helpers/hPage' );
$classLoader->loadFile( 'helpers/hUsers' );
$classLoader->loadFile( 'helpers/hMessages' );

$generalConfig = $config->getConfig( 'general' );
define( 'WEB_PATH', $generalConfig['webUrl'] );

$dbConfig = $config->getConfig( 'database' );
define( 'DB_TABLE_PREFIX', $dbConfig['tablePrefix'] );

$dbConnection = $classLoader->getClassInstance(
	'Database_Connection',
	true,
	array( $dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['name'], $dbConfig['tablePrefix'] )
);

$pluginManager = $classLoader->getClassInstance( 'PluginManager' );
$pluginManager->init();

