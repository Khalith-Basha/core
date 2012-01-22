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

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php
echo osc_current_admin_theme_url('images/settings-icon.png'); ?>" alt="" title="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Categories settings'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php
osc_show_flash_message('admin'); ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form action="<?php
echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="category" />
                            <input type="hidden" name="action" value="settings" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php
_e('Settings'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="selectable_parent_categories" id="selectable_parent_categories" <?php
echo (osc_selectable_parent_categories() ? 'checked="checked"' : ''); ?> value="1" />
                                    <label for="selectable_parent_categories"><?php
_e('Selectable parent categories'); ?></label>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php
_e('Update'); ?>" />
                        </form>
                    </div>
                </div>

