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
 * Model database for Admin table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class Model_Admin extends Model
{
	function d__construct() 
	{
		parent::__construct();
		$this->setTableName('t_admin');
		$this->setPrimaryKey('pk_i_id');
		$this->setFields(array('pk_i_id', 's_name', 's_username', 's_password', 's_email', 's_secret'));
	}

	public function delete( $id )
	{
		$sql = <<<SQL
DELETE FROM
	/*TABLE_PREFIX*/t_admin
WHERE
	pk_i_id = ?
LIMIT 1
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 'd', $id );
		$result = $stmt->execute();
		$stmt->close();

		return $result;
	}

	/**
	 * Searches for admin information, given an email address.
	 * If email not exist return false.
	 *
	 * @access public
	 * @since unknown
	 * @param string $email
	 * @return array
	 */
	function findByEmail($email) 
	{
		$this->dao->select();
		$this->dao->from($this->getTableName());
		$this->dao->where('s_email', $email);
		$result = $this->dao->get();
		if ($result->numRows == 0) 
		{
			return false;
		}
		return $result->row();
	}

	public function listAll()
	{
		$admins = array();

		$sql = <<<SQL
SELECT
	pk_i_id, s_name, s_username, s_password, s_email, s_secret
FROM
	/*TABLE_PREFIX*/t_admin
SQL;

		$stmt = $this->prepareStatement( $sql );
		if( $stmt->execute() )
			$admins = $this->fetchAll( $stmt );
		$stmt->close();

		return $admins;
	}

	public function findByPrimaryKey( $id )
	{
		$admin = false;

		$sql = <<<SQL
SELECT
	pk_i_id, s_name, s_username, s_password, s_email, s_secret
FROM
	/*TABLE_PREFIX*/t_admin
WHERE
	pk_i_id = ?
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 'd', $id );
		if( $stmt->execute() )
			$admin = $this->fetch( $stmt );
		$stmt->close();

		return $admin;
	}

	public function findByUsername( $username )
	{
		$admin = false;

		$sql = <<<SQL
SELECT
	pk_i_id, s_name, s_username, s_password, s_email, s_secret
FROM
	/*TABLE_PREFIX*/t_admin
WHERE
	s_username = ?
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 's', $username );
		if( $stmt->execute() )
			$admin = $this->fetch( $stmt );
		$stmt->close();

		return $admin;
	}
	/**
	 * Searches for admin information, given a admin id and secret.
	 * If credential don't match return false.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $id
	 * @param string $secret
	 * @return array
	 */
	function findByIdSecret($id, $secret) 
	{
		$this->dao->select();
		$this->dao->from($this->getTableName());
		$conditions = array('pk_i_id' => $id, 's_secret' => $secret);
		$this->dao->where($conditions);
		$result = $this->dao->get();
		if ($result->numRows == 0) 
		{
			return false;
		}
		return $result->row();
	}
	/**
	 * Searches for admin information, given a admin id and password.
	 * If credential don't match return false.
	 *
	 * @access public
	 * @since unknown
	 * @param integer $id
	 * @param string $password
	 * @return array
	 */
	function findByIdPassword($id, $password) 
	{
		$this->dao->select();
		$this->dao->from($this->getTableName());
		$conditions = array('pk_i_id' => $id, 's_password' => $secret);
		$this->dao->where($conditions);
		$result = $this->dao->get();
		if ($result->numRows == 0) 
		{
			return false;
		}
		return $result->row();
	}
}
