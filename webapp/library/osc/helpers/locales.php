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
/**
 * Helper Locales
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets locale generic field
 *
 * @param $field
 * @param $locale
 * @return string
 */
function osc_locale_field($field, $locale = '') 
{
	return osc_field(osc_locale(), $field, $locale);
}
/**
 * Gets locale object
 *
 * @return array
 */
function osc_locale() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Default' );
	if ($view->varExists('locales')) 
	{
		$locale = $view->_current('locales');
	}
	elseif ($view->varExists('locale')) 
	{
		$locale = $view->_get('locale');
	}
	else
	{
		$locale = null;
	}
	return ($locale);
}
/**
 * Gets list of locales
 *
 * @return array
 */
function osc_get_locales() 
{
	return ClassLoader::getInstance()
		->getClassInstance( 'Model_Locale' )
		->listAllEnabled();
}

/**
 * Private function to count locales
 *
 * @return boolean
 */
function osc_priv_count_locales() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Default' );
	return $view->countVar('locales');
}
/**
 * Reset iterator of locales
 *
 * @return void
 */
function osc_goto_first_locale() 
{
	$view = ClassLoader::getInstance()->getClassInstance( 'View_Default' );
	$view->_reset('locales');
}
/**
 * Gets number of enabled locales for website
 *
 * @return int
 */
function osc_count_web_enabled_locales() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Default' );
	if (!$view->varExists('locales')) 
	{
		$view->assign('locales', $classLoader->getClassInstance( 'Model_Locale' )->listAllEnabled());
	}
	return osc_priv_count_locales();
}
/**
 * Iterator for enabled locales for website
 *
 * @return array
 */
function osc_has_web_enabled_locales() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Default' );
	if (!$view->varExists('locales')) 
	{
		$view->assign('locales', $classLoader->getClassInstance( 'Model_Locale' )->listAllEnabled());
	}
	return $view->_next('locales');
}
/**
 * Gets current locale's code
 *
 * @return string
 */
function osc_locale_code() 
{
	return osc_locale_field("pk_c_code");
}
/**
 * Gets current locale's name
 *
 * @return string
 */
function osc_locale_name() 
{
	return osc_locale_field("s_name");
}
/**
 * Gets current locale's currency format
 *
 * @return string
 */
function osc_locale_currency_format() 
{
	$aLocales = osc_get_locales();
	$cLocale = $aLocales[0];
	foreach ($aLocales as $locale) 
	{
		if ($locale['pk_c_code'] == osc_current_user_locale()) 
		{
			$cLocale = $locale;
			break;
		}
	}
	return $cLocale['s_currency_format'];
}
/**
 * Gets current locale's decimal point
 *
 * @return string
 */
function osc_locale_dec_point() 
{
	$aLocales = osc_get_locales();
	$cLocale = $aLocales[0];
	foreach ($aLocales as $locale) 
	{
		if ($locale['pk_c_code'] == osc_current_user_locale()) 
		{
			$cLocale = $locale;
			break;
		}
	}
	return $cLocale['s_dec_point'];
}
/**
 * Gets current locale's thousands separator
 *
 * @return string
 */
function osc_locale_thousands_sep() 
{
	$aLocales = osc_get_locales();
	$cLocale = $aLocales[0];
	foreach ($aLocales as $locale) 
	{
		if ($locale['pk_c_code'] == osc_current_user_locale()) 
		{
			$cLocale = $locale;
			break;
		}
	}
	return $cLocale['s_thousands_sep'];
}
/**
 * Gets current locale's number of decimals
 *
 * @return string
 */
function osc_locale_num_dec() 
{
	$aLocales = osc_get_locales();
	$cLocale = $aLocales[0];
	foreach ($aLocales as $locale) 
	{
		if ($locale['pk_c_code'] == osc_current_user_locale()) 
		{
			$cLocale = $locale;
			break;
		}
	}
	return $cLocale['i_num_dec'];
}
/**
 * Gets list of enabled locales
 *
 * @return array
 */
function osc_all_enabled_locales_for_admin($indexed_by_pk = false) 
{
	$classLoader = ClassLoader::getInstance();
	return ($classLoader->getClassInstance( 'Model_Locale' )->listAllEnabled(true, $indexed_by_pk));
}
/**
 * Gets current locale object
 *
 * @return array
 */
function osc_get_current_user_locale() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Default' );
	$view->assign('locale', $classLoader->getClassInstance( 'Model_Locale' )->findByPrimaryKey(osc_current_user_locale()));
}
/**
 * Get the actual locale of the user.
 *
 * You get the right locale code. If an user is using the website in another language different of the default one, or
 * the user uses the default one, you'll get it.
 *
 * @return string Locale Code
 */
function osc_current_user_locale() 
{
	return osc_language();
}
/**
 * Get the actual locale of the admin.
 *
 * You get the right locale code. If an admin is using the website in another language different of the default one, or
 * the admin uses the default one, you'll get it.
 *
 * @return string Locale Code
 */
function osc_current_admin_locale() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	if ( $session->_get('adminLocale') != '') 
	{
		return $session->_get('adminLocale');
	}
	return osc_admin_language();
}

function osc_listLocales() 
{
	$languages = array();
	$codes = osc_listLanguageCodes();
	foreach ($codes as $code) 
	{
		$path = sprintf('%s/%s/index.php', osc_translations_path(), $code);
		if (file_exists($path)) 
		{
			require $path;
			$fxName = sprintf('locale_%s_info', $code);
			if (function_exists($fxName)) 
			{
				$lang = call_user_func($fxName);
				$lang['code'] = $code;
				$languages[] = $lang;
			}
		}
	}
	return $languages;
}
function osc_checkLocales() 
{
	$locales = osc_listLocales();
	foreach ($locales as $locale) 
	{
		$data = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->findByPrimaryKey($locale['code']);
		if (!is_array($data)) 
		{
			$values = array('pk_c_code' => $locale['code'], 's_name' => $locale['name'], 's_short_name' => $locale['short_name'], 's_description' => $locale['description'], 's_version' => $locale['version'], 's_author_name' => $locale['author_name'], 's_author_url' => $locale['author_url'], 's_currency_format' => $locale['currency_format'], 's_date_format' => $locale['date_format'], 's_stop_words' => $locale['stop_words'], 'b_enabled' => 0, 'b_enabled_bo' => 1);
			$result = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->insert($values);
			if (!$result) 
			{
				return false;
			}
			// inserting e-mail translations
			$path = sprintf('%s%s/mail.sql', osc_translations_path(), $locale['code']);
			if (file_exists($path)) 
			{
				$sql = file_get_contents( $path );
				// @TODO
		if( defined( 'DB_HOST' ) )
		{
			$c_db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		}

//				$conn = ClassLoader::getInstance()->getClassInstance( 'cuore_db_Connection' );
//				$c_db = $conn->getResource();
				$comm = new DBCommandClass( $c_db );
				$result = $comm->importSQL( $sql );
				if (!$result) 
				{
					return false;
				}
			}
		}
	}
	return true;
}
function osc_listLanguageCodes() 
{
	$codes = array();
	$dir = opendir(osc_translations_path());
	while ($file = readdir($dir)) 
	{
		if (preg_match('/^[a-z_]+$/i', $file)) 
		{
			$codes[] = $file;
		}
	}
	closedir($dir);
	return $codes;
}
