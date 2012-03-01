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

class Pagination
{
	const PLACEHOLDER = '___PAGE___';

	private $numItems;
	private $itemsPerPage;
	private $urlTemplate;
	private $selectedPage;

	public function __construct()
	{
		$this->numItems = 0;
		$this->selectedPage = 0;
		$this->itemsPerPage = 10;
		$this->urlTemplate = $_SERVER['QUERY_STRING'] . '&page=' . self::PLACEHOLDER;
	}

	public function setUrlTemplate( $urlTemplate )
	{
		$this->urlTemplate = $urlTemplate;
	}

	public function setNumItems( $numItems )
	{
		$this->numItems = $numItems;
	}

	public function setItemsPerPage( $itemsPerPage )
	{
		$this->itemsPerPage = $itemsPerPage;
	}

	public function getNumPages()
	{
		return intval( ceil( $this->numItems / $this->itemsPerPage ) );
	}

	public function setSelectedPage( $number )
	{
		$this->selectedPage = $number;
	}

	public function getPages()
	{
		$pages = array();
		for( $i = 0; $i < $this->getNumPages(); $i++ )
			$pages[] = array(
				'url' => $this->createPageUrl( $i ),
				'number' => ( $i + 1 ),
				'selected' => ( $i == $this->selectedPage )
			);
		return $pages;
	}

	//abstract public function createPageUrl( $pageNumber );
	public function createPageUrl( $pageNumber )
	{
		return str_replace( self::PLACEHOLDER, $pageNumber, $this->urlTemplate );
	}
}

class Pagination_Deprecated
{
	public function __construct($params = null) 
	{
		$this->class_first = isset($params['class_first']) ? $params['class_first'] : 'searchPaginationFirst';
		$this->class_last = isset($params['class_last']) ? $params['class_last'] : 'searchPaginationLast';
		$this->class_prev = isset($params['class_prev']) ? $params['class_prev'] : 'searchPaginationPrev';
		$this->class_next = isset($params['class_next']) ? $params['class_next'] : 'searchPaginationNext';
		$this->text_first = isset($params['text_first']) ? $params['text_first'] : '&laquo;';
		$this->text_last = isset($params['text_last']) ? $params['text_last'] : '&raquo';
	}
}


