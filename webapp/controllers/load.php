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

define('OpenSourceClassifieds_VERSION', '2.3.3') ;

if( !defined('ABS_PATH') ) {
    define( 'ABS_PATH', dirname(__FILE__) );
}

define('LIB_PATH', ABS_PATH . '/library/') ;
define('CONTENT_PATH', ABS_PATH . '/components/') ;
define('THEMES_PATH', CONTENT_PATH . '/themes/') ;
define('PLUGINS_PATH', CONTENT_PATH . '/plugins/') ;
define('TRANSLATIONS_PATH', CONTENT_PATH . '/languages/') ;

set_include_path( get_include_path() . PATH_SEPARATOR . LIB_PATH );

require 'osc/config.php';

$currentConfig = getCurrentConfig();
$configPath = implode( DIRECTORY_SEPARATOR, array( ABS_PATH, 'config', $currentConfig, 'general.php' ) );

if( !file_exists( $configPath ) ) {
    require_once 'osc/helpers/hErrors.php' ;

    $title   = 'OpenSourceClassifieds &raquo; Error' ;
    $message = 'There doesn\'t seem to be a <code>' . $configPath . '</code> file. OpenSourceClassifieds isn\'t installed. <a href="http://forums.opensourceclassifieds.org/">Need more help?</a></p>' ;
    $message .= '<p><a class="button" href="' . osc_get_absolute_url() . 'installer/index.php">Install</a></p>' ;

    osc_die($title, $message) ;
}

require $configPath;

require_once 'osc/default-constants.php' ;

// Sets PHP error handling
if( OSC_DEBUG ) {
    ini_set( 'display_errors', 1 ) ;
    error_reporting( E_ALL | E_STRICT ) ;

    if( OSC_DEBUG_LOG ) {
        ini_set( 'display_errors', 0 ) ;
        ini_set( 'log_errors', 1 ) ;
        ini_set( 'error_log', CONTENT_PATH . 'debug.log' ) ;
    }
} else {
    error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING ) ;
}

require_once 'osc/db.php';
require_once 'osc/Logger/LogDatabase.php' ;
require_once 'osc/classes/database/DBConnectionClass.php';
require_once 'osc/classes/database/DBCommandClass.php';
require_once 'osc/classes/database/DBRecordsetClass.php';
require_once 'osc/classes/database/DAO.php';
require_once 'osc/model/Preference.php';
require_once 'osc/helpers/hPreference.php';
require_once 'osc/helpers/hDatabaseInfo.php';
require_once 'osc/helpers/hDefines.php';
require_once 'osc/helpers/hLocale.php';
require_once 'osc/helpers/hMessages.php';
require_once 'osc/helpers/hUsers.php';
require_once 'osc/helpers/hItems.php';
require_once 'osc/helpers/hSearch.php';
require_once 'osc/helpers/hUtils.php';
require_once 'osc/helpers/hCategories.php';
require_once 'osc/helpers/hTranslations.php';
require_once 'osc/helpers/hSecurity.php';
require_once 'osc/helpers/hSanitize.php';
require_once 'osc/helpers/hValidate.php';
require_once 'osc/helpers/hPage.php';
require_once 'osc/helpers/hPagination.php';
require_once 'osc/helpers/hPremium.php';
require_once 'osc/helpers/hTheme.php';
require_once 'osc/core/Params.php';
require_once 'osc/core/Cookie.php';
require_once 'osc/core/Session.php';
require_once 'osc/core/View.php';
require_once 'osc/core/BaseModel.php';
require_once 'osc/core/SecBaseModel.php';
require_once 'osc/core/WebSecBaseModel.php';
require_once 'osc/core/AdminSecBaseModel.php';
require_once 'osc/core/Translation.php';

require_once 'osc/AdminThemes.php';
require_once 'osc/WebThemes.php';
require_once 'osc/compatibility.php';
require_once 'osc/utils.php';
require_once 'osc/formatting.php';
require_once 'osc/feeds.php';
require_once 'osc/locales.php';
require_once 'osc/plugins.php';
require_once 'osc/helpers/hPlugins.php';
require_once 'osc/ItemActions.php';
require_once 'osc/emails.php';
require_once 'osc/model/Admin.php';
require_once 'osc/model/Alerts.php';
require_once 'osc/model/Cron.php';
require_once 'osc/model/Category.php';
require_once 'osc/model/CategoryStats.php';
require_once 'osc/model/City.php';
require_once 'osc/model/Country.php';
require_once 'osc/model/Currency.php';
require_once 'osc/model/OSCLocale.php';
require_once 'osc/model/Item.php';
require_once 'osc/model/ItemComment.php';
require_once 'osc/model/ItemResource.php';
require_once 'osc/model/ItemStats.php';
require_once 'osc/model/Page.php';
require_once 'osc/model/PluginCategory.php';
require_once 'osc/model/Region.php';
require_once 'osc/model/User.php';
require_once 'osc/model/UserEmailTmp.php';
require_once 'osc/model/ItemLocation.php';
require_once 'osc/model/Widget.php';
require_once 'osc/model/Search.php';
require_once 'osc/model/LatestSearches.php';
require_once 'osc/model/SiteInfo.php';
require_once 'osc/model/Field.php';
require_once 'osc/model/Log.php';
require_once 'osc/classes/Cache.php';
require_once 'osc/classes/ImageResizer.php';
require_once 'osc/classes/RSSFeed.php';
require_once 'osc/classes/Sitemap.php';
require_once 'osc/classes/Pagination.php';
require_once 'osc/classes/Watermark.php';
require_once 'osc/classes/Rewrite.php';
require_once 'osc/classes/Stats.php';
require_once 'osc/alerts.php';

require_once 'osc/frm/Form.form.class.php';
require_once 'osc/frm/Page.form.class.php';
require_once 'osc/frm/Category.form.class.php';
require_once 'osc/frm/Item.form.class.php';
require_once 'osc/frm/Contact.form.class.php';
require_once 'osc/frm/Comment.form.class.php';
require_once 'osc/frm/User.form.class.php';
require_once 'osc/frm/Language.form.class.php';
require_once 'osc/frm/SendFriend.form.class.php';
require_once 'osc/frm/Alert.form.class.php';
require_once 'osc/frm/Field.form.class.php';

require_once 'osc/functions.php';

define('__OSC_LOADED__', true);

Plugins::init() ;

Rewrite::newInstance()->init();
// Moved from BaseModel, since we need some session magic on index.php ;)
Session::newInstance()->session_start() ;

if(osc_timezone() != '') {
    date_default_timezone_set(osc_timezone());
}

function osc_show_maintenance() {
    if(defined('__OSC_MAINTENANCE__')) { ?>
        <div id="maintenance" name="maintenance">
             <?php _e("The website is currently under maintenance mode"); ?>
        </div>
    <?php }
}

function osc_meta_generator() {
    echo '<meta name="generator" content="OpenSourceClassifieds ' . OpenSourceClassifieds_VERSION . '" />';
}

osc_add_hook("header", "osc_show_maintenance");
osc_add_hook("header", "osc_meta_generator");

