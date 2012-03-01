<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

class CAdminAjax extends Controller_Administration
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = $this->getInput()->getInteger( 'id' );

		$response = array();

		try
		{
			$categoryManager = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
			$categoryManager->deleteByPrimaryKey($id);

			$response = array( 'ok' => 'Saved' );
		}
		catch( Exception $e )
		{
			$response = array( 'error' => __('Error while deleting') );
		}

		echo json_encode( $response );
	}
}

