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

require_once 'osc/Database/Collection.php';

class Database_Exception extends Exception
{
	private $sql;

	public function setSql( $sql )
	{
		$this->sql = $sql;
	}

	public function getSql()
	{
		return $this->sql;
	}
}

/**
 * Database command object
 *
 * @package OpenSourceClassifieds
 * @subpackage Database
 */
class Database_Command
{
	/**
	 * Database connection object to OpenSourceClassifieds database
	 *
	 * @access private
	 * @private mysqli
	 */
	private $connId;
	/**
	 *
	 * @var array
	 */
	private $aSelect;
	/* var $aDistinct ; */
	/**
	 *
	 * @private array
	 */
	private $aFrom;
	/**
	 *
	 * @private array
	 */
	private $aJoin;
	/**
	 *
	 * @private array
	 */
	private $aWhere;
	/**
	 *
	 * @private array
	 */
	private $aLike;
	/**
	 *
	 * @private array
	 */
	private $aGroupby;
	/**
	 *
	 * @private array
	 */
	private $aHaving;
	/* private $aKeys ; */
	/**
	 *
	 * @var mixed
	 */
	private $aLimit;
	/**
	 *
	 * @var mixed
	 */
	private $aOffset;
	/**
	 *
	 * @var mixed
	 */
	private $aOrder;
	/**
	 *
	 * @var array
	 */
	private $aOrderby;
	/**
	 *
	 * @var array
	 */
	private $aSet;
	/**
	 *
	 * @var array
	 */
	private $aWherein;

	/**
	 * Initializate variables
	 *
	 * @param mysqli $connId
	 */
	public function __construct( mysqli &$connId )
	{
		$this->connId = & $connId;
		$this->aSelect = array();
		$this->aFrom = array();
		$this->aJoin = array();
		$this->aWhere = array();
		$this->aLike = array();
		$this->aGroupby = array();
		$this->aHaving = array();
		$this->aLimit = false;
		$this->aOffset = false;
		$this->aOrder = false;
		$this->aOrderby = array();
		$this->aWherein = array();
	}
	/**
	 * Set SELECT clause
	 *
	 * @access public
	 * @param mixed $select It can be a string or array
	 * @return DBCommandClass
	 */
	function select($select = '*') 
	{
		if (is_string($select)) 
		{
			$select = explode(',', $select);
		}
		foreach ($select as $s) 
		{
			$s = trim($s);
			if ($s != '') 
			{
				$this->aSelect[] = $s;
			}
		}
		return $this;
	}
	/**
	 * Set FROM clause
	 *
	 * @param mixed $from It can be a string or array
	 * @return DBCommandClass
	 */
	function from($from) 
	{
		if (!is_array($from)) 
		{
			if (strpos($from, ',') !== false) 
			{
				$from = explode(',', $from);
			}
			else
			{
				$from = array($from);
			}
		}
		foreach ($from as $f) 
		{
			$this->aFrom[] = $f;
		}
		return $this;
	}
	/**
	 * Set JOIN clause
	 *
	 * @access public
	 * @param string $table
	 * @param string $cond
	 * @param string $type It can be: LEFT, RIGHT, OUTER, INNER, LEFT OUTER or RIGHT OUTER
	 * @return DBCommandClass
	 */
	function join($table, $cond, $type = '') 
	{
		if ($type != '') 
		{
			$type = strtoupper(trim($type));
			if (!in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'))) 
			{
				$type = '';
			}
			else
			{
				$type.= ' ';
			}
		}
		$join = $type . ' JOIN ' . $table . ' ON ' . $cond;
		$this->aJoin[] = $join;
		return $this;
	}
	/**
	 * Set WHERE clause using OR operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @return DBCommandClass
	 */
	function where($key, $value = null) 
	{
		return $this->_where($key, $value, 'AND ');
	}
	/**
	 * Set WHERE clause using OR operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @return DBCommandClass
	 */
	function orWhere($key, $value = null) 
	{
		return $this->_where($key, $value, 'OR ');
	}
	/**
	 * Set WHERE clause
	 *
	 * @access private
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $type
	 * @return DBCommandClass
	 */
	function _where($key, $value = null, $type = 'AND ') 
	{
		if (!is_array($key)) 
		{
			$key = array($key => $value);
		}
		foreach ($key as $k => $v) 
		{
			$prefix = (count($this->aWhere) > 0) ? $type : '';
			if (!$this->_hasOperator($k)) 
			{
				$k.= ' =';
			}
			if (!is_null($v)) 
			{
				$v = ' ' . $this->escape($v);
			}
			$prefix . $k . $v;
			$this->aWhere[] = $prefix . $k . $v;
		}
		return $this;
	}
	/**
	 * Set WHERE IN clause using AND operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass
	 */
	function whereIn($key = null, $values = null) 
	{
		return $this->_whereIn($key, $values, false, 'AND ');
	}
	/**
	 * Set WHERE IN clause using OR operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass
	 */
	function orWhereIn($key = null, $values = null) 
	{
		return $this->_whereIn($key, $values, false, 'OR ');
	}
	/**
	 * Set WHERE NOT IN clause using AND operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass
	 */
	function whereNotIn($key = null, $values = null) 
	{
		return $this->_whereIn($key, $values, true, 'AND ');
	}
	/**
	 * Set WHERE NOT IN clause using OR operator
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass
	 */
	function orWhereNotIn($key = null, $values = null) 
	{
		return $this->_whereIn($key, $values, true, 'OR ');
	}
	/**
	 * Set WHERE IN clause
	 *
	 * @access private
	 * @param mixed $key
	 * @param mixed $values
	 * @param bool $not
	 * @param string $type
	 * @return DBCommandClass
	 */
	function _whereIn($key = null, $values = null, $not = false, $type = 'AND ') 
	{
		if (!is_array($values)) 
		{
			$values = array($values);
		}
		$not = ($not) ? ' NOT' : '';
		foreach ($values as $value) 
		{
			$this->aWherein[] = $this->escape($value);
		}
		$prefix = (count($this->aWhere) > 0) ? $type : '';
		$whereIn = $key . $not . ' IN (' . implode(', ', $this->aWherein) . ') ';
		$this->aWhere[] = $whereIn;
		$this->aWherein = array();
		return $this;
	}
	/**
	 * Set LIKE clause
	 *
	 * @access public
	 * @param type $field
	 * @param type $match
	 * @param type $side
	 * @return DBCommandClass
	 */
	function like($field, $match = '', $side = 'both') 
	{
		return $this->_like($field, $match, 'AND ', $side);
	}
	/**
	 * Set NOT LIKE clause using AND operator
	 *
	 * @access public
	 * @param string $field
	 * @param string $match
	 * @param string $side
	 * @return DBCommandClass
	 */
	function notLike($field, $match = '', $side = 'both') 
	{
		return $this->_like($field, $match, 'AND ', $side, 'NOT');
	}
	/**
	 * Set LIKE clause using OR operator
	 *
	 * @access public
	 * @param string $field
	 * @param string $match
	 * @param type $side
	 * @return string
	 */
	function orLike($field, $match = '', $side = 'both') 
	{
		return $this->_like($field, $match, 'OR ', $side);
	}
	/**
	 * Set NOT LIKE clause using OR operator
	 *
	 * @access public
	 * @param string $field
	 * @param string $match
	 * @param string $side
	 * @return DBCommandClass
	 */
	function orNotLike($field, $match = '', $side = 'both') 
	{
		return $this->_like($field, $match, 'OR ', $side, 'NOT');
	}
	/**
	 * Set LIKE clause
	 *
	 * @access private
	 * @param string $field
	 * @param string $match
	 * @param string $type Types: AND, OR
	 * @param string $side Options: before, after, both
	 * @param string $not Two possibilities: blank or NOT
	 * @return DBCommandClass
	 */
	function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '') 
	{
		$likeStatement = '';
		if (!is_array($field)) 
		{
			$field = array($field => $match);
		}
		foreach ($field as $k => $v) 
		{
			$prefix = (count($this->aLike) == 0) ? '' : $type;
			$v = $this->escapeStr($v, true);
			switch ($side) 
			{
			case 'before':
				$likeStatement = "$prefix $k $not LIKE '%$v'";
				break;

			case 'after':
				$likeStatement = "$prefix $k $not LIKE '$v%'";
				break;

			default:
				$likeStatement = "$prefix $k $not LIKE '%$v%'";
				break;
			}
			$this->aLike[] = $likeStatement;
		}
		return $this;
	}
	/**
	 * Fields for GROUP BY clause
	 *
	 * @access public
	 * @param mixed $by
	 * @return DBCommandClass
	 */
	function groupBy($by) 
	{
		if (is_string($by)) 
		{
			$by = explode(',', $by);
		}
		foreach ($by as $val) 
		{
			$val = trim($val);
			if ($val != '') 
			{
				$this->aGroupby[] = $val;
			}
		}
		return $this;
	}
	/**
	 *
	 * @param type $key
	 * @param type $value
	 * @return type
	 */
	function having($key, $value = '') 
	{
		return $this->_having($key, $value, 'AND ');
	}
	/**
	 *
	 * @param type $key
	 * @param type $value
	 * @return type
	 */
	function orHaving($key, $value = '') 
	{
		return $this->_having($key, $value, 'OR ');
	}
	/**
	 *
	 * @param type $key
	 * @param type $value
	 * @param type $type
	 */
	function _having($key, $value = '', $type = 'AND ') 
	{
		if (!is_array($key)) 
		{
			$key = array($key => $value);
		}
		foreach ($key as $k => $v) 
		{
			$prefix = (count($this->aHaving) == 0) ? '' : $type;
			if (!$this->_hasOperator($k)) 
			{
				$k.= ' = ';
			}
			$v = ' ' . $this->escapeStr($v);
			$this->aHaving[] = $prefix . $k . $v;
		}
	}
	/**
	 * Set ORDER BY clause
	 *
	 * @access public
	 * @param string $orderby
	 * @param string $direction Accepted directions: random, asc, desc
	 */
	function orderBy($orderby, $direction = '') 
	{
		if (strtolower($direction) == 'random') 
		{
			$direction = ' RAND()';
		}
		elseif (trim($direction) != '') 
		{
			$direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'))) ? ' ' . $direction : ' ASC';
		}
		$this->aOrderby[] = $orderby . $direction;
		return $this;
	}
	/**
	 * Set LIMIT clause
	 *
	 * @access public
	 * @param int $value
	 * @param int $offset
	 * @return DBCommandClass
	 */
	function limit($value, $offset = '') 
	{
		$this->aLimit = $value;
		if ($offset != '') 
		{
			$this->aOffset = $offset;
		}
		return $this;
	}
	/**
	 * Set the offset in the LIMIT clause
	 *
	 * @access public
	 * @param int $offset
	 * @return DBCommandClass
	 */
	function offset($offset) 
	{
		$this->aOffset = $offset;
		return $this;
	}
	/**
	 * Create the INSERT sql and perform the query
	 *
	 * @access public
	 * @param mixed $table
	 * @param mixed $set
	 * @return boolean
	 */
	function insert($table = '', $set = null) 
	{
		if (!is_null($set)) 
		{
			$this->set($set);
		}
		if (count($this->aSet) == 0) 
		{
			return false;
		}
		if ($table == '') 
		{
			if (!isset($this->aFrom[0])) 
			{
				return false;
			}
			$table = $this->aFrom[0];
		}
		$sql = $this->_insert($table, array_keys($this->aSet), array_values($this->aSet));
		$this->_resetWrite();
		return $this->query($sql);
	}
	/**
	 * Create the INSERT sql string
	 *
	 * @access private
	 * @param string $table
	 * @param array $keys
	 * @param array $values
	 * @return string
	 */
	function _insert( $table, array $keys, array $values ) 
	{
		$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
		return $sql;
	}
	/**
	 * Create the REPLACE INTO sql and perform the query
	 *
	 * @access public
	 * @param mixed $table
	 * @param mixed $set
	 * @return boolean
	 */
	function replace($table = '', $set = null) 
	{
		if (!is_null($set)) 
		{
			$this->set($set);
		}
		if (count($this->aSet) == 0) 
		{
			return false;
		}
		if ($table == '') 
		{
			if (!isset($this->aFrom[0])) 
			{
				return false;
			}
			$table = $this->aFrom[0];
		}
		$sql = $this->_replace($table, array_keys($this->aSet), array_values($this->aSet));
		$this->_resetWrite();
		return $this->query($sql);
	}
	/**
	 * Create the REPLACE INTO sql string
	 *
	 * @access private
	 * @param string $table
	 * @param array $key
	 * @param array $values
	 * @return string
	 */
	function _replace($table, $keys, $values) 
	{
		return 'REPLACE INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
	}
	/**
	 * Create the UPDATE sql and perform the query
	 *
	 * @access public
	 * @param mixed $table
	 * @param mixed $set
	 * @param mixed $where
	 * @return mixed
	 */
	function update($table = '', $set = null, $where = null) 
	{
		if (!is_null($set)) 
		{
			$this->set($set);
		}
		if (count($this->aSet) == 0) 
		{
			return false;
		}
		if ($table == '') 
		{
			if (!isset($this->aFrom[0])) 
			{
				return false;
			}
			$table = $this->aFrom[0];
		}
		if ($where != null) 
		{
			$this->where($where);
		}
		$sql = $this->_update($table, $this->aSet, $this->aWhere);
		$this->_resetWrite();
		$result = $this->query($sql);
		if ($result == false) 
		{
			return false;
		}
		return $this->affectedRows();
	}
	/**
	 * Create the UPDATE sql string
	 *
	 * @access private
	 * @param string $table
	 * @param array $values
	 * @param array $where
	 * @return string
	 */
	function _update($table, $values, $where) 
	{
		foreach ($values as $k => $v) 
		{
			$valstr[] = $k . ' = ' . $v;
		}
		$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $valstr);
		$sql.= ($where != '' && count($where) > 0) ? " WHERE " . implode(" ", $where) : '';
		return $sql;
	}
	/**
	 * Create the DELETE sql and perform the query
	 *
	 * @access public
	 * @param mixed $table
	 * @param mixed $where
	 * @return mixed
	 */
	function delete( $table = '', $where = '' )
	{
		if ($table == '') 
		{
			if (!isset($this->aFrom[0])) 
			{
				return false;
			}
			$table = $this->aFrom[0];
		}
		if ($where != null) 
		{
			$this->where($where);
		}
		if (count($this->aWhere) == 0 && count($this->aWherein) == 0 && count($this->aLike) == 0) 
		{
			return false;
		}
		$sql = $this->_delete($table, $this->aWhere, $this->aLike);
		$this->_resetWrite();
		$result = $this->query($sql);
		if ($result == false) 
		{
			return false;
		}
		return $this->affectedRows();
	}
	/**
	 * Create the DELETE sql string
	 *
	 * @access private
	 * @param string $table
	 * @param array $where
	 * @param array $like
	 * @return string
	 */
	function _delete($table, $where, $like) 
	{
		$conditions = '';
		if (count($where) > 0 || count($like) > 0) 
		{
			$conditions = "\nWHERE ";
			$conditions.= implode("\n", $where);
			if (count($where) > 0 && count($like) > 0) 
			{
				$conditions.= ' AND ';
			}
			$conditions.= implode("\n", $like);
		}
		$sql = 'DELETE FROM ' . $table . $conditions;
		return $sql;
	}
	/**
	 * Compile the select sql string and perform the query. Quick method for
	 * getting the rows of one table
	 *
	 * @access public
	 * @param mixed $table
	 * @param mixed $limit
	 * @param mixed $offset
	 * @return mixed
	 */
	function get($table = '', $limit = null, $offset = null) 
	{
		if ($table != '') 
		{
			$this->from($table);
		}
		if (!is_null($limit)) 
		{
			$this->limit($limit, $offset);
		}
		$sql = $this->_getSelect();
		$result = $this->query($sql);
		$this->_resetSelect();
		return $result;
	}
	/**
	 * Performs a query on the database
	 *
	 * @access public
	 * @param string $sql
	 * @return mixed
	 */
	public function query( $sql )
	{
		if( empty( $this->connId ) )
		{
			throw new Exception( 'Wrong connection resource' );
		}

		if( empty( $sql ) )
		{
			throw new Exception( 'Empty SQL' );
		}

		$resultId = $this->connId->query( $sql );
		if( false === $resultId )
		{
			$dbException = new Database_Exception( $this->connId->error );
			$dbException->setSql( $sql );
			throw $dbException;
		}

		if( $this->isWriteType( $sql ) === true )
		{
			return $resultId;
		}

		$rs = new Database_Collection();
		$rs->connId = $this->connId;
		$rs->resultId = $resultId;

		return $rs;
	}
	/**
	 * Execute queries sql. We replace TABLE_PREFIX for the real prefix: DB_TABLE_PREFIX
	 * The executions is stopped if some query throws an error.
	 *
	 * @access public
	 * @param string $sql
	 * @return boolean true if it's succesful, false if not
	 */
	function importSQL( $sql )
	{
		$sql = str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql);
		$sql = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', ($sql));
		$queries = explode(';', $sql);
		foreach ($queries as $q) 
		{
			$q = trim( $q );
			if( empty( $q ) )
				continue;

			if (!$this->query($q)) 
			{
				return false;
			}
		}
		return true;
	}
	/**
	 * Set aSet array
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @return DBCommandClass
	 */
	function set($key, $value = '', $escape = true) 
	{
		if (!is_array($key)) 
		{
			$key = array($key => $value);
		}
		foreach ($key as $k => $v) 
		{
			if ($escape) 
			{
				$this->aSet[$k] = $this->escape($v);
			}
			else
			{
				$this->aSet[$k] = $v;
			}
		}
		return $this;
	}
	/**
	 * Create SELECT sql statement
	 *
	 * @access private
	 * @return string
	 */
	function _getSelect() 
	{
		$sql = 'SELECT ';
		// "SELECT" portion of the query
		if (count($this->aSelect) == 0) 
		{
			$sql.= '*';
		}
		else
		{
			$sql.= implode(', ', $this->aSelect);
		}
		// "FROM" portion of the query
		if (count($this->aFrom) > 0) 
		{
			$sql.= "\nFROM ";
			if (!is_array($this->aFrom)) 
			{
				$this->a_from = array($this->aFrom);
			}
			$sql.= '(' . implode(', ', $this->aFrom) . ')';
		}
		// "JOIN" portion of the query
		if (count($this->aJoin) > 0) 
		{
			$sql.= "\n";
			$sql.= implode("\n", $this->aJoin);
		}
		// "WHERE" portion of the query
		if (count($this->aWhere) > 0 || count($this->aLike) > 0) 
		{
			$sql.= "\n";
			$sql.= "WHERE ";
		}
		$sql.= implode("\n", $this->aWhere);
		// "LIKE" portion of the query
		if (count($this->aLike) > 0) 
		{
			if (count($this->aWhere) > 0) 
			{
				$sql.= "\nAND";
			}
			$sql.= implode("\n", $this->aLike);
		}
		// "GROUP BY" portion of the query
		if (count($this->aGroupby) > 0) 
		{
			$sql.= "\nGROUP BY ";
			$sql.= implode(', ', $this->aGroupby);
		}
		// "HAVING" portion of the query
		if (count($this->aHaving) > 0) 
		{
			$sql.= "\nHAVING ";
			$sql.= implode(', ', $this->aHaving);
		}
		// "ORDER BY" portion of the query
		if (count($this->aOrderby) > 0) 
		{
			$sql.= "\nORDER BY ";
			$sql.= implode(', ', $this->aOrderby);
			if ($this->aOrder !== false) 
			{
				$sql.= ($this->aOrder == 'desc') ? ' DESC' : ' ASC';
			}
		}
		// "LIMIT" portion of the query
		if (is_numeric($this->aLimit)) 
		{
			$sql.= "\n";
			$sql.= "LIMIT " . $this->aLimit;
			if ($this->aOffset > 0) 
			{
				$sql.= ", " . $this->aOffset;
			}
		}
		return $sql;
	}
	/**
	 * Gets the number of affected rows in a previous MySQL operation
	 *
	 * @access public
	 * @return int
	 */
	function affectedRows() 
	{
		return $this->connId->affected_rows;
	}
	/**
	 * Get the ID generated from the previous INSERT operation
	 *
	 * @access public
	 * @return mixed
	 */
	function insertedId() 
	{
		return $this->connId->insert_id;
	}
	/**
	 * Check if the string has an operator
	 *
	 * @access private
	 * @param string $str
	 * @return bool
	 */
	function _hasOperator($str) 
	{
		$str = trim($str);
		return preg_match('/(\s|<|>|!|=|is null|is not null)/i', $str);
	}
	/**
	 * Check if the sql is a select
	 *
	 * @access private
	 * @param string $sql
	 * @return bool
	 */
	function isSelectType($sql) 
	{
		return preg_match('/^\s*"?(SELECT)\s+/i', $sql);
	}
	/**
	 * Check if the sql is a write type such as INSERT, UPDATE, UPDATE...
	 *
	 * @access private
	 * @param string $sql
	 * @return bool
	 */
	function isWriteType($sql) 
	{
		return preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql);
	}
	/**
	 * Add the apostrophe if it's an string; 0 or 1 if it's a number; NULL
	 *
	 * @access private
	 * @param string $str
	 * @return string
	 */
	function escape($str) 
	{
		if (is_string($str)) 
		{
			$str = "'" . $this->escapeStr($str) . "'";
		}
		elseif (is_bool($str)) 
		{
			$str = ($str === false) ? 0 : 1;
		}
		elseif (is_null($str)) 
		{
			$str = 'NULL';
		}
		return $str;
	}
	/**
	 * Escape the string if it's necessary
	 *
	 * @access private
	 * @param string $str
	 * @return string
	 */
	function escapeStr($str, $like = false) 
	{
		if (is_object($this->connId)) 
		{
			$str = $this->connId->real_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}
		if ($like) 
		{
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}
		return $str;
	}
	/**
	 * Reset variables used in write sql: aSet, aFrom, aWhere, aLike, aOrderby, aLimit, aOrder
	 *
	 * @access private
	 */
	function _resetWrite() 
	{
		$aReset = array('aSet' => array(), 'aFrom' => array(), 'aWhere' => array(), 'aLike' => array(), 'aOrderby' => array(), 'aLimit' => false, 'aOrder' => false);
		$this->_resetRun($aReset);
	}
	/**
	 * Reset variables used in select sql: aSelect, aFrom, aJoin, aWhere, aLike, aGroupby, aHaving,
	 * aOrderby, aWherein, aLimit, aOffset, aOrder
	 *
	 * @access private
	 */
	function _resetSelect() 
	{
		$aReset = array('aSelect' => array(), 'aFrom' => array(), 'aJoin' => array(), 'aWhere' => array(), 'aLike' => array(), 'aGroupby' => array(), 'aHaving' => array(), 'aOrderby' => array(), 'aWherein' => array(), 'aLimit' => false, 'aOffset' => false, 'aOrder' => false);
		$this->_resetRun($aReset);
	}
	/**
	 * Initializate $aReset variables
	 *
	 * @access private
	 * @param array $aReset
	 */
	function _resetRun($aReset) 
	{
		foreach ($aReset as $item => $defaultValue) 
		{
			$this->$item = $defaultValue;
		}
	}
}
