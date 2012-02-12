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
class Params
{
	static function getParam($param, $htmlencode = false) 
	{
		if( empty( $param ) )
			return null;
		if (!isset($_REQUEST[$param]))
			return null;
		$value = $_REQUEST[$param];
		if (!is_array($value)) 
		{
			if ($htmlencode) 
			{
				return htmlspecialchars(stripslashes($value), ENT_QUOTES);
			}
		}
		if (get_magic_quotes_gpc()) 
		{
			$value = strip_slashes_extended($value);
		}
		return $value;
	}
	static function existParam($param) 
	{
		if( empty( $param ) )
			return false;

		return isset( $_REQUEST[$param] );
	}
	static function getFiles($param) 
	{
		if (isset($_FILES[$param])) 
		{
			return ($_FILES[$param]);
		}
		else
		{
			return null;
		}
	}
	static function getParamsAsArray( $what = null ) 
	{
		switch ($what) 
		{
		case ("get"):
			return (strip_slashes_extended($_GET));
			break;

		case ("post"):
			return (strip_slashes_extended($_POST));
			break;

		case ("cookie"):
			return ($_COOKIE);
			break;

		default:
			return (strip_slashes_extended($_REQUEST));
			break;
		}
	}
	static function setParam($key, $value) 
	{
		$_REQUEST[$key] = $value;
		$_GET[$key] = $value;
		$_POST[$key] = $value;
	}
}

function strip_slashes_extended($array) 
{
	if (is_array($array)) 
	{
		foreach ($array as $k => & $v) 
		{
			$v = strip_slashes_extended($v);
		}
	}
	else
	{
		$array = stripslashes($array);
	}
	return $array;
}

