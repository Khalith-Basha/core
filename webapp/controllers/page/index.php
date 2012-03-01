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

class CWebPage extends Controller_Cacheable
{
	public function getCacheKey()
	{
		$input = $this->getInput();
		$pageId = $input->getInteger( 'id' );
		$slug = $input->getString( 'slug' );
		return sprintf( 'page-%d-%s', $pageId, $slug );
	}

	public function getCacheExpiration()
	{
		return 10800;
	}

	public function renderView( HttpRequest $req, HttpResponse $res )
	{
		$pagesModel = $this->getClassLoader()->getClassInstance( 'Model_Page' );

		$input = $this->getInput();
		$id = $input->getInteger( 'id' );
		$slug = $input->getString( 'slug' );

		$locale = osc_current_user_locale();
		
		if( !empty( $id ) )
			$page = $pagesModel->findByIdLocale( $id, $locale );
		elseif( !empty( $slug ) )
			$page = $pagesModel->findBySlugLocale( $slug, $locale );

		$view = $this->getView();

		if( is_null( $page ) || 1 == $page['b_indelible'] )
		{
			return array(
				'statusCode' => 404,
				'viewContent' => $this->getView()->render( 'error/404' )
			);
		}
		else if( file_exists( osc_themes_path() . osc_theme() . '/pages/' . $page['s_internal_name'] . '.php' ) )
		{
			return $view->render( 'pages/' . $page['s_internal_name'] );
		}
		else
		{
			$view->setTitle( $page['s_title'] . ' - ' . osc_page_title() );
			$view->setMetaDescription( osc_highlight( strip_tags( $page['s_text'] ), 140 ) );
			$view->assign( 'page', $page );
			return $view->render( 'page' );
		}
	}
}

