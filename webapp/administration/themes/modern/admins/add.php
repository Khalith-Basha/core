<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
?>

                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">

                        <form action="<?php
echo osc_admin_base_url(true); ?>" method="post" onSubmit="return checkForm()">
                            <input type="hidden" name="action" value="add_post" />
                            <input type="hidden" name="page" value="admins" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php
_e('Real name'); ?> (<?php
_e('required'); ?>)</legend>
                                    <input type="text" name="s_name" id="s_name" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php
_e('E-mail'); ?> (<?php
_e('required'); ?>)</legend>
                                    <input type="text" name="s_email" id="s_email" />
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php
_e('User name'); ?> (<?php
_e('required'); ?>)</legend>
                                    <input type="text" name="s_username" id="s_username" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php
_e('Password'); ?> (<?php
_e('required'); ?>)</legend>
                                    <input type="password" name="s_password" id="s_password" />
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php
_e('Create'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
osc_current_admin_theme_path('footer.php'); ?>
    </body>
</html>
