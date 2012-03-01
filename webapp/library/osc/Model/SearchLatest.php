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

class Model_SearchLatest extends Model 
{
	public function insert( $query )
	{
		$sql = <<<SQL
INSERT INTO
	/*TABLE_PREFIX*/latest_searches
	( query )
VALUES
	( ? )
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 's', $query );
		$result = $stmt->execute();
		$stmt->close();

		return $result;
	}

	public function selectAll()
	{
		$sql = <<<SQL
SELECT
	query,
	MAX( search_time )
FROM
	/*TABLE_PREFIX*/latest_searches
GROUP BY
	query
ORDER BY
	search_time DESC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$results = $this->fetchAll( $stmt );
		$stmt->close();

		return $results;
	}

	/**
	 * Get last searches, given a limit.
	 *
	 * @access public
	 * @since unknown
	 * @param int $limit
	 * @return array
	 */
	function getSearches($limit = 20) 
	{
		$this->dao->select('d_date, s_search, COUNT(s_search) as i_total');
		$this->dao->from($this->getTableName());
		$this->dao->groupBy('s_search');
		$this->dao->orderBy('d_date', 'DESC');
		$this->dao->limit($limit);
		$result = $this->dao->get();
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
	 * Get last searches, given since time.
	 *
	 * @access public
	 * @since unknown
	 * @param int $time
	 * @return array
	 */
	function getSearchesByDate($time = null) 
	{
		if ($time == null) 
		{
			$time = time() - (7 * 24 * 3600);
		};
		$this->dao->select('d_date, s_search, COUNT(s_search) as i_total');
		$this->dao->from($this->getTableName());
		$this->dao->where('d_date', date('Y-m-d H:i:s', $time));
		$this->dao->groupBy('s_search');
		$this->dao->orderBy('d_date', 'DESC');
		$this->dao->limit($limit);
		$result = $this->dao->get();
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
	 * Purge all searches by date.
	 *
	 * @access public
	 * @since unknown
	 * @param string $date
	 * @return bool
	 */
	function purgeDate($date = null) 
	{
		if ($date == null) 
		{
			return false;
		}
		$this->dao->from($this->getTableName());
		$this->dao->where('d_date <= ' . $this->dao->escape($date));
		return $this->dao->delete();
	}
	/**
	 * Purge n last searches.
	 *
	 * @access public
	 * @since unknown
	 * @param int $number
	 * @return bool
	 */
	public function purgeNumber($number = null) 
	{
		if ($number == null) 
		{
			return false;
		}
		$this->dao->select('d_date');
		$this->dao->from($this->getTableName());
		$this->dao->groupBy('s_search');
		$this->dao->orderBy('d_date', 'DESC');
		$this->dao->limit($number, 1);
		$result = $this->dao->get();
		$last = $result->row();
		if ($result == false) 
		{
			return false;
		}
		if ($result->numRows() == 0) 
		{
			return false;
		}
		return $this->purgeDate($last['d_date']);
	}
}
