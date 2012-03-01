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
                            <img src="<?php echo osc_current_admin_theme_url('images/plugins-icon.png'); ?>" title="" alt="" />
                        </div>
				        <div id="content_header_arrow">&raquo; <?php _e('Plugins'); ?></div>
				        <a href="<?php echo osc_admin_base_url(true); ?>?page=plugin&action=add" id="button_open"><?php _e('Add a new plugin'); ?></a>
				        <div style="clear: both;"></div>
			        </div>
			        <?php osc_show_flash_message('admin'); ?>
			        <div id="content_separator"></div>
			        <div id="TableToolsToolbar"></div>
			        <div>
                        <?php require $file; ?>
                    </div>

