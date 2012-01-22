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
echo osc_current_admin_theme_url('images/tools-icon.png'); ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Regenerate thumbnails'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php
osc_show_flash_message('admin'); ?>
                <!-- add new item form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <p>
                            <?php
_e('You can regenerate your thumbnails and previews images here. It\'s useful if you changed your theme and images are not showing up correctly. Please, check the size values defined in the settings/media section'); ?>.
                        </p>
                        <form action="<?php
echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="action" value="images_post" />
                            <input type="hidden" name="page" value="tools" />

                            <input id="button_save" type="submit" value="<?php
_e('Regenerate thumbnails'); ?>" />
                        </form>
                    </div>
                </div>

