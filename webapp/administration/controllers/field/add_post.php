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
class CAdminField extends Controller_Administration
{
	private $fieldManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->fieldManager = Field::newInstance();
	}

	function doModel() 
	{
		parent::doModel();
		if (Params::getParam('field_name') != '') 
		{
			$field = $this->fieldManager->findByName(Params::getParam('field_name'));
			if (!isset($field['pk_i_id'])) 
			{
				$slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower(Params::getParam("field_slug"))));
				$this->fieldManager->insertField(Params::getParam("field_name"), Params::getParam("field_type_new"), $slug, Params::getParam("field_required") == "1" ? 1 : 0, Params::getParam('field_options'), Params::getParam('categories'));
				osc_add_flash_ok_message(_m("New custom field added"), "admin");
			}
			else
			{
				osc_add_flash_error_message(_m("Sorry, you already have one field with that name"), "admin");
			}
		}
		else
		{
			osc_add_flash_error_message(_m("Name can not be empty"), "admin");
		}
		$this->redirectTo(osc_admin_base_url(true) . "?page=field");
	}
}
