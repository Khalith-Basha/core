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

require 'osc/config.php';
$currentConfig = getCurrentConfig();
$configPath = implode(DIRECTORY_SEPARATOR, array(ABS_PATH, 'config', $currentConfig, 'general.php'));
if (!file_exists($configPath)) 
{
	require_once 'osc/helpers/hErrors.php';
	$title = 'OpenSourceClassifieds &raquo; Error';
	$message = 'There doesn\'t seem to be a <code>' . $configPath . '</code> file. OpenSourceClassifieds isn\'t installed. <a href="http://forums.opensourceclassifieds.org/">Need more help?</a></p>';
	$message.= '<p><a class="button" href="' . osc_get_absolute_url() . 'installer/index.php">Install</a></p>';
	osc_die($title, $message);
}
require $configPath;

require 'osc/ClassLoader.php';

$classLoader = ClassLoader::getInstance();
$classLoader->addSearchPath( OVERRIDE_LIBRARY_PATH . '/osc', 'Override_' );
$classLoader->addSearchPath( LIBRARY_PATH . '/osc' );

require_once 'osc/helpers/hPreference.php';
require_once 'osc/classes/database/DAO.php';
require_once 'osc/classes/database/DBConnectionClass.php';
require_once 'osc/classes/database/DBCommandClass.php';
require_once 'osc/classes/database/DBRecordsetClass.php';
require_once 'osc/model/Preference.php';
require_once 'osc/model/Locale.php';
require_once 'osc/core/Params.php';
require_once 'osc/helpers/hLocale.php';
require_once 'osc/core/Translation.php';
require_once 'osc/helpers/hTranslations.php';
require_once 'osc/helpers/hDefines.php';
require_once 'osc/core/controller.php';
require_once 'osc/helpers/hPlugins.php';
require_once 'osc/helpers/hItems.php';
require_once 'osc/helpers/hUtils.php';
require_once 'osc/helpers/hSearch.php';
require_once 'osc/helpers/hPage.php';
require_once 'osc/helpers/hUsers.php';
require_once 'osc/helpers/hMessages.php';
require_once 'osc/utils.php';

$classLoader->loadFile( 'plugins' );
$classLoader->loadFile( 'core/View' );
$classLoader->loadFile( 'helpers/hCategories' );
$classLoader->loadFile( 'View' );
$classLoader->loadFile( 'Cookie' );
$classLoader->loadFile( 'WebThemes' );
$classLoader->loadFile( 'model/Category' );
$classLoader->loadFile( 'model/Item' );
$classLoader->loadFile( 'model/Search' );
$classLoader->loadFile( 'model/Page' );
$classLoader->loadFile( 'frm/Form' );
$classLoader->loadFile( 'frm/Category' );
$classLoader->loadFile( 'frm/User' );
$classLoader->loadFile( 'urls/item' );
$classLoader->loadFile( 'formatting' );
$classLoader->loadFile( 'model/Widget' );

$pluginManager = $classLoader->getClassInstance( 'PluginManager' );
$pluginManager->init();

$rewrite = $classLoader->getClassInstance( 'Rewrite' );
require 'osc/url_rules.php';
$rewrite->init();

$session = $classLoader->getClassInstance( 'Session' );
$session->start();

