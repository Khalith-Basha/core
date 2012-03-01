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
        <script type="text/javascript">
            $(document).ready(function(){
                $.ajaxSetup({
                    error: function(x,e){
                        if(x.status==0){
                            alert("<?php
_e('You\'re offline! Please check your connection'); ?>");
                        } else if(x.status==404){
                            alert("<?php
_e('Requested URL not found'); ?>");
                        } else if(x.status==500){
                            alert("<?php
_e('Internal server error'); ?>");
                        } else if(e=='parsererror'){
                            alert("<?php
_e('Error. Parsing JSON request failed'); ?>");
                        } else if(e=='timeout'){
                            alert("<?php
_e('Request timeout'); ?>");
                        } else {
                            alert("<?php
_e('Unknown error'); ?>" + x.responseText);
                        }
                    }
                });
            });

            function submitForm(frm, type) {
                frm.action.value = 'backup-' + type ;
                frm.submit() ;
            }

        </script>
        
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php
echo osc_current_admin_theme_url('images/tools-icon.png'); ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Backup OpenSourceClassifieds'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php
osc_show_flash_message('admin'); ?>
                <!-- add new item form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <?php
_e('You can back up OpenSourceClassifieds here. WARNING: If you don\'t specify a backup folder, the backup files will be created in the root of your OpenSourceClassifieds installation'); ?>
                        <form action="<?php
echo osc_admin_base_url(true); ?>" method="post" id="bckform" name="bckform" >
                            <input type="hidden" name="page" value="tools" />
                            <input type="hidden" name="action" value="" />

                            <p>
                                <label for="data"><?php
_e('Backup folder'); ?></label>
                                <input type="text" id="backup_dir" name="bck_dir" value="<?php
echo osc_base_path(); ?>" />
                                <?php
_e('This is the folder in which your backups will be created. We recommend that you choose a non-public path. For more information, please refer to OpenSourceClassifieds\' documentation') ?>.
                            </p>

                            <p>
                                <label for="data"><?php
_e('Back up database'); ?> (.sql)</label>
                                <button class="formButton" type="button" onclick="javascript:submitForm(this.form, 'sql');" ><?php
_e('Backup'); ?></button>
                                <div id="steps_sql"></div>
                            </p>

                            <p>
                                <label for="data"><?php
_e('Back up OpenSourceClassifieds installation'); ?> (.zip)</label>
                                <button class="formButton" type="button" onclick="javascript:submitForm(this.form, 'zip');" ><?php
_e('Backup'); ?></button>
                                <div id="steps_zip"></div>
                            </p>
                        </form>
                    </div>
                </div>

