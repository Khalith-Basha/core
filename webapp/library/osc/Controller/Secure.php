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

abstract class Controller_Secure extends Controller_Default
{
	public function __construct() 
	{
		parent::__construct();
		if (!$this->isLogged()) 
		{
			$this->logout();
			$this->showAuthFailPage();
		}
	}

	abstract public function isLogged();
	abstract public function showAuthFailPage();

	public function setGranting($grant) 
	{
		$this->grant = $grant;
	}

	public function logout() 
	{
		$this->getSession()->destroy();
	}
}

