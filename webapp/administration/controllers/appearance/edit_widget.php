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
class CAdminAppearance extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = Params::getParam('id');
		$widgetModel = $this->getClassLoader()
			->getClassInstance( 'Model_Widget' );
		$widget = $widgetModel->findByPrimaryKey($id);
		$this->getView()->assign("widget", $widget);
		$this->doView('appearance/add_widget.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$widgetModel = $this->getClassLoader()
			->getClassInstance( 'Model_Widget' );
		$res = $widgetModel->update(array('s_description' => Params::getParam('description'), 's_content' => Params::getParam('content')), array('pk_i_id' => Params::getParam('id')));
		if ($res) 
		{
			$this->getSession()->addFlashMessage( _m('Widget updated correctly'), 'admin' );
		}
		else
		{
			$this->getSession()->addFlashMessage( _m('Widget cannot be updated correctly'), 'admin' );
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=appearance&action=widgets");
	}
}

