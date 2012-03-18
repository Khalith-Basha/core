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
 * Model database for Currency table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class Model_Currency extends DAO
{
	/**
	 * Set data related to t_currency table
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_currency');
		$this->setPrimaryKey('pk_c_code');
		$this->setFields(array('pk_c_code', 's_name', 's_description', 'b_enabled'));
	}
	/**
	 * Find currency by currency code
	 *
	 * @deprecated
	 * @access public
	 * @since unknown
	 * @param int $id
	 * @return array
	 */
	public function findBycode($id) 
	{
		return $this->findByPrimaryKey($id);
	}

	public function listAll()
	{
		$sql = <<<SQL
SELECT
	pk_c_code, s_name, s_description, b_enabled
FROM
	/*TABLE_PREFIX*/t_currency
ORDER BY
	s_name ASC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$currencies = $this->fetchAll( $stmt );
		$stmt->close();

		return $currencies;
	}
}

