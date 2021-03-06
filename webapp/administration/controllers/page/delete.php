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
class CAdminPage extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->pageManager = $this->getClassLoader()->getClassInstance( 'Model_Page' );
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
				$this->getSession()->addFlashMessage( _m('One page can\'t be deleted because it is indelible'), 'admin', 'ERROR' );
			}
			else
			{
				$this->getSession()->addFlashMessage( $page_indelible . ' ' . _m('pages couldn\'t be deleted because are indelible'), 'admin', 'ERROR' );
			}
		}
		if ($page_deleted_error > 0) 
		{
			if ($page_deleted_error == 1) 
			{
				$this->getSession()->addFlashMessage( _m('One page couldn\'t be deleted'), 'admin', 'ERROR' );
			}
			else
			{
				$this->getSession()->addFlashMessage( $page_deleted_error . ' ' . _m('pages couldn\'t be deleted'), 'admin', 'ERROR' );
			}
		}
		if ($page_deleted_correcty > 0) 
		{
			if ($page_deleted_correcty == 1) 
			{
				$this->getSession()->addFlashMessage( _m('One page has been deleted correctly'), 'admin' );
			}
			else
			{
				$this->getSession()->addFlashMessage( $page_deleted_correcty . ' ' . _m('pages have been deleted correctly'), 'admin' );
			}
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=page");
	}
}

