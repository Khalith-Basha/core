<?php

define( 'OSC_VERSION', '1.0' );

define( 'ABS_PATH', dirname( __FILE__ ) );
define( 'CONTENT_PATH', ABS_PATH . '/components' );
define( 'THEMES_PATH', CONTENT_PATH . '/themes' );
define( 'PLUGINS_PATH', CONTENT_PATH . '/plugins' );
define( 'TRANSLATIONS_PATH', CONTENT_PATH . '/languages' );

set_include_path(get_include_path() . PATH_SEPARATOR . ABS_PATH . '/library');

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

require_once 'osc/default-constants.php';
require_once 'osc/db.php';
require_once 'osc/classes/database/DBConnectionClass.php';
require_once 'osc/classes/database/DBCommandClass.php';
require_once 'osc/classes/database/DBRecordsetClass.php';
require_once 'osc/classes/database/DAO.php';
require_once 'osc/model/Preference.php';
require_once 'osc/helpers/hPreference.php';
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
require_once 'osc/core/controller.php';
require_once 'osc/core/SecBaseModel.php';
require_once 'osc/core/WebSecBaseModel.php';
require_once 'osc/core/AdminSecBaseModel.php';
require_once 'osc/core/Translation.php';
require_once 'osc/AdminThemes.php';
require_once 'osc/WebThemes.php';
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
require_once 'osc/model/SiteInfo.php';
require_once 'osc/model/Field.php';
require_once 'osc/model/Log.php';
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

require 'osc/urls/item.php';

Plugins::init();

require 'osc/url_rules.php';
Rewrite::newInstance()->init();

Session::newInstance()->session_start();

