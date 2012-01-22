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
class CAdminEmail extends AdminSecBaseModel
{
	private $emailManager;
	function __construct() 
	{
		parent::__construct();
		$this->emailManager = ClassLoader::getInstance()->getClassInstance( 'Model_Page' );
	}
	public function doModel() 
	{
		parent::doModel();
		$this->getView()->_exportVariableToView("prefLocale", osc_current_admin_locale());
		$this->getView()->_exportVariableToView("emails", $this->emailManager->listAll(1));
		osc_current_admin_theme_path( "emails/index.php" );
	$this->getSession()->_clearVariables();
	}
}
