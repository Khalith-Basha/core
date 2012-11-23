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
define('DB_FUNC_NOW', 'NOW()');
define('DB_CONST_TRUE', 'TRUE');
define('DB_CONST_FALSE', 'FALSE');
define('DB_CONST_NULL', 'NULL');
define('DB_CUSTOM_COND', 'DB_CUSTOM_COND');

require_once 'osc/Database/Command.php';
require_once 'Osc/Model/Default.php';

/**
 * DAO base model
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 */
class DAO extends Model
{
	/**
	 * DBCommandClass object
	 *
	 * @acces public
	 * @private DBCommandClass
	 */
	public $dbCommand;
	/**
	 * Table name
	 *
	 * @access private
	 * @since unknown
	 * @private string
	 */
	protected $tableName;
	/**
	 * Table prefix
	 *
	 * @access private
	 * @since unknown
	 * @private string
	 */
	protected $tablePrefix;
	/**
	 * Primary key of the table
	 *
	 * @access private
	 * @private string
	 */
	private $primaryKey;
	/**
	 * Fields of the table
	 *
	 * @access private
	 * @private array
	 */
	private $fields;
	/**
	 * Init connection of the database and create DBCommandClass object
	 */
	public function __construct()
	{
		parent::__construct();

		if( defined( 'DB_HOST' ) )
		{
			$data = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		}

		$this->dbCommand = new Database_Command($data);
		$this->tablePrefix = 'osc_'; // @TODO $conn->getTablePrefix();
	}
	/**
	 * Get the result match of the primary key passed by parameter
	 *
	 * @access public
	 * @since unknown
	 * @param string $value
	 * @return mixed If the result has been found, it return the array row. If not, it returns false
	 */
	public function findByPrimaryKey($value) 
	{
		$this->dbCommand->select($this->fields);
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where($this->getPrimaryKey(), $value);
		$result = $this->dbCommand->get();
		if ($result === false) 
		{
			return null;
		}
		if ($result->numRows() !== 1) 
		{
			return false;
		}
		return $result->row();
	}
	/**
	 * Update row by primary key
	 *
	 * @access public
	 * @since unknown
	 * @param array $values Array with keys (database field) and values
	 * @param string $key Primary key to be updated
	 * @return mixed It return the number of affected rows if the update has been
	 * correct or false if nothing has been modified
	 */
	public function updateByPrimaryKey( array $values, $key) 
	{
		$cond = array($this->getPrimaryKey() => $key);
		return $this->update($values, $cond);
	}
	/**
	 * Delete the result match from the primary key passed by parameter
	 *
	 * @access public
	 * @since unknown
	 * @param string $value
	 * @return mixed It return the number of affected rows if the delete has been
	 * correct or false if nothing has been modified
	 */
	public function deleteByPrimaryKey($value) 
	{
		$cond = array($this->getPrimaryKey() => $value);
		return $this->delete($cond);
	}
	/**
	 * Get all the rows from the table $tableName
	 *
	 * @access public
	 * @since unknown
	 * @return array
	 */
	public function listAll() 
	{
		$this->dbCommand->select($this->getFields());
		$this->dbCommand->from($this->getTableName());
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return array();
		}
		return $result->result();
	}
	/**
	 * Basic insert
	 *
	 * @access public
	 * @since unknown
	 * @param array $values
	 * @return boolean
	 */
	public function insert( array $values ) 
	{
		if( !$this->checkFieldKeys( array_keys( $values ) ) ) 
		{
			return false;
		}
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->set($values);
		return $this->dbCommand->insert();
	}
	/**
	 * Basic update. It returns false if the keys from $values or $where doesn't
	 * match with the fields defined in the construct
	 *
	 * @access public
	 * @since unknown
	 * @param array $values Array with keys (database field) and values
	 * @param array $where
	 * @return mixed It return the number of affected rows if the update has been
	 * correct or false if nothing has been modified
	 */
	public function update( array $values, array $where ) 
	{
		$valuesKeys = array_keys( $values );
		if( !$this->checkFieldKeys( $valuesKeys ) )
		{
			throw new Exception( 'Invalid "set" columns: ' . implode( ', ', $valuesKeys ) );
		}
		$whereKeys = array_keys( $where );
		if( !$this->checkFieldKeys( $whereKeys ) )
		{
			throw new Exception( 'Invalid "where" columns: ' . implode( ', ', $whereKeys ) );
		}
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->set($values);
		$this->dbCommand->where($where);
		return $this->dbCommand->update();
	}
	/**
	 * Basic delete. It returns false if the keys from $where doesn't
	 * match with the fields defined in the construct
	 *
	 * @access public
	 * @since unknown
	 * @param array $where
	 * @return mixed It return the number of affected rows if the delete has been
	 * correct or false if nothing has been modified
	 */
	public function delete( array $where )
	{
		if (!$this->checkFieldKeys(array_keys($where))) 
		{
			return false;
		}
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where($where);
		return $this->dbCommand->delete();
	}
	/**
	 * Set table name, adding the DB_TABLE_PREFIX at the beginning
	 *
	 * @access private
	 * @since unknown
	 * @param string $table
	 */
	public function setTableName($table) 
	{
		$this->tableName = $this->tablePrefix . $table;
	}
	/**
	 * Get table name
	 *
	 * @access public
	 * @since unknown
	 * @return string
	 */
	public function getTableName() 
	{
		return $this->tableName;
	}
	/**
	 * Set primary key string
	 *
	 * @access private
	 * @since unknown
	 * @param string $key
	 */
	public function setPrimaryKey($key) 
	{
		$this->primaryKey = $key;
	}
	/**
	 * Get primary key string
	 *
	 * @access public
	 * @since unknown
	 * @return string
	 */
	public function getPrimaryKey() 
	{
		return $this->primaryKey;
	}
	/**
	 * Set fields array
	 *
	 * @access private
	 * @param array $fields
	 */
	public function setFields( array $fields )
	{
		$this->fields = $fields;
	}
	/**
	 * Get fields array
	 *
	 * @access public
	 * @return array
	 */
	public function getFields() 
	{
		return $this->fields;
	}
	/**
	 * Check if the keys of the array exist in the $fields array
	 *
	 * @access private
	 * @param array $aKey
	 * @return boolean
	 */
	public function checkFieldKeys( array $aKey)
	{
		$modelKeys = $this->getFields();
		foreach( $aKey as $key ) 
		{
			if( !in_array( $key, $modelKeys ) ) 
			{
				throw new Exception( 'Field is not defined: ' . $key );
			}
		}

		return true;
	}
	/**
	 * Get table prefix
	 *
	 * @access public
	 * @return string
	 */
	public function getTablePrefix() 
	{
		return $this->tablePrefix;
	}
	/**
	 * Returns the number of rows in the table represented by this object.
	 *
	 * @access public
	 * @since unknown
	 * @return int
	 */
	public function count() 
	{
		$total = 0;
		$tableName = $this->getTableName();

		$sql = <<<SQL
SELECT
	COUNT( * )
FROM
	$tableName
LIMIT
	1
SQL;

		$stmt = $this->prepareStatement( $sql );
		$result = $stmt->execute();
		if( false === $result )
			$total = 0;
		else
		{
			$stmt->bind_result( $total );
			$stmt->fetch();
		}
		$stmt->close();

		return $total;
	}
}

