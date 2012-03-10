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
define( 'DEFAULT_TABLE_PREFIX', 'osc_');

define( 'DEFAULT_CONFIG_FOLDER_PATH', implode(DIRECTORY_SEPARATOR, array(ABS_PATH, 'config', 'default')));
define( 'SAMPLE_CONFIG_FOLDER_PATH', implode(DIRECTORY_SEPARATOR, array(ABS_PATH, 'installer', 'data', 'config' )));

function basic_info() 
{
	require_once 'osc/helpers/security.php';
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

	$classLoader = ClassLoader::getInstance();
	$classLoader->getClassInstance( 'Model_User' )->insert(
		array(
			's_name' => 'Administrator',
			's_username' => $admin,
			's_password' => sha1( $password ),
			's_email' => $email,
			'role_id' => 1
		)
	);

	$mPreference = $classLoader->getClassInstance( 'Model_Preference' );
	$mPreference->insert( array('s_section' => 'osc', 's_name' => 'version', 's_value' => APP_VERSION, 'e_type' => 'STRING') );
	$mPreference->insert( array('s_section' => 'osc', 's_name' => 'contactEmail', 's_value' => $email, 'e_type' => 'STRING') );

	$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
	$generalConfig = $config->getConfig( 'general' );
	$webPath = $generalConfig['webUrl'];

	$body = 'Welcome ' . $webtitle . ',<br/><br/>';
	$body.= 'Your OpenSourceClassifieds installation at ' . $webPath . ' is up and running. You can access to the administration panel with this data access:<br/>';
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
		$mail->From = 'admin@' . $sitename;
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

	return array( $admin, $password );
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
				return array('message' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges');
				break;

			case 1045:
				return array('message' => 'Cannot connect to the database. Check if the user has privileges.');
				break;

			case 1044:
				return array('message' => 'Cannot connect to the database. Check if the username and password are correct.');
				break;

			case 2005:
				return array('message' => 'Cannot resolve MySQL host. Check if the host is correct.');
				break;

			default:
				return array('message' => 'Cannot connect to database. Error number: ' . $error_num . '.');
				break;
			}
		}
		$m_db = $master_conn->getResource();
		$comm = new DBCommandClass($m_db);
		$comm->query(sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname));
		$error_num = $comm->getErrorLevel();
		if ($error_num > 0) 
		{
			if (in_array($error_num, array(1006, 1044, 1045))) 
			{
				return array('message' => 'Cannot create the database. Check if the admin username and password are correct.');
			}
			return array('message' => 'Cannot create the database. Error number: ' . $error_num . '.');
		}
		unset($conn);
		unset($comm);
		unset($master_conn);
	}
	if( !defined( 'DB_TABLE_PREFIX' ) )
		define( 'DB_TABLE_PREFIX', $tableprefix );
	$conn = ClassLoader::getInstance()->getClassInstance( 'Database_Connection', true, array( $dbhost, $username, $password, $dbname, $tableprefix ) );
	$error_num = $conn->getErrorLevel();
	if ($error_num > 0) 
	{
		switch ($error_num) 
		{
		case 1049:
			return array('message' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges');
			break;

		case 1045:
			return array('message' => 'Cannot connect to the database. Check if the user has privileges.');
			break;

		case 1044:
			return array('message' => 'Cannot connect to the database. Check if the username and password are correct.');
			break;

		case 2005:
			return array('message' => 'Cannot resolve MySQL host. Check if the host is correct.');
			break;

		default:
			return array('message' => 'Cannot connect to database. Error number: ' . $error_num . '.');
			break;
		}
	}
	/*
	if (!is_writable(DEFAULT_CONFIG_PATH)) 
	{
		return array('message' => 'Cannot write in ' . DEFAULT_CONFIG_PATH . ' file. Check if the file is writable.');
	}
	*/
	create_config_file($dbname, $username, $password, $dbhost, $tableprefix);

	$comm = new Database_Command( $conn->getResource() );

	$sql = file_get_contents( ABS_PATH . '/installer/data/struct.sql' );
	$queries = explode( ';', $sql );

	try
	{
		foreach( $queries as $query )
		{
			$comm->importSQL( $query );
		}
	}
	catch( Database_Exception $e )
	{
		return array( 'message' => $e->getMessage(), 'description' => $e->getSql() );
	}
	catch( Exception $e )
	{
		return array( 'message' => $e->getMessage() );
	}

	require_once 'osc/Model/Locale.php';
	$localeManager = new Model_Locale;
	$locales = osc_listLocales();
	foreach ($locales as $locale) 
	{
		$values = array(
			'pk_c_code' => $locale['code'],
			's_name' => $locale['name'],
			's_short_name' => $locale['short_name'],
			's_description' => $locale['description'],
			's_version' => $locale['version'],
			's_author_name' => $locale['author_name'],
			's_author_url' => $locale['author_url'],
			's_currency_format' => $locale['currency_format'],
			's_date_format' => $locale['date_format'],
			'b_enabled' => ($locale['code'] == 'en_US') ? 1 : 0,
			'b_enabled_bo' => 1
		);
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
			return array('message' => 'the file ' . $file . ' doesn\'t exist in data folder');
		}
		else
		{
			$sql.= file_get_contents(ABS_PATH . '/installer/data/' . $file);
		}
	}

	try
	{
		$comm->importSQL( $sql );
	}
	catch( Exception $e )
	{
		return array( 'message' => $e->getMessage() );
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
	$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
	$configDatabasePath = $config->getConfigPath( 'database' );
	$configDatabase = file_get_contents( ABS_PATH . '/installer/data/config/database.php' );
	$configDatabase = str_replace(
		array( '##DB_HOST##', '##DB_USER##', '##DB_PASSWORD##', '##DB_NAME##', '##DB_TABLE_PREFIX##' ),
		array( $dbhost, $username, $password, $dbname, $tableprefix ),
		$configDatabase
	);
	file_put_contents( $configDatabasePath, $configDatabase );

	$configGeneralPath = $config->getConfigPath( 'general' );
	$configGeneral = file_get_contents( ABS_PATH . '/installer/data/config/general.php' );
	$configGeneral = str_replace(
		array( '##RELATIVE_WEB_URL##', '##WEB_URL##' ),
		array( osc_get_absolute_url(), osc_get_relative_url() ),
		$configGeneral
	);
	file_put_contents( $configGeneralPath, $configGeneral);
}

function is_osc_installed() 
{
	/** TODO: FIX THIS! */
	return false;
}

/**
 * @return boolean
 */
function requestUrl( $url )
{
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	$result = @curl_exec( $ch );
	curl_close( $ch );

	return $result;
}

function ping_search_engines($bool) 
{
	$classLoader = ClassLoader::getInstance();
	$classLoader->loadFile( 'Url_Abstract' );
	$searchUrls = $classLoader->getClassInstance( 'Url_Search' );
	$mPreference = $classLoader->getClassInstance( 'Model_Preference' );
	if ($bool == 1) 
	{
		$url = $searchUrls->osc_search_url(array('sFeed' => 'rss'));
		$mPreference->insert(array('s_section' => 'osc', 's_name' => 'ping_search_engines', 's_value' => '1', 'e_type' => 'BOOLEAN'));
		requestUrl('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . urlencode( $url ));
		requestUrl('http://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode( $url ));
		requestUrl('http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=' . urlencode( $url ));
	}
	else
	{
		$mPreference->insert(array('s_section' => 'osc', 's_name' => 'ping_search_engines', 's_value' => '0', 'e_type' => 'BOOLEAN'));
	}
}
function savePreferences($password) 
{
	$classLoader = ClassLoader::getInstance();
	$classLoader->loadFile( 'helpers/plugins' );
	$mAdmin = $classLoader->getClassInstance( 'Model_User' );
	$mPreference = $classLoader->getClassInstance( 'Model_Preference' );
	$mPreference->insert(array('s_section' => 'osc', 's_name' => 'osc_installed', 's_value' => '1', 'e_type' => 'BOOLEAN'));
	// update categories
	$mCategories = $classLoader->getClassInstance( 'Model_Category' );
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

	$data = array();
	$data['s_email'] = $admin['s_email'];
	$data['admin_user'] = $admin['s_username'];
	$data['password'] = $password;
	return $data;
}

