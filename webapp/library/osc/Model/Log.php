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
 * Log DAO
 */
class Model_Log extends DAO
{
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_audit');
		$array_fields = array('dt_date', 's_section', 's_action', 'fk_i_id', 's_data', 's_ip', 's_who', 'fk_i_who_id');
		$this->setFields($array_fields);
	}
	/**
	 * Insert a log row.
	 *
	 * @access public
	 * @since unknown
	 * @param string $section
	 * @param string $action
	 * @param integer $id
	 * @param string $data
	 * @param string $who
	 * @param integer $who_id
	 * @return boolean
	 */
	public function insertLog($section, $action, $id, $data, $who, $whoId) 
	{
		$array_set = array('dt_date' => date('Y-m-d H:i:s'), 's_section' => $section, 's_action' => $action, 'fk_i_id' => $id, 's_data' => $data, 's_ip' => $_SERVER['REMOTE_ADDR'], 's_who' => $who, 'fk_i_who_id' => $whoId);
		return $this->dao->insert($this->getTableName(), $array_set);
	}
}
