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

class Widget extends \Cuore\Model\DbObject
{
	public function __construct() 
	{
		parent::__construct();

		$this->setTableName( DB_TABLE_PREFIX . 't_widget' );
		$this->setPrimaryKey( 'pk_i_id' );
		$this->setFields( array( 'pk_i_id', 's_description', 's_location', 'e_kind', 's_content' ) );
	}

	/**
	 * @return array
	 */
	public function findByLocation($location) 
	{
		$fields = implode( ', ', $this->fields );

		$sql = <<<SQL
SELECT
	$fields
FROM
	$this->tableName
WHERE
	s_location = ?
SQL;
		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 's', $s_location );
		return $this->fetchAll( $stmt );
	}
}

