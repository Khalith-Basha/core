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

require 'osc/core/SecBaseModel.php';
require 'osc/core/AdminSecBaseModel.php';
require 'osc/model/Admin.php';
require 'osc/AdminThemes.php';

$page = Params::getParam('page');
if( empty( $page ) ) $page = 'index';

$action = Params::getParam( 'action' );
if( empty( $action ) ) $action = 'index';

WebThemes::newInstance();

$ctrlPath = osc_admin_base_path() . '/controllers/' . $page . '/' . $action . '.php';
require $ctrlPath;

$className = 'CAdmin' . ucfirst( $page );
$ctrl = new $className;
$ctrl->processRequest( new HttpRequest, new HttpResponse );


