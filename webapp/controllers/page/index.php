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
class CWebPage extends Controller
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->pageManager = ClassLoader::getInstance()->getClassInstance( 'Model_Page' );
		$id = Params::getParam('id');
		$page = $this->pageManager->findByPrimaryKey($id);
		if ($page == false) 
		{
			$this->do404();
			return;
		}
		if ($page['b_indelible'] == 1) 
		{
			$this->do404();
			return;
		}
		if (file_exists(osc_themes_path() . osc_theme() . '/' . $page['s_internal_name'] . ".php")) 
		{
			$this->doView($page['s_internal_name'] . ".php");
		}
		else if (file_exists(osc_themes_path() . osc_theme() . '/pages/' . $page['s_internal_name'] . ".php")) 
		{
			$this->doView("pages/" . $page['s_internal_name'] . ".php");
		}
		else
		{
			if (Params::getParam('lang') != '') 
			{
				$this->getSession()->_set('userLocale', Params::getParam('lang'));
			}

			$view = $this->getView();
			$view->setTitle( $page['locale'][osc_current_user_locale()]['s_title'] );
			$view->setTitle( osc_static_page_title() . ' - ' . osc_page_title() );
			$view->assign('page', $page);
			echo $view->render( 'page' );
		}
	}
}

