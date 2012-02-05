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
class CAdminPage extends Controller_Administration
{
	private $pageManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->pageManager = $this->getClassLoader()->getClassInstance( 'Model_Page' );
	}
	public function doModel() 
	{
		$id = Params::getParam("id");
		$page_deleted_correcty = 0;
		$page_deleted_error = 0;
		$page_indelible = 0;
		if (!is_array($id)) 
		{
			$id = array($id);
		}
		foreach ($id as $_id) 
		{
			$result = (int)$this->pageManager->deleteByPrimaryKey($_id);
			switch ($result) 
			{
			case -1:
				$page_indelible++;
				break;

			case 0:
				$page_deleted_error++;
				break;

			case 1:
				$page_deleted_correcty++;
			}
		}
		if ($page_indelible > 0) 
		{
			if ($page_indelible == 1) 
			{
				osc_add_flash_error_message(_m('One page can\'t be deleted because it is indelible'), 'admin');
			}
			else
			{
				osc_add_flash_error_message($page_indelible . ' ' . _m('pages couldn\'t be deleted because are indelible'), 'admin');
			}
		}
		if ($page_deleted_error > 0) 
		{
			if ($page_deleted_error == 1) 
			{
				osc_add_flash_error_message(_m('One page couldn\'t be deleted'), 'admin');
			}
			else
			{
				osc_add_flash_error_message($page_deleted_error . ' ' . _m('pages couldn\'t be deleted'), 'admin');
			}
		}
		if ($page_deleted_correcty > 0) 
		{
			if ($page_deleted_correcty == 1) 
			{
				osc_add_flash_ok_message(_m('One page has been deleted correctly'), 'admin');
			}
			else
			{
				osc_add_flash_ok_message($page_deleted_correcty . ' ' . _m('pages have been deleted correctly'), 'admin');
			}
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=page");
	}
}

