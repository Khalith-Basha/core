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
class CWebItem extends Controller_Default
{
	private $itemModel;
	private $user;
	private $userId;
	function __construct() 
	{
		parent::__construct();

		$this->userId = null;
		$this->user = null;

		if (osc_is_web_user_logged_in()) 
		{
			$this->userId = osc_logged_user_id();
			$this->user = ClassLoader::getInstance()->getClassInstance( 'Model_User' )->findByPrimaryKey($this->userId);
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$classLoader = $this->getClassLoader();
		$this->itemModel = $this->getClassLoader()->getClassInstance( 'Model_Item' );
		$locales = $this->getClassLoader()->getClassInstance( 'Model_Locale' )->listAllEnabled();
		$this->view->assign('locales', $locales);
		if (Params::getParam('id') == '') 
		{
			$this->redirectToBaseUrl();
		}
		if (Params::getParam('lang') != '') 
		{
			$this->getSession()->_set('userLocale', Params::getParam('lang'));
		};
		$item = $this->itemModel->findByPrimaryKey(Params::getParam('id'));
		// if item doesn't exist redirect to base url
		if( count($item) == 0 )
		{
			$this->getSession()->addFlashMessage( _m('This item doesn\'t exist'), 'ERROR' );
			$this->redirectToBaseUrl();
		}

		$this->loadCommentsData( $item );

		if ($item['b_active'] != 1) 
		{
			if ($this->userId == $item['fk_i_user_id']) 
			{
				$this->getSession()->addFlashMessage( _m('The item hasn\'t been validated. Please validate it in order to show it to the rest of users'), 'ERROR' );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('This item hasn\'t been validated'), 'ERROR' );
				$this->redirectToBaseUrl();
			}
		}
		else if ($item['b_enabled'] == 0) 
		{
			$this->getSession()->addFlashMessage( _m('The item has been suspended'), 'ERROR' );
			$this->redirectToBaseUrl();
		}
		$mStats = ClassLoader::getInstance()->getClassInstance( 'Model_ItemStats' );
		$mStats->increase('i_num_views', $item['pk_i_id']);
		foreach ($item['locale'] as $k => $v) 
		{
			$item['locale'][$k]['s_title'] = osc_apply_filter('item_title', $v['s_title']);
			$item['locale'][$k]['s_description'] = nl2br(osc_apply_filter('item_description', $v['s_description']));
		}
		$this->view->assign('items', array($item));
		osc_run_hook('show_item', $item);

		$view = $this->getView();
		$this->assign( 'item', $item );
		$this->view->setTitle( $item['locale'][osc_current_user_locale()]['s_title'] );
		$this->view->addJavaScript( '/static/scripts/contact-form.js' );
		$this->view->addJavaScript( '/static/scripts/comment-form.js' );
		$view->setMetaDescription( osc_item_category( $item ) . ', ' . osc_highlight( strip_tags( osc_item_description( $item ) ), 140) . '..., ' . osc_item_category( $item ) );

		$metafields = $classLoader->getClassInstance( 'Model_Item' )->metaFields( osc_item_id( $item ) );
		$view->assign( 'metafields', $metafields );

		$itemResourceManager = $classLoader->getClassInstance( 'Model_ItemResource' );
		$view->assign('resources', $itemResourceManager->getAllResources( osc_item_id( $item ) ) );
		
		$view->assign('user', $classLoader->getClassInstance( 'Model_User' )->findByPrimaryKey( osc_item_user_id( $item ) ) );

		$classLoader->loadFile( 'helpers/security' );

		echo $view->render( 'item/index' );
	}

	protected function loadCommentsData( array $item )
	{
		$classLoader = ClassLoader::getInstance();
		$view = $this->getView();

		$comments = $classLoader->getClassInstance( 'Model_ItemComment' )
			->findByItemID( $item['pk_i_id'], osc_item_comments_page(), osc_comments_per_page() );
		$view->assign( 'comments', $comments );

		if( 0 === osc_comments_per_page() || 'all' === osc_item_comments_page() )
			return;

		$itemUrls = $classLoader->getClassInstance( 'Url_Item' );

		$pagination = $classLoader->getClassInstance( 'Pagination', false );
		$pagination->setNumItems( osc_item_total_comments( $item ) );
		$pagination->setUrlTemplate( $itemUrls->osc_item_comments_url( $item, $pagination::PLACEHOLDER ) );
		$pagination->setSelectedPage( osc_item_comments_page() );
		$view->assign( 'commentsPagination', $pagination );
	}
}

