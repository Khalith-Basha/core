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
namespace Osc\Model;
/**
 * Model database for ItemComment table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class ItemComment extends \DAO
{
	/**
	 * Set data related to t_item_comment table
	 */
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_item_comment');
		$this->setPrimaryKey('pk_i_id');
		$array_fields = array('pk_i_id', 'fk_i_item_id', 'dt_pub_date', 's_title', 's_author_name', 's_author_email', 's_body', 'b_enabled', 'b_active', 'b_spam', 'fk_i_user_id');
		$this->setFields($array_fields);
	}
	/**
	 * Searches for comments information, given an item id.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $id
	 * @return array
	 */
	function findByItemIDAll($id) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('fk_i_item_id', $id);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else
		{
			return $result->result();
		}
	}
	/**
	 * Searches for comments information, given an item id, page and comments per page.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $id
	 * @param integer $page
	 * @param integer $comments_per_page
	 * @return array
	 */
	function findByItemID($id, $page = null, $commentsPerPage = null) 
	{
		$result = array();
		if ($page == null) 
		{
			$page = osc_item_comments_page();
		}
		if ($page == '') 
		{
			$page = 0;
		}
		if ($commentsPerPage == null) 
		{
			$commentsPerPage = osc_comments_per_page();
		}
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('fk_i_item_id' => $id, 'b_active' => 1, 'b_enabled' => 1);
		$this->dbCommand->where($conditions);
		if (($page !== 'all') || ($commentsPerPage > 0)) 
		{
			$this->dbCommand->limit(($page * $commentsPerPage), $commentsPerPage);
		}
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else
		{
			return $result->result();
		}
	}
	/**
	 * Return total of comments, given an item id. (active & enabled)
	 *
	 * @access public
	 * @since unknown
	 * @deprecated since 2.3
	 * @see ItemComment::totalComments
	 * @param integer $id
	 * @return integer
	 */
	function total_comments($id) 
	{
		return $this->totalComments($id);
	}
	/**
	 * Return total of comments, given an item id. (active & enabled)
	 *
	 * @access public
	 * @since 2.3
	 * @param integer $id
	 * @return integer
	 */
	function totalComments($id) 
	{
		$this->dbCommand->select('count(pk_i_id) as total');
		$this->dbCommand->from($this->getTableName());
		$conditions = array('fk_i_item_id' => $id, 'b_active' => 1, 'b_enabled' => 1);
		$this->dbCommand->where($conditions);
		$this->dbCommand->groupBy('fk_i_item_id');
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else if ($result->numRows() === 0) 
		{
			return 0;
		}
		else
		{
			$total = $result->row();
			return $total['total'];
		}
	}
	/**
	 * Searches for comments information, given an user id.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $id
	 * @return array
	 */
	function findByAuthorID($id) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('fk_i_user_id' => $id, 'b_active' => 1, 'b_enabled' => 1);
		$this->dbCommand->where($conditions);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else
		{
			return $result->result();
		}
	}
	/**
	 * Searches for comments information, given an user id.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $itemId
	 * @return array
	 */
	function getAllComments($itemId = null) 
	{
		$this->dbCommand->select('c.*');
		$this->dbCommand->from($this->getTableName() . ' c');
		$this->dbCommand->from(DB_TABLE_PREFIX . 'item i');
		$conditions = array();
		if (is_null($itemId)) 
		{
			$conditions = 'c.fk_i_item_id = i.pk_i_id';
		}
		else
		{
			$conditions = array('i.pk_i_id' => $itemId, 'c.fk_i_item_id' => $itemId);
		}
		$this->dbCommand->where($conditions);
		$this->dbCommand->orderBy('c.dt_pub_date', 'DESC');
		$aux = $this->dbCommand->get();
		$comments = $aux->result();
		return $this->extendData($comments);
	}
	/**
	 * Searches for last comments information, given a limit of comments.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $num
	 * @return array
	 */
	function getLastComments($num) 
	{
		if (!intval($num)) return false;
		$lang = osc_current_user_locale();
		$this->dbCommand->select('c.*,c.s_title as comment_title, d.s_title');
		$this->dbCommand->from($this->getTableName() . ' c');
		$this->dbCommand->join(DB_TABLE_PREFIX . 'item i', 'i.pk_i_id = c.fk_i_item_id');
		$this->dbCommand->join(DB_TABLE_PREFIX . 't_item_description d', 'd.fk_i_item_id = c.fk_i_item_id');
		$this->dbCommand->orderBy('c.pk_i_id', 'DESC');
		$this->dbCommand->limit(0, $num);
		$result = $this->dbCommand->get();
		return $result->result();
	}
	/**
	 * Extends an array of comments with title / description / what
	 *
	 * @access private
	 * @since unknown
	 * @param array $items
	 * @return array
	 */
	private function extendData($items) 
	{
		$prefLocale = osc_current_user_locale();
		$results = array();
		foreach ($items as $item) 
		{
			$this->dbCommand->select();
			$this->dbCommand->from(DB_TABLE_PREFIX . 't_item_description');
			$this->dbCommand->where('fk_i_item_id', $item['fk_i_item_id']);
			$aux = $this->dbCommand->get();
			$descriptions = $aux->result();
			$item['locale'] = array();
			foreach ($descriptions as $desc) 
			{
				$item['locale'][$desc['fk_c_locale_code']] = $desc;
			}
			if (isset($item['locale'][$prefLocale])) 
			{
				$item['s_title'] = $item['locale'][$prefLocale]['s_title'];
				$item['s_description'] = $item['locale'][$prefLocale]['s_description'];
				$item['s_what'] = $item['locale'][$prefLocale]['s_what'];
			}
			else
			{
				$data = current($item['locale']);
				$item['s_title'] = $data['s_title'];
				$item['s_description'] = $data['s_description'];
				$item['s_what'] = $data['s_what'];
				unset($data);
			}
			$results[] = $item;
		}
		return $results;
	}
}
