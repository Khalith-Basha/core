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
class CAdminSettings extends AdministrationController
{
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$htaccess = $this->getInput()->getString( 'htaccess_status' );
		$file = $this->getInput()->getString( 'file_status' );

		$this->getView()->assign( 'htaccess', $htaccess );
		$this->getView()->assign( 'file', $file );

		$this->doView('settings/permalinks.php');
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$preference = $this->getClassLoader()->getClassInstance( 'Model_Preference' );	
		$htaccess_status = 0;
		$file_status = 0;
		$rewriteEnabled = $this->getInput()->getString( 'rewrite_enabled' );
		if ($rewriteEnabled) 
		{
			$preference->update(array('s_value' => '1'), array('s_name' => 'rewriteEnabled'));
			$htaccess = '
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase ' . REL_WEB_URL . '
        RewriteRule ^index\.php$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . ' . REL_WEB_URL . 'index.php [L]
    </IfModule>';
			if (file_exists(osc_base_path() . '.htaccess')) 
			{
				$file_status = 1;
			}
			else if (file_put_contents(osc_base_path() . '.htaccess', $htaccess)) 
			{
				$file_status = 2;
			}
			else
			{
				$file_status = 3;
			}
			if (apache_mod_loaded('mod_rewrite')) 
			{
				$htaccess_status = 1;
				$preference->update(array('s_value' => '1'), array('s_name' => 'mod_rewrite_loaded'));
			}
			else
			{
				$htaccess_status = 2;
				$preference->update(array('s_value' => '0'), array('s_name' => 'mod_rewrite_loaded'));
			}
		}
		else
		{
			$modRewrite = apache_mod_loaded('mod_rewrite');
			$preference->update(array('s_value' => '0'), array('s_name' => 'rewriteEnabled'));
			$preference->update(array('s_value' => '0'), array('s_name' => 'mod_rewrite_loaded'));
		}
		$redirectUrl = osc_admin_base_url(true) . '?page=settings&action=permalinks&htaccess_status=';
		$redirectUrl.= $htaccess_status . '&file_status=' . $file_status;
		$this->redirectTo($redirectUrl);
	}
}

