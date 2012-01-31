<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
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
 * Model database for Country table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class Model_Country extends DAO
{
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_country');
		$this->setPrimaryKey('pk_c_code');
		$this->setFields(array('pk_c_code', 'fk_c_locale_code', 's_name'));
	}
	/**
	 * Find a country by its ISO code
	 *
	 * @access public
	 * @since unknown
	 * @param type $code
	 * @return array
	 */
	public function findByCode($code) 
	{
		if( empty( $code ) )
		{
			trigger_error( 'Empty primary key' );
			return null;
		}
		$this->dao->select('*');
		$this->dao->from($this->getTableName());
		$this->dao->where('pk_c_code', $code);
		$this->dao->where('fk_c_locale_code', osc_current_user_locale());
		$result = $this->dao->get();
		return $result->row();
	}
	/**
	 * Find a country by its name
	 *
	 * @access public
	 * @since unknown
	 * @param type $name
	 * @return array
	 */
	public function findByName($name) 
	{
		$this->dao->select('*');
		$this->dao->from($this->getTableName());
		$this->dao->where('s_name', $name);
		$result = $this->dao->get();
		return $result->row();
	}
	/**
	 * List all the countries
	 *
	 * @access public
	 * @since unknown
	 * @param type $language
	 * @return array
	 */
	public function listAll($language = '') 
	{
		if ($language == '') 
		{
			$language = osc_current_user_locale();
		}
		$result = $this->dao->query(sprintf('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'%s\', \'%s\') as sorter FROM %st_country WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC', osc_current_user_locale(), $language, DB_TABLE_PREFIX));
		return $result->result();
	}
	/**
	 * List all countries for admin panel
	 *
	 * @access public
	 * @since unknown
	 * @param type $language
	 * @return array
	 */
	public function listAllAdmin($language = "") 
	{
		if ($language == '') 
		{
			$language = osc_current_user_locale();
		}
		$result = $this->dao->query(sprintf('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'%s\', \'%s\') as sorter FROM %st_country WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC', osc_current_user_locale(), $language, DB_TABLE_PREFIX));
		$countries_temp = $result->result();
		$countries = array();
		foreach ($countries_temp as $country) 
		{
			$locales = $this->dao->query(sprintf("SELECT * FROM %st_country WHERE pk_c_code = '%s'", DB_TABLE_PREFIX, $country['pk_c_code']));
			$locales = $locales->result();
			foreach ($locales as $locale) 
			{
				$country['locales'][$locale['fk_c_locale_code']] = $locale['s_name'];
			}
			$countries[] = $country;
		}
		return $countries;
	}
	/**
	 * Update a country by its locale
	 *
	 * @access public
	 * @since unknown
	 * @param type $code
	 * @param type $locale
	 * @param type $name
	 * @return array
	 */
	public function updateLocale($code, $locale, $name) 
	{
		$this->dao->select('*');
		$this->dao->from($this->getTableName());
		$this->dao->where('pk_c_code', addslashes($code));
		$this->dao->where('fk_c_locale_code', addslashes($locale));
		$this->dao->limit(1);
		$result = $this->dao->get();
		$country = $result->result();
		if ($country) 
		{
			return $this->dao->update($this->getTableName(), array('s_name' => $name), array('pk_c_code' => $code, 'fk_c_locale_code' => $locale));
		}
		else
		{
			return $this->insert(array('pk_c_code' => $code, 'fk_c_locale_code' => $locale, 's_name' => $name,));
		}
	}
	/**
	 * Function that work with the ajax file
	 *
	 * @access public
	 * @since unknown
	 * @param type $query
	 * @return array
	 */
	public function ajax($query) 
	{
		$this->dao->select('pk_c_code as id, s_name as label, s_name as value');
		$this->dao->from($this->getTableName());
		$this->dao->like('s_name', $query, 'after');
		$this->dao->limit(5);
		$result = $this->dao->get();
		return $result->result();
	}
}
