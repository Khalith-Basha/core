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
define( 'DEFAULT_TABLE_PREFIX', 'osc_');
define( 'DEFAULT_CONFIG_FOLDER_PATH', implode(DIRECTORY_SEPARATOR, array(ABS_PATH, 'config', 'default')));
define( 'DEFAULT_CONFIG_PATH', implode(DIRECTORY_SEPARATOR, array(DEFAULT_CONFIG_FOLDER_PATH, 'general.php')));
define( 'SAMPLE_CONFIG_PATH', implode(DIRECTORY_SEPARATOR, array(ABS_PATH, 'installer', 'data', 'config-sample.php')));

function basic_info() 
{
	require_once 'osc/model/Admin.php';
	require_once 'osc/helpers/hSecurity.php';
	$admin = Params::getParam('s_name');
	if ($admin == '') 
	{
		$admin = 'admin';
	}
	$password = Params::getParam('s_passwd');
	if ($password == '') 
	{
		$password = osc_genRandomPassword();
	}
	$email = Params::getParam('email');
	$webtitle = Params::getParam('webtitle');
	Admin::newInstance()->insert(array('s_name' => 'Administrator', 's_username' => $admin, 's_password' => sha1($password), 's_email' => $email));
	$mPreference = Preference::newInstance();
	$mPreference->insert(array('s_section' => 'osclass', 's_name' => 'pageTitle', 's_value' => $webtitle, 'e_type' => 'STRING'));
	$mPreference->insert(array('s_section' => 'osclass', 's_name' => 'contactEmail', 's_value' => $email, 'e_type' => 'STRING'));
	$body = 'Welcome ' . $webtitle . ',<br/><br/>';
	$body.= 'Your OpenSourceClassifieds installation at ' . WEB_PATH . ' is up and running. You can access to the administration panel with this data access:<br/>';
	$body.= '<ul>';
	$body.= '<li>username: ' . $admin . '</li>';
	$body.= '<li>password: ' . $password . '</li>';
	$body.= '</ul>';
	$body.= 'Regards,<br/>';
	$body.= 'The <a href=\'http://opensourceclassifieds.org/\'>OpenSourceClassifieds</a> team';
	$sitename = strtolower($_SERVER['SERVER_NAME']);
	if (substr($sitename, 0, 4) == 'www.') 
	{
		$sitename = substr($sitename, 4);
	}
	try
	{
		require_once 'phpmailer/class.phpmailer.php';
		$mail = new PHPMailer(true);
		$mail->CharSet = "utf-8";
		$mail->Host = "localhost";
		$mail->From = 'osclass@' . $sitename;
		$mail->FromName = 'OpenSourceClassifieds';
		$mail->Subject = 'OpenSourceClassifieds successfully installed!';
		$mail->AddAddress($email, 'OpenSourceClassifieds administrator');
		$mail->Body = $body;
		$mail->AltBody = $body;
		if (!$mail->Send()) 
		{
			throw new Exception($email . ' - ' . $mail->ErrorInfo);
		}
	}
	catch(phpmailerException $exception) 
	{
		throw new Exception($email . ' - ' . $exception->errorMessage());
	}
	$return = array($admin, $password);
	return $return;
}
/*
 * The url of the site
 *
 * @return string The url of the site
*/
function get_absolute_url() 
{
	$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
	$pos = strpos($_SERVER['REQUEST_URI'], 'library');
	return $protocol . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, $pos);
}
/*
 * The relative url on the domain url
 *
 * @return string The relative url on the domain url
*/
function get_relative_url() 
{
	$url = $_SERVER['REQUEST_URI'];
	return substr($url, 0, strpos($url, '/library')) . "/";
}
/*
 * Get the requirements to install OpenSourceClassifieds
 *
 * @return array Requirements
*/
function get_requirements() 
{
	$minimumPhpVersion = '5.3';
	$array = array(
		'PHP version >= ' . $minimumPhpVersion => array(
			'check' => version_compare( PHP_VERSION, $minimumPhpVersion, '>=' ),
			'solution' => "PHP $minimumPhpVersion is required to run OpenSourceClassifieds. You may talk with your hosting to upgrade your PHP version."
		),
		'Folder <code>components/uploads</code> exists' => array(
			'check' => file_exists( ABS_PATH . '/components/uploads' ),
			'solution' => 'You have to create <code>uploads</code> folder, i.e.: <code>mkdir ' . ABS_PATH . '/components/uploads</code>'
		),
		'Folder <code>components/uploads</code> is writable' => array(
			'check' => is_writable( ABS_PATH . '/components/uploads' ),
			'solution' => 'Folder <code>uploads</code> has to be writable, i.e.: <code>chmod a+w ' . ABS_PATH . '/components/uploads</code>'
		),
		'Folder <code>components/languages</code> exists' => array(
			'check' => file_exists( ABS_PATH . '/components/languages' ),
			'solution' => 'You have to create <code>languages</code> folder, i.e.: <code>mkdir ' . ABS_PATH . '/components/languages/</code>'
		)
	);
	$php_extensions = array( 'mysqli', 'gd', 'curl', 'zip', 'memcached', 'mbstring' );
	foreach ($php_extensions as $php_ext) 
	{
		$array[$php_ext . ' extension for PHP'] = array(
			'check' => extension_loaded( $php_ext ),
			'solution' => "Install and enable the PHP extension '$php_ext'."
		);
	}
	$config_writable = false;
	$root_writable = false;
	$config_sample = false;
	if (file_exists( DEFAULT_CONFIG_PATH ) ) 
	{
		if (is_writable( DEFAULT_CONFIG_PATH ) ) 
		{
			$config_writable = true;
		}
		$array['File <code>' . DEFAULT_CONFIG_PATH . '</code> is writable'] = array(
			'check' => $config_writable,
			'solution' => 'File <code>' . DEFAULT_CONFIG_PATH . '</code> has to be writable, i.e.: <code>chmod a+w ' . DEFAULT_CONFIG_PATH . '</code>'
		);
	}
	else
	{
		if (is_writable( DEFAULT_CONFIG_FOLDER_PATH )) 
		{
			$root_writable = true;
		}
		$array['Config directory is writable'] = array(
			'check' => $root_writable,
			'solution' => 'Config folder has to be writable, i.e.: <code>chmod a+w ' . DEFAULT_CONFIG_FOLDER_PATH . '</code>'
		);
		if (file_exists( SAMPLE_CONFIG_PATH )) 
		{
			$config_sample = true;
		}
		$array['File <code>' . SAMPLE_CONFIG_PATH . '</code> exists'] = array(
			'check' => $config_sample,
			'solution' => 'File <code>' . SAMPLE_CONFIG_PATH . '</code> is required, you should download OpenSourceClassifieds again.'
		);
	}
	return $array;
}

/**
 * Check if some of the requirements to install OpenSourceClassifieds are correct or not
 *
 * @return boolean Check if all the requirements are correct
 */
function check_requirements( array $requirements ) 
{
	foreach( $requirements as $req )
		if( false === $req['check'] )
			return true;

	return false;
}

/*
 * Install OpenSourceClassifieds database
 *
 * @return mixed Error messages of the installation
*/
function oc_install() 
{
	$dbhost = Params::getParam('dbhost');
	$dbname = Params::getParam('dbname');
	$username = Params::getParam('username');
	$password = Params::getParam('password');
	$tableprefix = Params::getParam('tableprefix');
	$createdb = false;
	if (Params::getParam('createdb') != '') 
	{
		$createdb = true;
	}
	if ($createdb) 
	{
		$adminuser = Params::getParam('admin_username');
		$adminpwd = Params::getParam('admin_password');
		$master_conn = new Database_Connection($dbhost, $adminuser, $adminpwd, '');
		$error_num = $master_conn->getErrorConnectionLevel();
		if ($error_num > 0) 
		{
			switch ($error_num) 
			{
			case 1049:
				return array('error' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges');
				break;

			case 1045:
				return array('error' => 'Cannot connect to the database. Check if the user has privileges.');
				break;

			case 1044:
				return array('error' => 'Cannot connect to the database. Check if the username and password are correct.');
				break;

			case 2005:
				return array('error' => 'Cannot resolve MySQL host. Check if the host is correct.');
				break;

			default:
				return array('error' => 'Cannot connect to database. Error number: ' . $error_num . '.');
				break;
			}
		}
		$m_db = $master_conn->getOsclassDb();
		$comm = new DBCommandClass($m_db);
		$comm->query(sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname));
		$error_num = $comm->getErrorLevel();
		if ($error_num > 0) 
		{
			if (in_array($error_num, array(1006, 1044, 1045))) 
			{
				return array('error' => 'Cannot create the database. Check if the admin username and password are correct.');
			}
			return array('error' => 'Cannot create the database. Error number: ' . $error_num . '.');
		}
		unset($conn);
		unset($comm);
		unset($master_conn);
	}
	$conn = new Database_Connection($dbhost, $username, $password, $dbname);
	$error_num = $conn->getErrorLevel();
	if ($error_num > 0) 
	{
		switch ($error_num) 
		{
		case 1049:
			return array('error' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges');
			break;

		case 1045:
			return array('error' => 'Cannot connect to the database. Check if the user has privileges.');
			break;

		case 1044:
			return array('error' => 'Cannot connect to the database. Check if the username and password are correct.');
			break;

		case 2005:
			return array('error' => 'Cannot resolve MySQL host. Check if the host is correct.');
			break;

		default:
			return array('error' => 'Cannot connect to database. Error number: ' . $error_num . '.');
			break;
		}
	}
	if (!is_writable(DEFAULT_CONFIG_PATH)) 
	{
		return array('error' => 'Cannot write in ' . DEFAULT_CONFIG_PATH . ' file. Check if the file is writable.');
	}
	create_config_file($dbname, $username, $password, $dbhost, $tableprefix);

	require DEFAULT_CONFIG_PATH;
	$sql = file_get_contents(ABS_PATH . '/installer/data/struct.sql');
	$c_db = $conn->getOsclassDb();
	$comm = new DBCommandClass($c_db);
	$comm->importSQL($sql);
	$error_num = $comm->getErrorLevel();
	if ($error_num > 0) 
	{
		switch ($error_num) 
		{
		case 1050:
			return array('error' => 'There are tables with the same name in the database. Change the table prefix or the database and try again.');
			break;

		default:
			return array('error' => 'Cannot create the database structure. Error number: ' . $error_num . '.');
			break;
		}
	}
	require_once 'osc/locales.php';
	require_once 'osc/model/Locale.php';
	$localeManager = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' );
	$locales = osc_listLocales();
	foreach ($locales as $locale) 
	{
		$values = array('pk_c_code' => $locale['code'], 's_name' => $locale['name'], 's_short_name' => $locale['short_name'], 's_description' => $locale['description'], 's_version' => $locale['version'], 's_author_name' => $locale['author_name'], 's_author_url' => $locale['author_url'], 's_currency_format' => $locale['currency_format'], 's_date_format' => $locale['date_format'], 'b_enabled' => ($locale['code'] == 'en_US') ? 1 : 0, 'b_enabled_bo' => 1);
		if (isset($locale['stop_words'])) 
		{
			$values['s_stop_words'] = $locale['stop_words'];
		}
		$localeManager->insert($values);
	}
	$required_files = array('basic_data.sql', 'categories.sql', 'pages.sql');
	$sql = '';
	foreach ($required_files as $file) 
	{
		if (!file_exists(ABS_PATH . '/installer/data/' . $file)) 
		{
			return array('error' => 'the file ' . $file . ' doesn\'t exist in data folder');
		}
		else
		{
			$sql.= file_get_contents(ABS_PATH . '/installer/data/' . $file);
		}
	}
	$comm->importSQL($sql);
	$error_num = $comm->getErrorLevel();
	if ($error_num > 0) 
	{
		switch ($error_num) 
		{
		case 1471:
			return array('error' => 'Cannot insert basic configuration. This user has no privileges to \'INSERT\' into the database.');
			break;

		default:
			return array('error' => 'Cannot insert basic configuration. Error number: ' . $error_num . '.');
			break;
		}
	}
	return false;
}
/*
 * Create config file from scratch
 *
 * @param string $dbname Database name
 * @param string $username User of the database
 * @param string $password Password for user of the database
 * @param string $dbhost Database host
 * @param string $tableprefix Prefix for table names
 * @return mixed Error messages of the installation
*/
function create_config_file($dbname, $username, $password, $dbhost, $tableprefix) 
{
	$abs_url = get_absolute_url();
	$rel_url = get_relative_url();
	$config_text = <<<CONFIG
<?php

/** MySQL database name */
define('DB_NAME', '$dbname');

/** MySQL database username */
define('DB_USER', '$username');

/** MySQL database password */
define('DB_PASSWORD', '$password');

/** MySQL hostname */
define('DB_HOST', '$dbhost');

/** Database Table prefix */
define('DB_TABLE_PREFIX', '$tableprefix');

define('REL_WEB_URL', '$rel_url');

define('WEB_PATH', '$abs_url');

CONFIG;
	$ds = DIRECTORY_SEPARATOR;
	$configPath = ABS_PATH . $ds . 'config' . $ds . 'default' . $ds . 'general.php';
	file_put_contents( $configPath, $config_text );
}
function is_osclass_installed() 
{
	if( file_exists( DEFAULT_CONFIG_PATH ) ) 
	{
		require DEFAULT_CONFIG_PATH;
		
		if( !defined( 'DB_HOST' ) )
			return false;

		return true;
	}

	return false;
}
function ping_search_engines($bool) 
{
	$mPreference = Preference::newInstance();
	if ($bool == 1) 
	{
		$mPreference->insert(array('s_section' => 'osclass', 's_name' => 'ping_search_engines', 's_value' => '1', 'e_type' => 'BOOLEAN'));
		// GOOGLE
		osc_doRequest('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . urlencode(osc_search_url(array('sFeed' => 'rss'))), array());
		// BING
		osc_doRequest('http://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode(osc_search_url(array('sFeed' => 'rss'))), array());
		// YAHOO!
		osc_doRequest('http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=' . urlencode(osc_search_url(array('sFeed' => 'rss'))), array());
	}
	else
	{
		$mPreference->insert(array('s_section' => 'osclass', 's_name' => 'ping_search_engines', 's_value' => '0', 'e_type' => 'BOOLEAN'));
	}
}
function display_finish($password) 
{
	require_once 'osc/model/Admin.php';
	require_once 'osc/model/Category.php';
	require_once 'osc/model/Item.php';
	require_once 'osc/helpers/hPlugins.php';
	require_once 'osc/plugins.php';
	$data = array();
	$mAdmin = new Admin();
	$mPreference = Preference::newInstance();
	$mPreference->insert(array('s_section' => 'osclass', 's_name' => 'osclass_installed', 's_value' => '1', 'e_type' => 'BOOLEAN'));
	// update categories
	$mCategories = new Category();
	if (Params::getParam('submit') != '') 
	{
		$categories = Params::getParam('categories');
		if (is_array($categories)) 
		{
			foreach ($categories as $category_id) 
			{
				$mCategories->update(array('b_enabled' => '1'), array('pk_i_id' => $category_id));
			}
		}
	}
	$aCategoriesToDelete = $mCategories->listWhere("a.b_enabled = 0");
	foreach ($aCategoriesToDelete as $aCategory) 
	{
		$mCategories->deleteByPrimaryKey($aCategory['pk_i_id']);
	}
	$admin = $mAdmin->findByPrimaryKey(1);
	$data['s_email'] = $admin['s_email'];
	$data['admin_user'] = $admin['s_username'];
	$data['password'] = $password;
	require 'views/finish.php';
}

