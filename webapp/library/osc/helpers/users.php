<?php
/**
 * Helper Users
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets a specific field from current user
 *
 * @param string $field
 * @param string $locale
 * @return mixed
 */
function osc_user_field( $field, $locale = null )
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($view->_exists('users')) 
	{
		$user = $view->_current('users');
	}
	else
	{
		$user = $view->_get('user');
	}
	if( empty( $user ) )
	{
		trigger_error( 'null user' );
		return null;
	}
	return osc_field($user, $field, $locale);
}
/**
 * Gets user array from view
 *
 * @return array
 */
function osc_user() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($view->_exists('users')) 
	{
		$user = $view->_current('users');
	}
	else
	{
		$user = $view->_get('user');
	}
	return ($user);
}
/**
 * Gets true if user is logged in web
 *
 * @return boolean
 */
function osc_is_web_user_logged_in() 
{
	$classLoader = ClassLoader::getInstance();
	$session = $classLoader->getClassInstance( 'Session' );
	if ( $session->_get("userId") != '') 
	{
		$user = $classLoader->getClassInstance( 'Model_User' )->findByPrimaryKey( $session->_get("userId"));
		if (isset($user['b_enabled']) && $user['b_enabled'] == 1) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//can already be a logged user or not, we'll take a look into the cookie

	$cookie = ClassLoader::getInstance()->getClassInstance( 'Cookie' );
	if ($cookie->get_value('oc_userId') != '' && $cookie->get_value('oc_userSecret') != '') 
	{
		$user = $classLoader->getClassInstance( 'Model_User' )->findByIdSecret($cookie->get_value('oc_userId'), $cookie->get_value('oc_userSecret'));
		if (isset($user['b_enabled']) && $user['b_enabled'] == 1) 
		{
			$session->_set('userId', $user['pk_i_id']);
			$session->_set('userName', $user['s_name']);
			$session->_set('userEmail', $user['s_email']);
			$phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
			$session->_set('userPhone', $phone);
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}
/**
 * Gets logged user id
 *
 * @return int
 */
function osc_logged_user_id() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return $session->_get("userId");
}
/**
 * Gets logged user mail
 *
 * @return string
 */
function osc_logged_user_email() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userEmail');
}
/**
 * Gets logged user email
 *
 * @return string
 */
function osc_logged_user_name() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userName');
}
/**
 * Gets logged user phone
 *
 * @return string
 */
function osc_logged_user_phone() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userPhone');
}
/**
 * Gets true if admin user is logged in
 *
 * @return boolean
 */
function osc_is_admin_user_logged_in() 
{
	$classLoader = ClassLoader::getInstance();
	$session = $classLoader->getClassInstance( 'Session' );
	if ($session->_get("adminId") != '') 
	{
		$admin = $classLoader->getClassInstance( 'Model_Admin' )->findByPrimaryKey($session->_get("adminId"));
		if (isset($admin['pk_i_id'])) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//can already be a logged user or not, we'll take a look into the cookie
	$cookie = $classLoader->getClassInstance( 'Cookie' );
	if ($cookie->get_value('oc_adminId') != '' && $cookie->get_value('oc_adminSecret') != '') 
	{
		$admin = $classLoader->getClassInstance( 'Model_Admin' )->findByIdSecret($cookie->get_value('oc_adminId'), $cookie->get_value('oc_adminSecret'));
		if (isset($admin['pk_i_id'])) 
		{
			$session->_set('adminId', $admin['pk_i_id']);
			$session->_set('adminUserName', $admin['s_username']);
			$session->_set('adminName', $admin['s_name']);
			$session->_set('adminEmail', $admin['s_email']);
			$session->_set('adminLocale', $cookie->get_value('oc_adminLocale'));
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}
/**
 * Gets logged admin id
 *
 * @return int
 */
function osc_logged_admin_id() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (int)$session->_get("adminId");
}
/**
 * Gets logged admin username
 *
 * @return string
 */
function osc_logged_admin_username() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminUserName');
}
/**
 * Gets logged admin name
 * @return string
 */
function osc_logged_admin_name() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminName');
}
/**
 * Gets logged admin email
 *
 * @return string
 */
function osc_logged_admin_email() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminEmail');
}
/**
 * Gets name of current user
 *
 * @return string
 */
function osc_user_name() 
{
	return (string)osc_user_field("s_name");
}
/**
 * Gets email of current user
 *
 * @return string
 */
function osc_user_email() 
{
	return (string)osc_user_field("s_email");
}
/**
 * Gets registration date of current user
 *
 * @return string
 */
function osc_user_regdate() 
{
	return (string)osc_user_field("reg_date");
}
/**
 * Gets id of current user
 *
 * @return int
 */
function osc_user_id() 
{
	return (int)osc_user_field("pk_i_id");
}
/**
 * Gets website of current user
 *
 * @return string
 */
function osc_user_website() 
{
	return (string)osc_user_field("s_website");
}
/**
 * Gets description/information of current user
 *
 * @return string
 */
function osc_user_info() 
{
	return (string)osc_user_field("s_info");
}
/**
 * Gets phone of current user
 *
 * @return string
 */
function osc_user_phone_land() 
{
	return (string)osc_user_field("s_phone_land");
}
/**
 * Gets cell phone of current user
 *
 * @return string
 */
function osc_user_phone_mobile() 
{
	return (string)osc_user_field("s_phone_mobile");
}
/**
 * Gets phone_land if exist, else if exist return phone_mobile,
 * else return string blank
 * @return string
 */
function osc_user_phone() 
{
	if (osc_user_field("s_phone_land") != "") 
	{
		return osc_user_field("s_phone_land");
	}
	else if (osc_user_field("s_phone_mobile") != "") 
	{
		return osc_user_field("s_phone_mobile");
	}
	return null;
}
/**
 * Gets country of current user
 *
 * @return string
 */
function osc_user_country() 
{
	return (string)osc_user_field("s_country");
}
/**
 * Gets region of current user
 *
 * @return string
 */
function osc_user_region() 
{
	return (string)osc_user_field("s_region");
}
/**
 * Gets city of current user
 *
 * @return string
 */
function osc_user_city() 
{
	return (string)osc_user_field("s_city");
}
/**
 * Gets city area of current user
 *
 * @return string
 */
function osc_user_city_area() 
{
	return (string)osc_user_field("s_city_area");
}
/**
 * Gets address of current user
 *
 * @return address
 */
function osc_user_address() 
{
	return (string)osc_user_field("s_address");
}
/**
 * Gets postal zip of current user
 *
 * @return string
 */
function osc_user_zip() 
{
	return (string)osc_user_field("s_zip");
}
/**
 * Gets latitude of current user
 *
 * @return float
 */
function osc_user_latitude() 
{
	return (float)osc_user_field("d_coord_lat");
}
/**
 * Gets longitude of current user
 *
 * @return float
 */
function osc_user_longitude() 
{
	return (float)osc_user_field("d_coord_long");
}
/**
 * Gets number of items validated of current user
 *
 * @return int
 */
function osc_user_items_validated() 
{
	return (int)osc_user_field("i_items");
}
/**
 * Gets number of comments validated of current user
 *
 * @return int
 */
function osc_user_comments_validated() 
{
	return osc_user_field("i_comments");
}

/**
 * Gets a specific field from current alert
 *
 * @param array $field
 * @return mixed
 */
function osc_alert_field($field) 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return osc_field($view->_current('alerts'), $field, '');
}
/**
 * Gets next alert if there is, else return null
 *
 * @return array
 */
function osc_has_alerts() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	$result = $view->_next('alerts');
	$alert = osc_alert();
	$view->assign("items", isset($alert['items']) ? $alert['items'] : array());
	return $result;
}
/**
 * Gets number of alerts in array alerts
 * @return int
 */
function osc_count_alerts() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return (int)$view->_count('alerts');
}
/**
 * Gets current alert fomr view
 *
 * @return array
 */
function osc_alert() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return $view->_current('alerts');
}
/**
 * Gets search field of current alert
 *
 * @return string
 */
function osc_alert_search() 
{
	return (string)osc_alert_field('s_search');
}
/**
 * Gets secret of current alert
 * @return string
 */
function osc_alert_secret() 
{
	return (string)osc_alert_field('s_secret');
}
/**
 * Gets the search object of a specific alert
 *
 * @return Search
 */
function osc_alert_search_object() 
{
	return osc_unserialize(base64_decode(osc_alert_field('s_search')));
}
/**
 * Gets next user in users array
 *
 * @return <type>
 */
function osc_prepare_user_info() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if (!$view->_exists('users')) 
	{
		$view->assign('users', array($classLoader->getClassInstance( 'Model_User' )->findByPrimaryKey(osc_item_user_id())));
	}
	return $view->_next('users');
}
