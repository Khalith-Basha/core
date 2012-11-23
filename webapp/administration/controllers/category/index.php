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
class CAdminCategory extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$categoryModel = new \Osc\Model\Category;

		$view = $this->getView();
		$view->addJavaScript( 'jquery.jstree.js' );
		$view->addJavaScript( 'categories_index.js' );
		$view->assign( 'categories', $categoryModel->toTreeAll() );
		$this->doView( 'categories/index' );
	}
}

