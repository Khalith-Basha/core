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
 * Model database for Region table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class Model_Region extends DAO
{
	public function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_region');
		$this->setPrimaryKey('pk_i_id');
		$this->setFields(array('pk_i_id', 'fk_c_country_code', 's_name', 'b_active'));
	}

	public function findAll()
	{
		$sql = <<<SQL
SELECT
	pk_i_id, fk_c_country_code, s_name, b_active
FROM
	/*TABLE_PREFIX*/t_region
ORDER BY
	s_name ASC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$regions = $this->fetchAll( $stmt );
		$stmt->close();

		return $regions;
	}

	public function findByCountry( $countryId )
	{
		$sql = <<<SQL
SELECT
	pk_i_id, fk_c_country_code, s_name, b_active
FROM
	/*TABLE_PREFIX*/t_region
WHERE
	fk_c_country_code = ?
ORDER BY
	s_name ASC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 's', $countryId );
		$regions = $this->fetchAll( $stmt );
		$stmt->close();

		return $regions;
	}
	/**
	 * Find a region by its name and country
	 *
	 * @access public
	 * @since unknown
	 * @param string $name
	 * @param string $country
	 * @return array
	 */
	public function findByName($name, $country = null) 
	{
		$this->dao->select('*');
		$this->dao->from($this->getTableName());
		$this->dao->where('s_name', $name);
		if ($country != null) 
		{
			$this->dao->where('fk_c_country_code', $country);
		}
		$this->dao->limit(1);
		$result = $this->dao->get();
		return $result->row();
	}
	/**
	 * Function to deal with ajax queries
	 *
	 * @access public
	 * @since unknown
	 * @param type $query
	 * @return array
	 */
	public function ajax($query, $country = null) 
	{
		$this->dao->select('pk_i_id as id, s_name as label, s_name as value');
		$this->dao->from($this->getTableName());
		$this->dao->like('s_name', $query, 'after');
		if ($country != null) 
		{
			$this->dao->where('fk_c_country_code', strtolower($country));
		}
		$this->dao->limit(5);
		$result = $this->dao->get();
		if ($result) 
		{
			return $result->result();
		}
		return array();
	}
}
