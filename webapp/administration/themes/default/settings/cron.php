<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
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

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php
echo osc_current_admin_theme_url('images/settings-icon.png'); ?>" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Cron'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php
osc_show_flash_message('admin'); ?>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form action="<?php
echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="cron" />
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php
_e('Cron system'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php
echo (osc_auto_cron()) ? 'checked="true"' : ''; ?> name="auto_cron" id="auto_cron" />
                                    <label for="auto_cron"><?php
_e('Auto-cron'); ?></label>
                                    <br/>
                                    <label><?php
_e('Some OpenSourceClassifieds functionalities require a cron in order to work. Check if your host isn\'t able to do them. Uncheck if you want to set up your cron manually. Refer to the manual to know more about the OpenSourceClassifieds cron system.'); ?></label>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php
_e('Update'); ?>" />
                        </form>
                    </div>
                </div>

