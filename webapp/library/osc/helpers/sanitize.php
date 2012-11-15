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
 * Helper Sanitize
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Sanitize a website URL.
 *
 * @param string $value value to sanitize
 * @return string sanitized
 */
function osc_sanitize_url($value) 
{
	if (!function_exists('filter_var')) 
	{
		return preg_replace('|([^a-zA-Z0-9\$\-\_\.\+!\*\'\(\),{}\|\^~\[\]`"#%;\/\?:@=<>\\\&]*)|', '', $value);
	}
	else
	{
		return filter_var($value, FILTER_SANITIZE_URL);
	}
}
/**
 * Sanitize capitalization for a string.
 * Capitalize first letter of each name.
 * If all-caps, remove all-caps.
 *
 * @param string $value value to sanitize
 * @return string sanitized
 */
function osc_sanitize_name($value) 
{
	return ucwords(osc_sanitize_allcaps(trim($value)));
}
/**
 * Sanitize string that's all-caps
 *
 * @param string $value value to sanitize
 * @return string sanitized
 */
function osc_sanitize_allcaps($value) 
{
	if (preg_match("/^([A-Z][^A-Z]*)+$/", $value) && !preg_match("/[a-z]+/", $value)) 
	{
		$value = ucfirst(strtolower($value));
	}
	return $value;
}
/**
 * Sanitize number (with no periods)
 *
 * @param string $value value to sanitize
 * @return string sanitized
 */
function osc_sanitize_int($value) 
{
	if (!preg_match("/^[0-9]*$/", $value)) 
	{
		return (int)$value;
	}
	return $value;
}
/**
 * Format phone number. Supports 10-digit with extensions,
 * and defaults to international if cannot match US number.
 *
 * @param string $value value to sanitize
 * @return string sanitized
 */
function osc_sanitize_phone($value) 
{
	if (empty($value)) return;
	// Remove strings that aren't letter and number.
	$value = preg_replace("/[^a-z0-9]/", "", strtolower($value));
	// Remove 1 from front of number.
	if (preg_match("/^([0-9]{11})/", $value) && $value[0] == 1) 
	{
		$value = substr($value, 1);
	}
	// Check for phone ext.
	if (!preg_match("/^[0-9]$/", $value)) 
	{
		$value = preg_replace("/^([0-9]{10})([a-z]+)([0-9]+)/", "$1ext$3", $value); // Replace 'x|ext|extension' with 'ext'.
		list($value, $ext) = explode("ext", $value); // Split number & ext.
		
	}
	// Add dashes: ___-___-____
	if (strlen($value) == 7) 
	{
		$value = preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $value);
	}
	else if (strlen($value) == 10) 
	{
		$value = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $value);
	}
	return ($ext) ? $value . " x" . $ext : $value;
}

/**
 * Validate the text with a minimum of non-punctuation characters (international)
 *
 * @param string $value
 * @param integer $count
 * @param boolean $required
 * @return boolean
 */
function osc_validate_text($value = '', $count = 1, $required = true) 
{
	if ($required || $value) 
	{
		if (!preg_match("/([\p{L}][^\p{L}]*){" . $count . "}/i", strip_tags($value))) 
		{
			return false;
		}
	}
	return true;
}
/**
 * Validate one or more numbers (no periods)
 *
 * @param string $value
 * @return boolean
 */
function osc_validate_int($value) 
{
	if (preg_match("/^[0-9]+$/", $value)) 
	{
		return true;
	}
	return false;
}
/**
 * Validate one or more numbers (no periods), must be more than 0.
 *
 * @param string $value
 * @return boolean
 */
function osc_validate_nozero($value) 
{
	if (preg_match("/^[0-9]+$/", $value) && $value > 0) 
	{
		return true;
	}
	return false;
}
/**
 * Validate $value is a number or a numeric string
 *
 * @param string $value
 * @param boolean $required
 * @return boolean
 */
function osc_validate_number($value = null, $required = false) 
{
	if ($required || strlen($value) > 0) 
	{
		if (!is_numeric($value)) 
		{
			return false;
		}
	}
	return true;
}
/**
 * Validate $value is a number phone,
 * with $count lenght
 *
 * @param string $value
 * @param int $count
 * @param boolean $required
 * @return boolean
 */
function osc_validate_phone($value = null, $count = 10, $required = false) 
{
	if ($required || strlen($value) > 0) 
	{
		if (!preg_match("/([\p{Nd}][^\p{Nd}]*){" . $count . "}/i", strip_tags($value))) 
		{
			return false;
		}
	}
	return true;
}
/**
 * Validate if $value is more than $min
 *
 * @param string $value
 * @param int $min
 * @return boolean
 */
function osc_validate_min($value = null, $min = 6) 
{
	if (strlen($value) < $min) 
	{
		return false;
	}
	return true;
}
/**
 * Validate if $value is less than $max
 * @param string $value
 * @param int $max
 * @return boolean
 */
function osc_validate_max($value = null, $max = 255) 
{
	if (strlen($value) > $max) 
	{
		return false;
	}
	return true;
}
/**
 * Validate if $value belongs at range between min to max
 * @param string $value
 * @param int $min
 * @param int $max
 * @return boolean
 */
function osc_validate_range($value, $min = 6, $max = 255) 
{
	if (strlen($value) >= $min && strlen($value) <= $max) 
	{
		return true;
	}
	return false;
}
/**
 * Validate if exist $city, $region, $country in db
 *
 * @param string $city
 * @param string $region
 * @param string $country
 * @return boolean
 */
function osc_validate_location($city, $sCity, $region, $sRegion, $country, $sCountry) 
{
	if (osc_validate_nozero($city) && osc_validate_nozero($region) && osc_validate_text($country, 2)) 
	{
		$data = ClassLoader::getInstance()->getClassInstance( 'Model_Country' )->findByCode($country);
		$countryId = $data['pk_c_code'];
		if ($countryId) 
		{
			$data = Region::newInstance()->findByPrimaryKey($region);
			$regionId = $data['pk_i_id'];
			if ($data['b_active'] == 1) 
			{
				$data = City::newInstance()->findByPrimaryKey($city);
				if ($data['b_active'] == 1 && $data['fk_i_region_id'] == $regionId && strtolower($data['fk_c_country_code']) == strtolower($countryId)) 
				{
					return true;
				}
			}
		}
	}
	else if (osc_validate_nozero($region) && osc_validate_text($country, 2) && $sCity != "") 
	{
		return true;
	}
	else if ($sRegion != "" && osc_validate_text($country, 2) && $sCity != "") 
	{
		return true;
	}
	else if ($sRegion != "" && $sCountry != "" && $sCity != "") 
	{
		return true;
	}
	return false;
}
/**
 * Validate if exist category $value and is enabled in db
 *
 * @param string $value
 * @return boolean
 */
function osc_validate_category($value) 
{
	if (osc_validate_nozero($value)) 
	{
		$data = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->findByPrimaryKey($value);
		if (isset($data['b_enabled']) && $data['b_enabled'] == 1) 
		{
			if (osc_selectable_parent_categories()) 
			{
				return true;
			}
			else
			{
				if ($data['fk_i_parent_id'] != null) 
				{
					return true;
				}
			}
		}
	}
	return false;
}
/**
 * Validate if $value url is a valid url.
 * Check header response to validate.
 *
 * @param string $value
 * @param boolean $required
 * @return boolean
 */
function osc_validate_url($value, $required = false) 
{
	if ($required || strlen($value) > 0) 
	{
		$value = osc_sanitize_url($value);
		if (!function_exists('filter_var')) 
		{
			$success = preg_match('|^(http\:\/\/[a-zA-Z0-9_\-]+(?:\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,4}(?:\/[a-zA-Z0-9_]+)*(?:\/[a-zA-Z0-9_]+\.[a-zA-Z]{2,4}(?:\?[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)?)?(?:\&[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)*)$|', $value, $m);
		}
		else
		{
			$success = filter_var($value, FILTER_VALIDATE_URL);
		}
		if ($success) 
		{
			@$headers = get_headers($value);
			if (!preg_match('/^HTTP\/\d\.\d\s+(200|301|302)/', $headers[0])) 
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	return true;
}
/**
 * Validate time between two items added/comments
 *
 * @param string $type
 * @return boolean
 */
function osc_validate_spam_delay($type = 'item') 
{
	if ($type == 'item') 
	{
		$delay = osc_item_spam_delay();
		$saved_as = 'last_submit_item';
	}
	else
	{
		$delay = osc_comment_spam_delay();
		$saved_as = 'last_submit_comment';
	}

	$cookie = new \Cuore\Input\Cookie;
	if ((ClassLoader::getInstance()->getClassInstance( 'Session' )->_get($saved_as) + $delay) > time() || ($cookie->getValue($saved_as) + $delay) > time()) 
	{
		return false;
	}
	return true;
}
/**
 * Validate an email address
 * Source: http://www.linuxjournal.com/article/9585?page=0,3
 *
 * @param string $email
 * @param boolean $required
 * @return boolean
 */
function osc_validate_email($email, $required = true) 
{
	if ($required || strlen($email) > 0) 
	{
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) 
		{
			return false;
		}
		else
		{
			$domain = substr($email, $atIndex + 1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) 
			{
				return false;
			}
			else if ($domainLen < 1 || $domainLen > 255) 
			{
				return false;
			}
			else if ($local[0] == '.' || $local[$localLen - 1] == '.') 
			{
				return false;
			}
			else if (preg_match('/\\.\\./', $local)) 
			{
				return false;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) 
			{
				return false;
			}
			else if (preg_match('/\\.\\./', $domain)) 
			{
				return false;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&amp;`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) 
			{
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) 
				{
					return false;
				}
			}
			return true;
		}
	}
	return true;
}
/**
 * validate username, accept letters plus underline, without separators
 *
 * @param $value
 * @param $min
 */
function osc_validate_username($value, $min = 1) 
{
	if (strlen($value) >= $min && preg_match('/^[A-Za-z0-9_]+$/', $value)) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
