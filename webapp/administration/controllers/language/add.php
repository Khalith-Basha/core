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
class CAdminLanguage extends AdminSecBaseModel
{
	private $localeManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->localeManager = OSCLocale::newInstance();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		osc_current_admin_theme_path( 'languages/add.php' );
		Session::newInstance()->_clearVariables();
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$filePackage = Params::getFiles('package');
		if( isset($filePackage['size']) && $filePackage['size'] != 0) 
		{
			$path = osc_translations_path();
			$status = osc_unzip_file($filePackage['tmp_name'], $path);
			switch ($status) 
			{
			case (0):
				$msg = _m('The translation folder is not writable');
				osc_add_flash_error_message($msg, 'admin');
				break;

			case (1):
				if (osc_checkLocales()) 
				{
					$msg = _m('The language has been installed correctly');
					osc_add_flash_ok_message($msg, 'admin');
				}
				else
				{
					$msg = _m('There was a problem adding the language');
					osc_add_flash_error_message($msg, 'admin');
				}
				break;

			case (2):
				$msg = _m('The zip file is not valid');
				osc_add_flash_error_message($msg, 'admin');
				break;

			case (3):
				$msg = _m('No file was uploaded');
				osc_add_flash_warning_message($msg, 'admin');
				$this->redirectTo(osc_admin_base_url(true) . "?page=language&action=add");
				break;

			case (-1):
			default:
				$msg = _m('There was a problem adding the language');
				osc_add_flash_error_message($msg, 'admin');
				break;
			}
		}
		$this->redirectTo( osc_admin_base_url( true ) . '?page=language' );
	}
}
