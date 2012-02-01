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

class CWebPage extends Controller_Cacheable
{
	public function getCacheKey()
	{
		$pageId = $this->getInput()->getInteger( 'id' );
		return 'page-' . $pageId;
	}

	public function getCacheExpiration()
	{
		return 10800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res )
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
			$view->setTitle( osc_static_page_title() . ' - ' . osc_page_title() );
			$view->setMetaDescription( osc_highlight(strip_tags(osc_static_page_text()), 140) );
			$view->assign('page', $page);
			return $view->render( 'page' );
		}
	}
}

