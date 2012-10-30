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
 * Alerts DAO
 */
class Model_Alerts extends DAO
{
	/**
	 *
	 */
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_alerts');
		// $this->setPrimaryKey('') ; // no primary key in preference table
		$array_fields = array('s_email', 'fk_i_user_id', 's_search', 's_secret', 'b_active', 'e_type');
		$this->setFields($array_fields);
	}
	/**
	 * Searches for user alerts, given an user id.
	 * If user id not exist return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $userId
	 * @return array
	 */
	function findByUser($userId) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('fk_i_user_id', $userId);
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
	 * Searches for user alerts, given an user id.
	 * If user id not exist return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $email
	 * @return array
	 */
	function findByEmail($email) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('s_email', $email);
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
	 * Searches for alerts, given a type.
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $type
	 * @return array
	 */
	function findByType($type) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('e_type', $type);
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
	 * Searches for alerts, given a type group by s_search.
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $type
	 * @param bool $active
	 * @return array
	 */
	function findByTypeGroup($type, $active = FALSE) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('e_type', $type);
		if ($active) 
		{
			$this->dbCommand->where('b_active', 1);
		}
		$this->dbCommand->groupBy('s_search');
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
	 * Searches for alerts, given a type group and a s_search.
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $search
	 * @param string $type
	 * @return array
	 *
	 * WARNIGN doble where!
	 */
	function findBySearchAndType($search, $type) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('e_type' => $type, 's_search' => $search);
		$this->dbCommand->where('e_type', $type);
		$this->dbCommand->where('s_search', $search);
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
	// a.s_email, a.fk_i_user_id @TODO
	
	/**
	 * Searches for users, given a type group and a s_search.
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $search
	 * @param string $type
	 * @param bool $active
	 * @return array
	 */
	function findUsersBySearchAndType($search, $type, $active = FALSE) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('e_type', $type);
		$this->dbCommand->where('s_search', $search);
		if ($active) 
		{
			$this->dbCommand->where('b_active', 1);
		}
		$this->dbCommand->groupBy('s_search');
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
	 * Searches for alerts, given a type group and an user id
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param int $userId
	 * @param string $type
	 * @return array
	 */
	function findByUserByType($userId, $type) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('e_type' => $type, 'fk_i_user_id' => $userId);
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
	 * Searches for alerts, given a type group and an email
	 * If type don't match return empty array.
	 *
	 * @access public
	 * @since unknown
	 * @param string $email
	 * @param string $type
	 * @return array
	 */
	function findByEmailByType($email, $type) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('e_type' => $type, 's_email' => $email);
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
	 * Create a new alert
	 *
	 * @access public
	 * @since unknown
	 * @param int $userid
	 * @param string $email
	 * @param string $alert
	 * @param string $secret
	 * @param string $type
	 * @return bool on success
	 */
	function createAlert($userid, $email, $alert, $secret, $type = 'DAILY') 
	{
		$results = 0;
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('s_search', $alert);
		if ($userid == 0 || $userid == null) 
		{
			$this->dbCommand->where('fk_i_user_id', 0);
			$this->dbCommand->where('s_email', $email);
		}
		else
		{
			$this->dbCommand->where('fk_i_user_id', $userid);
		}
		$results = $this->dbCommand->get();
		if ($results->numRows() == 0) 
		{
			return $this->dbCommand->insert($this->getTableName(), array('fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => $type, 's_secret' => $secret));
		}
		return false;
	}
	/**
	 * Activate an alert
	 *
	 * @access public
	 * @since unknown
	 * @param string $email
	 * @param string $secret
	 * @return mixed false on fail, int of num. of affected rows
	 */
	function activate($email, $secret) 
	{
		return $this->dbCommand->update($this->getTableName(), array('b_active' => 1), array('s_email' => $email, 's_secret' => $secret));
	}
}
