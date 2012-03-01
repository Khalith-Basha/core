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

class Model_Locale extends DAO
{
	public function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_locale');
		$this->setPrimaryKey('pk_c_code');
		$array_fields = array( 'pk_c_code', 's_name', 's_short_name', 's_description', 's_version', 's_author_name', 's_author_url', 's_currency_format', 's_dec_point', 's_thousands_sep', 'i_num_dec', 's_date_format', 's_stop_words', 'b_enabled', 'b_enabled_bo' );
		$this->setFields($array_fields);
	}

	public function listAll()
	{
		$sql = <<<SQL
SELECT
	pk_c_code, s_name, s_short_name, s_description, s_version, s_author_name, s_author_url, s_currency_format, s_dec_point, s_thousands_sep, i_num_dec, s_date_format, s_stop_words, b_enabled, b_enabled_bo
FROM
	/*TABLE_PREFIX*/t_locale
ORDER BY
	s_name ASC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$locales = $this->fetchAll( $stmt );
		$stmt->close();

		return $locales;

	}

	public function listAllEnabled( $isBo = false )
	{
		$column = $isBo ? 'b_enabled_bo' : 'b_enabled';
		$sql = <<<SQL
SELECT
	pk_c_code, s_name, s_short_name, s_description, s_version, s_author_name, s_author_url, s_currency_format, s_dec_point, s_thousands_sep, i_num_dec, s_date_format, s_stop_words, b_enabled, b_enabled_bo
FROM
	/*TABLE_PREFIX*/t_locale
WHERE
	$column IS TRUE
ORDER BY
	s_name ASC
SQL;

		$stmt = $this->prepareStatement( $sql );
		$locales = $this->fetchAll( $stmt );
		$stmt->close();

		return $locales;
	}

	/**
	 * Return all locales by code
	 *
	 * @param string $code
	 * @return array
	 */
	public function findByCode($code) 
	{
		$this->dao->select();
		$this->dao->from($this->getTableName());
		$this->dao->where('pk_c_code', $code);
		$result = $this->dao->get();
		return $result->result();
	}

	/**
	 * Delete all related to locale code.
	 *
	 * @param string $locale
	 * @return bool
	 */
	public function deleteLocale($locale) 
	{
		osc_run_hook('delete_locale', $locale);
		$array_where = array('fk_c_locale_code' => $locale);
		$this->dao->delete(DB_TABLE_PREFIX . 't_category_description', $array_where);
		$this->dao->delete(DB_TABLE_PREFIX . 't_item_description', $array_where);
		$this->dao->delete(DB_TABLE_PREFIX . 't_keywords', $array_where);
		$this->dao->delete(DB_TABLE_PREFIX . 't_user_description', $array_where);
		$this->dao->delete(DB_TABLE_PREFIX . 't_pages_description', $array_where);
		$this->dao->delete(DB_TABLE_PREFIX . 't_country', $array_where);
		$result = $this->dao->delete($this->getTableName(), array('pk_c_code' => $locale));
		return $result;
	}
}

