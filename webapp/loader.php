<?php

define( 'ABS_PATH', dirname( __FILE__ ) );
define( 'CONTENT_PATH', ABS_PATH . '/components' );
define( 'THEMES_PATH', CONTENT_PATH . '/themes' );
define( 'PLUGINS_PATH', CONTENT_PATH . '/plugins' );
define( 'TRANSLATIONS_PATH', CONTENT_PATH . '/languages' );
define( 'OVERRIDE_LIBRARY_PATH', ABS_PATH . '/override/library' );
define( 'LIBRARY_PATH', ABS_PATH . '/library' );
define( 'LIB_PATH', LIBRARY_PATH );

set_include_path(
	get_include_path() .
	PATH_SEPARATOR . LIBRARY_PATH
);

require 'osc/constants.php';
require 'osc/ClassLoader.php';

$classLoader = ClassLoader::getInstance();
$classLoader->addSearchPath( OVERRIDE_LIBRARY_PATH . '/osc', 'Override_' );
$classLoader->addSearchPath( LIBRARY_PATH . '/osc' );

$classLoader->loadFile( 'helpers/urls' );

$config = $classLoader->getClassInstance( 'Config' );
if( false === $config->hasConfig( 'database' ) )
{
	$configPath = $config->getConfigPath( 'database' );
	$absoluteUrl = osc_get_absolute_url();
	$errorPage = <<<HTML
<html>
<head><title>OpenSourceClassifieds &raquo; Error</title></head>
<body>
It doesn't seem to be a <code>$configPath</code> file. OpenSourceClassifieds isn't installed. <a href="http://forums.opensourceclassifieds.org/">Need more help?</a></p>
<p><a class="button" href="$absoluteUrl/installer/index.php">Install</a></p>
</body>
</html>
HTML;

	die( $errorPage );	
}

$classLoader->loadFile( 'Url/Abstract' );
$classLoader->loadFile( 'Form/Form' );
$classLoader->loadFile( 'Controller/Default' );
$classLoader->loadFile( 'Controller/Secure' );
$classLoader->loadFile( 'Controller/User' );
$classLoader->loadFile( 'Controller/Cacheable' );
$classLoader->loadFile( 'Params' );
$classLoader->loadFile( 'Database/DAO' );
$classLoader->loadFile( 'Ui/Theme' );

$classLoader->loadFile( 'helpers/themes' );
$classLoader->loadFile( 'helpers/paths' );
$classLoader->loadFile( 'helpers/formatting' );
$classLoader->loadFile( 'helpers/categories' );
$classLoader->loadFile( 'helpers/preference' );
$classLoader->loadFile( 'helpers/locales' );
$classLoader->loadFile( 'helpers/translations' );
$classLoader->loadFile( 'helpers/items' );
$classLoader->loadFile( 'helpers/search' );
$classLoader->loadFile( 'helpers/users' );

$generalConfig = $config->getConfig( 'general' );
define( 'WEB_PATH', $generalConfig['webUrl'] );

$dbConfig = $config->getConfig( 'database' );
define( 'DB_TABLE_PREFIX', $dbConfig['tablePrefix'] );

define( 'DB_HOST', $dbConfig['host'] );
define( 'DB_USER', $dbConfig['user'] );
define( 'DB_PASS', $dbConfig['password'] );
define( 'DB_NAME', $dbConfig['name'] );

$pluginManager = $classLoader->getClassInstance( 'PluginManager' );
$pluginManager->loadPlugins();

$classLoader->loadFile( 'helpers/plugins' );
$classLoader->loadFile( 'helpers/views' );

require 'Cuore/ClassLoader.php';
\Cuore\ClassLoader::getInstance();

// Ugly workaround... migration to Cuore framework in progress.
class HttpRequest extends \Cuore\Http\Request {}

class HttpResponse extends \Cuore\Http\Response
{
	private $redirector;

	public function __construct()
	{
		$this->redirector = new \Cuore\Web\Redirector;
	}

	public function sendRedirection( $url )
	{
		$this->redirector->goToUrl( $url );
	}
}


