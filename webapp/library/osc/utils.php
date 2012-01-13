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
 * Remove resources from disk
 * @param <type> $id
 * @return boolean
 */
function osc_deleteResource($id) 
{
	if (is_array($id)) 
	{
		$id = $id[0];
	}
	$resource = ItemResource::newInstance()->findByPrimaryKey($id);
	if (!is_null($resource)) 
	{
		$resource_original = osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . "." . $resource['s_extension'];
		$resource_thum = osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . "_*" . "." . $resource['s_extension'];
		array_map("unlink", glob($resource_thum));
		array_map("unlink", glob($resource_original));
		osc_run_hook('delete_resource', $resource);
	}
}
/**
 * Tries to delete the directory recursivaly.
 * @return true on success.
 */
function osc_deleteDir($path) 
{
	if (!is_dir($path)) return false;
	$fd = @opendir($path);
	if (!$fd) return false;
	while ($file = @readdir($fd)) 
	{
		if ($file != '.' && $file != '..') 
		{
			if (!is_dir($path . '/' . $file)) 
			{
				if (!@unlink($path . '/' . $file)) 
				{
					closedir($fd);
					return false;
				}
				else
				{
					osc_deleteDir($path . '/' . $file);
				}
			}
			else
			{
				osc_deleteDir($path . '/' . $file);
			}
		}
	}
	closedir($fd);
	return @rmdir($path);
}
/**
 * Unpack a ZIP file into the specific path in the second parameter.
 * @return true on success.
 */
function osc_packageExtract($zipPath, $path) 
{
	if (!file_exists($path)) 
	{
		if (!@mkdir($path, 0666)) 
		{
			return false;
		}
	}
	@chmod($path, 0777);
	$zip = new ZipArchive;
	if ($zip->open($zipPath) === true) 
	{
		$zip->extractTo($path);
		$zip->close();
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_path($file) 
{
	// Sanitize windows paths and duplicated slashes
	$file = preg_replace('|/+|', '/', str_replace('\\', '/', $file));
	$plugin_path = preg_replace('|/+|', '/', str_replace('\\', '/', osc_plugins_path()));
	$file = $plugin_path . preg_replace('#^.*components\/plugins\/#', '', $file);
	return $file;
}
/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_url($file) 
{
	// Sanitize windows paths and duplicated slashes
	$dir = preg_replace('|/+|', '/', str_replace('\\', '/', dirname($file)));
	$dir = WEB_PATH . 'components/plugins/' . preg_replace('#^.*components\/plugins\/#', '', $dir) . "/";
	return $dir;
}
/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_folder($file) 
{
	// Sanitize windows paths and duplicated slashes
	$dir = preg_replace('|/+|', '/', str_replace('\\', '/', dirname($file)));
	$dir = preg_replace('#^.*components\/plugins\/#', '', $dir) . "/";
	return $dir;
}
/**
 * Serialize the data (usefull at plugins activation)
 * @return the data serialized
 */
function osc_serialize($data) 
{
	if (!is_serialized($data)) 
	{
		if (is_array($data) || is_object($data)) 
		{
			return serialize($data);
		}
	}
	return $data;
}
/**
 * Unserialize the data (usefull at plugins activation)
 * @return the data unserialized
 */
function osc_unserialize($data) 
{
	if (is_serialized($data)) 
	{ // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize($data);
	}
	return $data;
}
/**
 * Checks is $data is serialized or not
 * @return bool False if not serialized and true if it was.
 */
function is_serialized($data) 
{
	// if it isn't a string, it isn't serialized
	if (!is_string($data)) return false;
	$data = trim($data);
	if ('N;' == $data) return true;
	if (!preg_match('/^([adObis]):/', $data, $badions)) return false;
	switch ($badions[1]) 
	{
	case 'a':
	case 'O':
	case 's':
		if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) return true;
		break;

	case 'b':
	case 'i':
	case 'd':
		if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) return true;
		break;
	}
	return false;
}
/**
 * VERY BASIC
 * Perform a POST request, so we could launch fake-cron calls and other core-system calls without annoying the user
 */
function osc_doRequest($url, $_data) 
{
	if (function_exists('fputs')) 
	{
		// convert variables array to string:
		$data = array();
		while (list($n, $v) = each($_data)) 
		{
			$data[] = "$n=$v";
		}
		$data = implode('&', $data);
		// format --> test1=a&test2=b etc.
		// parse the given URL
		$url = parse_url($url);
		// extract host and path:
		$host = $url['host'];
		$path = $url['path'];
		// open a socket connection on port 80
		// use localhost in case of issues with NATs (hairpinning)
		$fp = @fsockopen($host, 80);
		if ($fp !== false) 
		{
			// send the request headers:
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "Referer: OpenSourceClassifieds\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($data) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);
			// close the socket connection:
			fclose($fp);
		}
	}
}
function osc_sendMail( array $params )
{
	if (key_exists('add_bcc', $params)) 
	{
		if (!is_array($params['add_bcc'])) 
		{
			$params['add_bcc'] = array($params['add_bcc']);
		}
	}
	require_once 'phpmailer/class.phpmailer.php';
	if (osc_mailserver_pop()) 
	{
		require_once 'phpmailer/class.pop3.php';
		$pop = new POP3();
		$pop->Authorise((isset($params['host'])) ? $params['host'] : osc_mailserver_host(), (isset($params['port'])) ? $params['port'] : osc_mailserver_port(), 30, (isset($params['username'])) ? $params['username'] : osc_mailserver_username(), (isset($params['username'])) ? $params['username'] : osc_mailserver_username(), 0);
	}
	$mail = new PHPMailer(true);
	$mail->CharSet = "utf-8";
	if (osc_mailserver_auth()) 
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
	}
	else if (osc_mailserver_pop()) 
	{
		$mail->IsSMTP();
	}
	$mail->SMTPSecure = (isset($params['ssl'])) ? $params['ssl'] : osc_mailserver_ssl();
	$mail->Username = (isset($params['username'])) ? $params['username'] : osc_mailserver_username();
	$mail->Password = (isset($params['password'])) ? $params['password'] : osc_mailserver_password();
	$mail->Host = (isset($params['host'])) ? $params['host'] : osc_mailserver_host();
	$mail->Port = (isset($params['port'])) ? $params['port'] : osc_mailserver_port();
	$mail->From = (isset($params['from'])) ? $params['from'] : osc_contact_email();
	$mail->FromName = (isset($params['from_name'])) ? $params['from_name'] : osc_page_title();
	$mail->Subject = (isset($params['subject'])) ? $params['subject'] : '';
	$mail->Body = (isset($params['body'])) ? $params['body'] : '';
	$mail->AltBody = (isset($params['alt_body'])) ? $params['alt_body'] : '';
	$to = (isset($params['to'])) ? $params['to'] : '';
	$to_name = (isset($params['to_name'])) ? $params['to_name'] : '';
	if (key_exists('add_bcc', $params)) 
	{
		foreach ($params['add_bcc'] as $bcc) 
		{
			$mail->AddBCC($bcc);
		}
	}
	if (isset($params['reply_to'])) $mail->AddReplyTo($params['reply_to']);
	if (isset($params['attachment'])) 
	{
		$mail->AddAttachment($params['attachment']);
	}
	$mail->IsHTML(true);
	$mail->AddAddress($to, $to_name);
	$mail->Send();
}
function osc_mailBeauty($text, $params) 
{
	$text = str_ireplace($params[0], $params[1], $text);
	$kwords = array('{WEB_URL}', '{WEB_TITLE}', '{CURRENT_DATE}', '{HOUR}');
	$rwords = array(osc_base_url(), osc_page_title(), date('Y-m-d H:i:s'), date('H:i'));
	$text = str_ireplace($kwords, $rwords, $text);
	return $text;
}
function osc_copy($source, $dest, $options = array('folderPermission' => 0755, 'filePermission' => 0755)) 
{
	$result = true;
	if (is_file($source)) 
	{
		if ($dest[strlen($dest) - 1] == '/') 
		{
			if (!file_exists($dest)) 
			{
				cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
			}
			$__dest = $dest . "/" . basename($source);
		}
		else
		{
			$__dest = $dest;
		}
		if (function_exists('copy')) 
		{
			$result = @copy($source, $__dest);
		}
		else
		{
			$result = osc_copyemz($source, $__dest);
		}
		@chmod($__dest, $options['filePermission']);
	}
	elseif (is_dir($source)) 
	{
		if ($dest[strlen($dest) - 1] == '/') 
		{
			if ($source[strlen($source) - 1] == '/') 
			{
				//Copy only contents
				
			}
			else
			{
				//Change parent itself and its contents
				$dest = $dest . basename($source);
				@mkdir($dest);
				@chmod($dest, $options['filePermission']);
			}
		}
		else
		{
			if ($source[strlen($source) - 1] == '/') 
			{
				//Copy parent directory with new name and all its content
				@mkdir($dest, $options['folderPermission']);
				@chmod($dest, $options['filePermission']);
			}
			else
			{
				//Copy parent directory with new name and all its content
				@mkdir($dest, $options['folderPermission']);
				@chmod($dest, $options['filePermission']);
			}
		}
		$dirHandle = opendir($source);
		$result = true;
		while ($file = readdir($dirHandle)) 
		{
			if ($file != "." && $file != "..") 
			{
				if (!is_dir($source . "/" . $file)) 
				{
					$__dest = $dest . "/" . $file;
				}
				else
				{
					$__dest = $dest . "/" . $file;
				}
				//echo "$source/$file ||| $__dest<br />";
				$data = osc_copy($source . "/" . $file, $__dest, $options);
				if ($data == false) 
				{
					$result = false;
				}
			}
		}
		closedir($dirHandle);
	}
	else
	{
		$result = true;
	}
	return $result;
}
function osc_copyemz($file1, $file2) 
{
	$contentx = @file_get_contents($file1);
	$openedfile = fopen($file2, "w");
	fwrite($openedfile, $contentx);
	fclose($openedfile);
	if ($contentx === FALSE) 
	{
		$status = false;
	}
	else
	{
		$status = true;
	}
	return $status;
}
function osc_downloadFile($sourceFile, $downloadedFile) 
{
	@set_time_limit(0);
	ini_set('display_errors', true);
	$fp = @fopen(osc_content_path() . 'downloads/' . $downloadedFile, 'w+');
	if ($fp) 
	{
		$ch = curl_init($sourceFile);
		@curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		return true;
	}
	else
	{
		return false;
	}
}
function osc_file_get_contents($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	if (!defined('CURLOPT_RETURNTRANSFER')) define('CURLOPT_RETURNTRANSFER', 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
/**
 * Check if we loaded some specific module of apache
 *
 * @param string $mod
 *
 * @return bool
 */
function apache_mod_loaded($mod) 
{
	if (function_exists('apache_get_modules')) 
	{
		$modules = apache_get_modules();
		if (in_array($mod, $modules)) 
		{
			return true;
		}
	}
	else if (function_exists('phpinfo')) 
	{
		ob_start();
		phpinfo(INFO_MODULES);
		$content = ob_get_contents();
		if (stripos($content, $mod) !== FALSE) 
		{
			return true;
		}
		ob_end_clean();
	}
	return false;
}
/**
 * Change version to param number
 *
 * @param mixed version
 */
function osc_changeVersionTo($version = null) 
{
	if ($version != null) 
	{
		Preference::newInstance()->update(array('s_value' => $version), array('s_section' => 'osclass', 's_name' => 'version'));
		//XXX: I don't know if it's really needed. Only for reload the values of the preferences
		Preference::newInstance()->toArray();
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
/**
 * Unzip's a specified ZIP file to a location
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function osc_unzip_file($file, $to) 
{
	if (!file_exists($to)) 
	{
		if (!@mkdir($to, 0766)) 
		{
			return 0;
		}
	}
	@chmod($to, 0777);
	if (!is_writable($to)) 
	{
		return 0;
	}
	if (class_exists('ZipArchive')) 
	{
		return _unzip_file_ziparchive($file, $to);
	}
	// if ZipArchive class doesn't exist, we use PclZip
	return _unzip_file_pclzip($file, $to);
}
/**
 * We assume that the $to path is correct and can be written. It unzips an archive using the PclZip library.
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function _unzip_file_ziparchive($file, $to) 
{
	$zip = new ZipArchive();
	$zipopen = $zip->open($file, 4);
	if ($zipopen !== true) 
	{
		return 2;
	}
	// The zip is empty
	if ($zip->numFiles == 0) 
	{
		return 2;
	}
	for ($i = 0; $i < $zip->numFiles; $i++) 
	{
		$file = $zip->statIndex($i);
		if (!$file) 
		{
			return -1;
		}
		if (substr($file['name'], 0, 9) === '__MACOSX/') 
		{
			continue;
		}
		if (substr($file['name'], -1) == '/') 
		{
			@mkdir($to . $file['name'], 0777);
			continue;
		}
		$content = $zip->getFromIndex($i);
		if ($content === false) 
		{
			return -1;
		}
		$fp = @fopen($to . $file['name'], 'w');
		if (!$fp) 
		{
			return -1;
		}
		@fwrite($fp, $content);
		@fclose($fp);
	}
	$zip->close();
	return 1;
}
/**
 * We assume that the $to path is correct and can be written. It unzips an archive using the PclZip library.
 *
 * @param string $zip_file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function _unzip_file_pclzip($zip_file, $to) 
{
	$archive = new PclZip($zip_file);
	if (($files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING)) == false) 
	{
		return 2;
	}
	// check if the zip is not empty
	if (count($files) == 0) 
	{
		return 2;
	}
	// Extract the files from the zip
	foreach ($files as $file) 
	{
		if (substr($file['filename'], 0, 9) === '__MACOSX/') 
		{
			continue;
		}
		if ($file['folder']) 
		{
			@mkdir($to . $file['filename'], 0777);
			continue;
		}
		$fp = @fopen($to . $file['filename'], 'w');
		if (!$fp) 
		{
			return -1;
		}
		@fwrite($fp, $file['content']);
		@fclose($fp);
	}
	return 1;
}
/**
 * Common interface to zip a specified folder to a file using ziparchive or pclzip
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function osc_zip_folder($archive_folder, $archive_name) 
{
	if (class_exists('ZipArchive')) 
	{
		return _zip_folder_ziparchive($archive_folder, $archive_name);
	}
	// if ZipArchive class doesn't exist, we use PclZip
	return _zip_folder_pclzip($archive_folder, $archive_name);
}
/**
 * Zips a specified folder to a file
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function _zip_folder_ziparchive($archive_folder, $archive_name) 
{
	$zip = new ZipArchive;
	if ($zip->open($archive_name, ZipArchive::CREATE) === TRUE) 
	{
		$dir = preg_replace('/[\/]{2,}/', '/', $archive_folder . "/");
		$dirs = array($dir);
		while (count($dirs)) 
		{
			$dir = current($dirs);
			$zip->addEmptyDir(str_replace(ABS_PATH, '', $dir));
			$dh = opendir($dir);
			while (false !== ($_file = readdir($dh))) 
			{
				if ($_file != '.' && $_file != '..') 
				{
					if (is_file($dir . $_file)) 
					{
						$zip->addFile($dir . $_file, str_replace(ABS_PATH, '', $dir . $_file));
					}
					elseif (is_dir($dir . $_file)) 
					{
						$dirs[] = $dir . $_file . "/";
					}
				}
			}
			closedir($dh);
			array_shift($dirs);
		}
		$zip->close();
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * Zips a specified folder to a file
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function _zip_folder_pclzip($archive_folder, $archive_name) 
{
	$zip = new PclZip($archive_name);
	if ($zip) 
	{
		$dir = preg_replace('/[\/]{2,}/', '/', $archive_folder . "/");
		$v_dir = osc_base_path();
		$v_remove = $v_dir;
		// To support windows and the C: root you need to add the
		// following 3 lines, should be ignored on linux
		if (substr($v_dir, 1, 1) == ':') 
		{
			$v_remove = substr($v_dir, 2);
		}
		$v_list = $zip->create($v_dir, PCLZIP_OPT_REMOVE_PATH, $v_remove);
		if ($v_list == 0) 
		{
			return false;
		}
		return true;
	}
	else
	{
		return false;
	}
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
function osc_check_dir_writable($dir = ABS_PATH) 
{
	clearstatcache();
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			if ($file != "." && $file != "..") 
			{
				if (is_dir(str_replace("//", "/", $dir . "/" . $file))) 
				{
					if (str_replace("//", "/", $dir) == (ABS_PATH . "components/themes")) 
					{
						if ($file == "modern" || $file == "index.php") 
						{
							$res = osc_check_dir_writable(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/plugins")) 
					{
						if ($file == "google_maps" || $file == "google_analytics" || $file == "index.php") 
						{
							$res = osc_check_dir_writable(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/languages")) 
					{
						if ($file == "en_US" || $file == "index.php") 
						{
							$res = osc_check_dir_writable(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/downloads")) 
					{
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/uploads")) 
					{
					}
					else
					{
						$res = osc_check_dir_writable(str_replace("//", "/", $dir . "/" . $file));
						if (!$res) 
						{
							return false;
						};
					}
				}
				else
				{
					return is_writable(str_replace("//", "/", $dir . "/" . $file));
				}
			}
		}
		closedir($dh);
	}
	return true;
}
function osc_change_permissions($dir = ABS_PATH) 
{
	clearstatcache();
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			if ($file != "." && $file != "..") 
			{
				if (is_dir(str_replace("//", "/", $dir . "/" . $file))) 
				{
					if (!is_writable(str_replace("//", "/", $dir . "/" . $file))) 
					{
						$res = @chmod(str_replace("//", "/", $dir . "/" . $file), 0777);
					}
					if (!$res) 
					{
						return false;
					};
					if (str_replace("//", "/", $dir) == (ABS_PATH . "components/themes")) 
					{
						if ($file == "modern" || $file == "index.php") 
						{
							$res = osc_change_permissions(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/plugins")) 
					{
						if ($file == "google_maps" || $file == "google_analytics" || $file == "index.php") 
						{
							$res = osc_change_permissions(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/languages")) 
					{
						if ($file == "en_US" || $file == "index.php") 
						{
							$res = osc_change_permissions(str_replace("//", "/", $dir . "/" . $file));
							if (!$res) 
							{
								return false;
							};
						}
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/downloads")) 
					{
					}
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "components/uploads")) 
					{
					}
					else
					{
						$res = osc_change_permissions(str_replace("//", "/", $dir . "/" . $file));
						if (!$res) 
						{
							return false;
						};
					}
				}
				else
				{
					if (!is_writable(str_replace("//", "/", $dir . "/" . $file))) 
					{
						return @chmod(str_replace("//", "/", $dir . "/" . $file), 0777);
					}
					else
					{
						return true;
					}
				}
			}
		}
		closedir($dh);
	}
	return true;
}
function osc_save_permissions($dir = ABS_PATH) 
{
	$perms = array();
	$perms[$dir] = fileperms($dir);
	clearstatcache();
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			if ($file != "." && $file != "..") 
			{
				if (is_dir(str_replace("//", "/", $dir . "/" . $file))) 
				{
					$res = osc_save_permissions(str_replace("//", "/", $dir . "/" . $file));
					foreach ($res as $k => $v) 
					{
						$perms[$k] = $v;
					}
				}
				else
				{
					$perms[str_replace("//", "/", $dir . "/" . $file) ] = fileperms(str_replace("//", "/", $dir . "/" . $file));
				}
			}
		}
		closedir($dh);
	}
	return $perms;
}
function osc_prepare_price($price) 
{
	return $price / 1000000;
}
/**
 * Recursive glob function
 *
 * @param string $pattern
 * @param string $flags
 * @param string $path
 * @return array of files
 */
function rglob($pattern, $flags = 0, $path = '') 
{
	if (!$path && ($dir = dirname($pattern)) != '.') 
	{
		if ($dir == '\\' || $dir == '/') $dir = '';
		return rglob(basename($pattern), $flags, $dir . '/');
	}
	$paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	$files = glob($path . $pattern, $flags);
	foreach ($paths as $p) $files = array_merge($files, rglob($pattern, $flags, $p . '/'));
	return $files;
}
