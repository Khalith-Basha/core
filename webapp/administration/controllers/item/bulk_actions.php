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
class CAdminItem extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->itemManager = ClassLoader::getInstance()->getClassInstance( 'Model_Item' );
		switch (Params::getParam('bulk_actions')) 
		{
		case 'enable_all':
			$id = Params::getParam('id');
			$value = 1;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_enabled' => $value), array('pk_i_id' => $_id));
						$item = $this->itemManager->findByPrimaryKey($_id);
						CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->increaseNumItems($item['fk_i_category_id']);
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been enabled', '%d items have been enabled', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'disable_all':
			$id = Params::getParam('id');
			$value = 0;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_enabled' => $value), array('pk_i_id' => $_id));
						$item = $this->itemManager->findByPrimaryKey($_id);
						CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->decreaseNumItems($item['fk_i_category_id']);
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been disabled', '%d items have been disabled', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'activate_all':
			$id = Params::getParam('id');
			$value = 1;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_active' => $value), array('pk_i_id' => $_id));
						$item = $this->itemManager->findByPrimaryKey($_id);
						CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->increaseNumItems($item['fk_i_category_id']);
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been activated', '%d items have been activated', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'deactivate_all':
			$id = Params::getParam('id');
			$value = 0;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_active' => $value), array('pk_i_id' => $_id));
						$item = $this->itemManager->findByPrimaryKey($_id);
						CategoryClassLoader::getInstance()->getClassInstance( 'Stats' )->decreaseNumItems($item['fk_i_category_id']);
					}
					$this->getSession()->addFlashMessage( sprintf(_m('%d item has been deactivated', '%d items have been deactivated', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'premium_all':
			$id = Params::getParam('id');
			$value = 1;
			try
			{
				if ($id) 
				{
					$count = count($id);
					$mItems = new ItemActions(true);
					foreach ($id as $_id) 
					{
						$mItems->premium($_id);
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been marked as premium', '%d items have been marked as premium', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'depremium_all':
			$id = Params::getParam('id');
			$value = 0;
			try
			{
				if ($id) 
				{
					$count = count($id);
					$mItems = new ItemActions(true);
					foreach ($id as $_id) 
					{
						$mItems->premium($_id, false);
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d change has been made', '%d changes have been made', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'spam_all':
			$id = Params::getParam('id');
			$value = 1;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_spam' => $value), array('pk_i_id' => $_id));
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been marked as spam', '%d items have been marked as spam', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'despam_all':
			$id = Params::getParam('id');
			$value = 0;
			try
			{
				if ($id) 
				{
					$count = count($id);
					foreach ($id as $_id) 
					{
						$this->itemManager->update(array('b_spam' => $value), array('pk_i_id' => $_id));
					}
					$this->getSession()->addFlashMessage( sprintf(_mn('%d change have been made', '%d changes have been made', $count), $count), 'admin' );
				}
			}
			catch(Exception $e) 
			{
				$this->getSession()->addFlashMessage( sprintf(_m('Error: %s'), $e->getMessage()), 'admin', 'ERROR' );
			}
			break;

		case 'delete_all':
			$id = Params::getParam('id');
			$success = false;
			if ($id != '') 
			{
				$count = count($id);
				foreach ($id as $i) 
				{
					if ($i) 
					{
						$item = $this->itemManager->findByPrimaryKey($i);
						$mItems = new ItemActions(true);
						$success = $mItems->delete($item['s_secret'], $item['pk_i_id']);
					}
				}
			}
			if ($success) 
			{
				$this->getSession()->addFlashMessage( sprintf(_mn('%d item has been deleted', '%d items have been deleted', $count), $count), 'admin' );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('The item couldn\'t be deleted'), 'admin', 'ERROR' );
			}
			$this->redirectTo(osc_admin_base_url(true) . "?page=item");
			break;
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=item");
	}
}
