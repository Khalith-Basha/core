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
/**
 * Model database for ItemResource table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class Model_ItemResource extends DAO
{
	/**
	 * Set data related to t_item_resource table
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_item_resource');
		$this->setPrimaryKey('pk_i_id');
		$this->setFields(array('pk_i_id', 'fk_i_item_id', 's_name', 's_extension', 's_content_type', 's_path'));
	}
	/**
	 * Get all resources belong to an item given its id
	 *
	 * @access public
	 * @since unknown
	 * @param int $itemId Item id
	 * @return array of resources
	 */
	function getAllResources($itemId = null) 
	{
		$this->dbCommand->select('r.*, c.pub_date');
		$this->dbCommand->from($this->getTableName() . ' r');
		$this->dbCommand->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id');
		if (!is_null($itemId)) 
		{
			$this->dbCommand->where('r.fk_i_item_id', $itemId);
		}
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return array();
		}
		return $result->result();
	}
	/**
	 * Get first resource belong to an item given it id
	 *
	 * @access public
	 * @since unknown
	 * @param int $itemId Item id
	 * @return array resource
	 */
	function getResource($itemId) 
	{
		$this->dbCommand->select($this->getFields());
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('fk_i_item_id', $itemId);
		$this->dbCommand->limit(1);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return array();
		}
		if ($result->numRows == 0) 
		{
			return array();
		}
		return $result->row();
	}
	/**
	 * Check if resource id and name exist
	 *
	 * @deprecated since 2.3
	 * @param int $resourceId
	 * @param string $code
	 * @return bool
	 */
	function getResourceSecure($resourceId, $code) 
	{
		return $this->existResource($resourceId, $code);
	}
	/**
	 * Check if resource id and name exist
	 *
	 * @access public
	 * @since unknown
	 * @param int $resourceId
	 * @param string $code
	 * @return bool
	 */
	function existResource($resourceId, $code) 
	{
		$this->dbCommand->select('COUNT(*) AS numrows');
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('pk_i_id', $resourceId);
		$this->dbCommand->where('s_name', $code);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return 0;
		}
		if ($result->numRows() != 1) 
		{
			return 0;
		}
		$row = $result->row();
		return $row['numrows'];
	}
	/**
	 * Count resouces belong to item given its id
	 *
	 * @access public
	 * @since unknown
	 * @param int $itemId Item id
	 * @return int
	 */
	function countResources($itemId = null) 
	{
		$this->dbCommand->select('COUNT(*) AS numrows');
		$this->dbCommand->from($this->getTableName());
		if (!is_null($itemId) && is_numeric($itemId)) 
		{
			$this->dbCommand->where('fk_i_item_id', $itemId);
		}
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return 0;
		}
		if ($result->numRows() != 1) 
		{
			return 0;
		}
		$row = $result->row();
		return $row['numrows'];
	}
	/**
	 * Get resources, if $itemId is set return resources belong to an item given its id,
	 * can be filtered by $start/$end and ordered by column.
	 *
	 * @access public
	 * @since unknown
	 * @param int $itemId Item id
	 * @param int $start beginig
	 * @param int $length ending
	 * @param string $order column order default='pk_i_id'
	 * @param string $type order type [DESC|ASC]
	 * @return array of resources
	 */
	function getResources($itemId = NULL, $start = 0, $length = 10, $order = 'pk_i_id', $type = 'DESC') 
	{
		if (!in_array($order, $this->getFields())) 
		{
			// order by is incorrect
			return array();
		}
		if (!in_array(strtoupper($type), array('DESC', 'ASC'))) 
		{
			// order type is incorrect
			return array();
		}
		$this->dbCommand->select('r.*, c.pub_date');
		$this->dbCommand->from($this->getTableName() . ' r');
		$this->dbCommand->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id');
		if (!is_null($itemId) && is_numeric($itemId)) 
		{
			$this->dbCommand->where('r.fk_i_item_id', $itemId);
		}
		$this->dbCommand->orderBy($order, $type);
		$this->dbCommand->limit($start);
		$this->dbCommand->offset($length);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return array();
		}
		return $result->result();
	}
	/**
	 * Delete all resources where id is in $ids
	 *
	 * @param array $ids
	 */
	public function deleteResourcesIds($ids) 
	{
		$this->dbCommand->whereIn('pk_i_id', $ids);
		return $this->dbCommand->delete($this->getTableName());
	}
	/**
	 * Return table item name
	 *
	 * @access public
	 * @since unknown
	 * @return string table name
	 */
	function getTableItemName() 
	{
		return $this->getTablePrefix() . 'item';
	}
	/**
	 * Return table description name
	 *
	 * @access public
	 * @since unknown
	 * @return string table description name
	 */
	function getTableItemDescription() 
	{
		return $this->getTablePrefix() . 't_item_description';
	}
}
