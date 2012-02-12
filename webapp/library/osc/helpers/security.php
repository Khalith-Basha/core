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
/**
 * Helper Security
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Creates a random password.
 * @param int password $length. Default to 8.
 * @return string
 */
function osc_genRandomPassword($length = 8) 
{
	$dict = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
	shuffle($dict);
	$pass = '';
	for ($i = 0; $i < $length; $i++) $pass.= $dict[rand(0, count($dict) - 1) ];
	return $pass;
}
function osc_check_recaptcha() 
{
	require_once 'recaptchalib.php';
	if (Params::getParam("recaptcha_challenge_field") != '') 
	{
		$resp = recaptcha_check_answer(osc_recaptcha_private_key(), $_SERVER["REMOTE_ADDR"], Params::getParam("recaptcha_challenge_field"), Params::getParam("recaptcha_response_field"));
		return $resp->is_valid;
	}
	return false;
}
/**
 * Print recaptcha html, if $section = "recover_password"
 * set 'recover_time' at session.
 *
 * @param  string $section
 * @return void
 */
function osc_show_recaptcha($section = '') 
{
	if (osc_recaptcha_public_key()) 
	{
		require_once 'recaptchalib.php';
		switch ($section) 
		{
		case ('recover_password'):
			$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
			$time = $session->_get('recover_time');
			if ((time() - $time) <= 1200) 
			{
				echo recaptcha_get_html(osc_recaptcha_public_key()) . "<br />";
			}
			break;

		default:
			echo recaptcha_get_html(osc_recaptcha_public_key());
			break;
		}
	}
}

