<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
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
class CAdminAppearance extends AdminSecBaseModel
{
	function doModel() 
	{
		parent::doModel();
		//specific things for this class
		switch ($this->action) 
		{
		case 'widgets':
			$info = ClassLoader::getInstance()->getClassInstance( 'WebThemes' )->loadThemeInfo(osc_theme());
			$this->getView()->_exportVariableToView("info", $info);
			$this->doView('appearance/widgets.php');
			break;

		case 'add_widget':
			$this->doView('appearance/add_widget.php');
			break;

		case 'edit_widget':
			$id = Params::getParam('id');
			$widget = Widget::newInstance()->findByPrimaryKey($id);
			$this->getView()->_exportVariableToView("widget", $widget);
			$this->doView('appearance/add_widget.php');
			break;

		case 'delete_widget':
			Widget::newInstance()->delete(array('pk_i_id' => Params::getParam('id')));
			osc_add_flash_ok_message(_m('Widget removed correctly'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=appearance&action=widgets");
			break;

		case 'edit_widget_post':
			$res = Widget::newInstance()->update(array('s_description' => Params::getParam('description'), 's_content' => Params::getParam('content')), array('pk_i_id' => Params::getParam('id')));
			if ($res) 
			{
				osc_add_flash_ok_message(_m('Widget updated correctly'), 'admin');
			}
			else
			{
				osc_add_flash_ok_message(_m('Widget cannot be updated correctly'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . "?page=appearance&action=widgets");
			break;

		case 'add_widget_post':
			Widget::newInstance()->insert(array('s_location' => Params::getParam('location'), 'e_kind' => 'html', 's_description' => Params::getParam('description'), 's_content' => Params::getParam('content')));
			osc_add_flash_ok_message(_m('Widget added correctly'), 'admin');
			$this->redirectTo(osc_admin_base_url(true) . "?page=appearance&action=widgets");
			break;
		}
	}

	function doView($file) 
	{
		osc_current_admin_theme_path($file);
	$this->getSession()->_clearVariables();
	}
}
