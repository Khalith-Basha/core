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

require '../loader.php';

define( 'OC_ADMIN', 'true');

if (file_exists(ABS_PATH . '.maintenance')) 
{
	define('__OSC_MAINTENANCE__', true);
}

$page = Params::getParam('page');
if( empty( $page ) ) $page = 'index';

$action = Params::getParam( 'action' );
if( empty( $action ) ) $action = 'index';

WebThemes::newInstance();
switch ( "x$page" ) 
{
case ('items'):
	require_once (osc_admin_base_path() . 'items.php');
	$do = new CAdminItems();
	$do->doModel();
	break;

case ('comments'):
	require_once (osc_admin_base_path() . 'comments.php');
	$do = new CAdminItemComments();
	$do->doModel();
	break;

case ('media'):
	require_once (osc_admin_base_path() . 'media.php');
	$do = new CAdminMedia();
	$do->doModel();
	break;

case ('login'):
	require_once (osc_admin_base_path() . 'login.php');
	$do = new CAdminLogin();
	$do->doModel();
	break;

case ('categories'):
	require_once (osc_admin_base_path() . 'categories.php');
	$do = new CAdminCategories();
	$do->doModel();
	break;

case ('emails'):
	require_once (osc_admin_base_path() . 'emails.php');
	$do = new CAdminEmails();
	$do->doModel();
	break;

case ('pages'):
	require_once (osc_admin_base_path() . 'pages.php');
	$do = new CAdminPages();
	$do->doModel();
	break;

case ('settings'):
	require_once (osc_admin_base_path() . 'settings.php');
	$do = new CAdminSettings();
	$do->doModel();
	break;

case ('plugins'):
	require_once (osc_admin_base_path() . 'plugins.php');
	$do = new CAdminPlugins();
	$do->doModel();
	break;

case ('languages'):
	require_once (osc_admin_base_path() . 'languages.php');
	$do = new CAdminLanguages();
	$do->doModel();
	break;

case ('admins'):
	require_once (osc_admin_base_path() . 'admins.php');
	$do = new CAdminAdmins();
	$do->doModel();
	break;

case ('users'):
	require_once (osc_admin_base_path() . 'users.php');
	$do = new CAdminUsers();
	$do->doModel();
	break;

case ('ajax'):
	require_once (osc_admin_base_path() . 'ajax/ajax.php');
	$do = new CAdminAjax();
	$do->doModel();
	break;

case ('appearance'):
	require_once (osc_admin_base_path() . 'appearance.php');
	$do = new CAdminAppearance();
	$do->doModel();
	break;

case ('tools'):
	require_once (osc_admin_base_path() . 'tools.php');
	$do = new CAdminTools();
	$do->doModel();
	break;

case ('stats'):
	require_once (osc_admin_base_path() . 'stats.php');
	$do = new CAdminStats();
	$do->doModel();
	break;

case ('cfields'):
	require_once (osc_admin_base_path() . 'custom_fields.php');
	$do = new CAdminCFields();
	$do->doModel();
	break;
}

$ctrlPath = osc_admin_base_path() . '/controllers/' . $page . '/' . $action . '.php';
require $ctrlPath;

$className = 'CAdmin' . ucfirst( $page );
$ctrl = new $className;
$ctrl->processRequest( new HttpRequest, new HttpResponse );


