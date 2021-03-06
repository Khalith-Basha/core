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
	$resource = ClassLoader::getInstance()->getClassInstance( 'Model_ItemResource' )->findByPrimaryKey($id);
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
 * Unzip's a specified ZIP file to a location
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function osc_unzip_file( $file, $to ) 
{
	if( !file_exists( $to ) )
	{
		if( !@mkdir( $to, 0777 ) )
		{
			throw new Exception( 'Destination folder "' . $to . '" does not exist and could not be created.' );
		}
	}
	if( !is_writable( $to ) )
	{
		throw new Exception( 'Destination folder "' . $to . '" is not writable.' );
	}

	return _unzip_file_ziparchive( $file, $to );
}
/**
 * We assume that the $to path is correct and can be written. It unzips an archive using the PclZip library.
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function _unzip_file_ziparchive( $file, $to ) 
{
	$zip = new ZipArchive();
	$zipopen = $zip->open( $file, ZIPARCHIVE::CHECKCONS );
	if( true !== $zipopen )
	{
		throw new Exception( 'Unable to open ZIP file: ' . $file . ' - Code: ' . $zipopen );
	}
	if( 0 === $zip->numFiles )
	{
		return true;
	}
	for( $i = 0; $i < $zip->numFiles; $i++ )
	{
		$file = $zip->statIndex( $i );
		if (!$file) 
		{
			return -1;
		}
		$filePath = $to . DIRECTORY_SEPARATOR . $file['name'];
		if ( substr($file['name'], -1) == '/' && !file_exists( $filePath ) ) 
		{
			mkdir( $filePath, 0777);
			continue;
		}
		$content = $zip->getFromIndex($i);
		if ($content === false) 
		{
			return -1;
		}
		if( !is_dir( $filePath ) )
		{
			$fp = fopen( $filePath, 'w');
			if (!$fp) 
			{
				return -1;
			}
			fwrite($fp, $content);
			fclose($fp);
		}
	}
	$zip->close();
	return true;
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
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "/components/languages")) 
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
					else if (str_replace("//", "/", $dir) == (ABS_PATH . "/components/languages")) 
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


