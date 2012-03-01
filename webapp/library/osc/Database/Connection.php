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
/**
 * Database connection object
 *
 * @package OpenSourceClassifieds
 * @subpackage Database
 */
class Database_Connection 
{
	/**
	 * Host name or IP address where it is located the database
	 *
	 * @access private
	 * @var string
	 */
	private $dbHost;
	/**
	 * Database name where it's installed OpenSourceClassifieds
	 *
	 * @access private
	 * @var string
	 */
	private $dbName;
	/**
	 * Database user
	 *
	 * @access private
	 * @var string
	 */
	private $dbUser;
	/**
	 * Database user password
	 *
	 * @access private
	 * @var string
	 */
	private $dbPassword;
	/**
	 * Database connection object to OpenSourceClassifieds database
	 *
	 * @access private
	 * @var mysqli
	 */
	private $db = null;
	/**
	 * Database error number
	 *
	 * @access private
	 * @var int
	 */
	private $errorLevel = 0;
	/**
	 * Database error description
	 *
	 * @access private
	 * @var string
	 */
	private $errorDesc = null;
	/**
	 * Initializate database connection
	 *
	 * @param string $server Host name where it's located the mysql server
	 * @param string $database Default database to be used when performing queries
	 * @param string $user MySQL user name
	 * @param string $password MySQL password
	 */
	public function __construct( $server, $user, $password, $database = null, $tablePrefix = null ) 
	{
		$this->dbHost = $server;
		$this->dbName = $database;
		$this->dbUser = $user;
		$this->dbPassword = $password;
		$this->tablePrefix = $tablePrefix;
		$this->connectToMainDb();
	}

	public function getTablePrefix()
	{
		return $this->tablePrefix;
	}

	/**
	 * Connection destructor and print debug
	 */
	public function __destruct() 
	{
		$this->db->close();
	}
	/**
	 * Set error num error and error description
	 *
	 * @access private
	 */
	function errorReport() 
	{
		$this->errorLevel = $this->db->errno;
		$this->errorDesc = $this->db->error;
	}
	/**
	 * Return the mysqli error number
	 *
	 * @access public
	 * @return type
	 */
	function getErrorLevel() 
	{
		return $this->errorLevel;
	}
	/**
	 * Return the mysqli error description
	 *
	 * @access public
	 * @return string
	 */
	function getErrorDesc() 
	{
		return $this->errorDesc;
	}
	/**
	 * Connect to OpenSourceClassifieds database
	 *
	 * @access public
	 * @return boolean It returns true if the connection has been successful or false if not
	 */
	function connectToMainDb() 
	{
		$this->db = new mysqli( $this->dbHost, $this->dbUser, $this->dbPassword );
		if( false === $this->db )
		{
			throw new Exception( $this->db->connect_errno . ': ' . $this->db->connect_error );
		}
		$this->db->set_charset( 'UTF8' );
		if( empty( $this->dbName ) )
		{
			return true;
		}
		if( false === $this->db->select_db( $this->dbName ) )
		{
			$this->errorReport();
			$this->db->close();
			return false;
		}
		return true;
	}

	public function getResource()
	{
		return $this->db;
	}
}

