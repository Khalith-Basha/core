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
class CAdminComment extends Controller_Administration
{
	private $itemCommentManager;
	function __construct() 
	{
		parent::__construct();
		$this->itemCommentManager = ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' );
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$id = Params::getParam('id');
		$comment = ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->findByPrimaryKey($id);
		$this->getView()->assign('comment', $comment);
		$this->doView('comments/frm.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$this->itemCommentManager->update(array('s_title' => Params::getParam('title'), 's_body' => Params::getParam('body'), 's_author_name' => Params::getParam('authorName'), 's_author_email' => Params::getParam('authorEmail')), array('pk_i_id' => Params::getParam('id')));
		osc_run_hook('edit_comment', Params::getParam('id'));
		$this->getSession()->addFlashMessage( _m('Great! We just updated your comment'), 'admin' );
		$this->redirectTo(osc_admin_base_url(true) . "?page=comment");
	}
}
