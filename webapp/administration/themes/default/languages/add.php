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
echo osc_current_admin_theme_url('images/icon-language.png'); ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Add a language'); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php
osc_show_flash_message('admin'); ?>

                <!-- add new plugin form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                    <?php
if (is_writable(osc_translations_path())) 
{ ?>

                        <p style="border-bottom: 1px black solid;padding-bottom: 10px;">
                            <img style="padding-right: 10px;"src="<?php
	echo osc_current_admin_theme_url('images/info-icon.png'); ?>"/>
                            <?php
	printf(__('Download more languages at %s'), '<a href="https://sourceforge.net/projects/osclass/files/Languages/" target="_blank">Sourceforge</a>'); ?>
                        </p>

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add" />
                            <input type="hidden" name="page" value="language" />
                            <p>
                                <label for="package"><?php
	_e('Language package'); ?> (.zip)</label>
                                <input type="file" name="package" id="package" />
                            </p>
                            <input id="button_save" type="submit" value="<?php
	_e('Upload'); ?>" />
                        </form>

                    <?php
}
else
{ ?>
                        <p>
                            <?php
	_e('The translations folder is not writable on your server and you cannot upload translations from the administration panel. Please make the translation folder writable'); ?>
                        </p>
                        <p>
                            <?php
	_e('To make the directory writable under UNIX execute this command from the shell:'); ?>
                        </p>
                        <div style="background-color: white; border: 1px solid black; padding: 8px;">
                            chmod a+w <?php
	echo osc_translations_path(); ?>
                        </div>
                    <?php
} ?>
                    </div>
                </div>

