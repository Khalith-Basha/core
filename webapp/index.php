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

require 'loader.php';

// If the "installer" folder is still present, show a warning. 
if( file_exists( ABS_PATH . '/installer' ) )
{
	trigger_error( __('The "installer" folder should be removed for security reasons.'), E_USER_WARNING );
}

if (osc_timezone() != '') 
{
	date_default_timezone_set(osc_timezone());
}

if( file_exists( ABS_PATH . '/.maintenance' ) ) 
{
	if (!osc_is_admin_user_logged_in()) 
	{
		require_once 'osc/helpers/hErrors.php';
		$title = 'OpenSourceClassifieds &raquo; Error';
		$message = sprintf(__('We are sorry for any inconvenience. %s is under maintenance mode') . '.', osc_page_title());
		osc_die($title, $message);
	}
	else
	{
		define('__OSC_MAINTENANCE__', true);
	}
}
if (!osc_users_enabled() && osc_is_web_user_logged_in()) 
{
	$classLoader->getClassInstance( 'Session' )->_drop('userId');
	$classLoader->getClassInstance( 'Session' )->_drop('userName');
	$classLoader->getClassInstance( 'Session' )->_drop('userEmail');
	$classLoader->getClassInstance( 'Session' )->_drop('userPhone');
	ClassLoader::getInstance()->getClassInstance( 'Cookie' )->pop('oc_userId');
	ClassLoader::getInstance()->getClassInstance( 'Cookie' )->pop('oc_userSecret');
	ClassLoader::getInstance()->getClassInstance( 'Cookie' )->set();
}

$rewrite = $classLoader->getClassInstance( 'Rewrite' );
$rewrite->loadRules();
$rewrite->init();

$page = Params::getParam('page');
if (empty($page)) $page = 'index';
$action = Params::getParam('action');
if (empty($action)) $action = 'index';
$req = new HttpRequest;
$resp = new HttpResponse;
$controllerPath = "controllers/$page/$action.php";
if (file_exists($controllerPath)) 
{
	require $controllerPath;
	$className = 'CWeb' . ucfirst($page);
	if (class_exists($className)) 
	{
		$classInstance = new $className;
		$classInstance->processRequest($req, $resp);
	}
	else
	{
		trigger_error('Class does not exist: ' . $className);
	}
	exit(0);
}
else
{
	// @TODO Send a 404 error code here.
	trigger_error('File not found: ' . $controllerPath);
}

