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
$numCols = 1;
$catsPerCol = round(count($categories) / $numCols);
?>

        <link href="<?php echo osc_current_admin_theme_styles_url('jquery.treeview.css'); ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.treeview.js'); ?>"></script>
        <script type="text/javascript">

            function checkAll (frm, check) {
                var aa = document.getElementById(frm);
                for (var i = 0 ; i < aa.elements.length ; i++) {
                    aa.elements[i].checked = check;
                }
            }

            function checkCat(id, check) {
                var lay = document.getElementById("cat" + id);
                inp = lay.getElementsByTagName("input");
                for (var i = 0, maxI = inp.length ; i < maxI; ++i) {
                    if(inp[i].type == "checkbox") {
                        inp[i].checked = check;
                    }
                }
            }
            
            $(document).ready(function(){
                $("#cat_tree").treeview({
                    animated: "fast",
                    collapsed: true
                });
            });

        </script>

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/plugins-icon.png'); ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Plugins'); ?></div>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugin&action=add" id="button_open"><?php _e('Add a new plugin'); ?></a>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <div id="TableToolsToolbar"></div>

                <div>
                    <form id="frm3" action="<?php echo osc_admin_base_url(true); ?>?page=plugin" method="post">
                        <input type="hidden" name="action" value="configure_post" />
                        <input type="hidden" name="plugin" value="<?php echo $plugin_data['filename']; ?>" />
                        <input type="hidden" name="plugin_short_name" value="<?php echo $plugin_data['short_name']; ?>" />

                        <p>
                            <?php echo "<b>" . $plugin_data['plugin_name'] . "</b>,<br/>" . $plugin_data['description']; ?>
                            <br/>
                            <?php _e('Select the categories where you want to apply these attributes (click on their names to expand them)'); ?>:
                        </p>
                        <p>
                            <table>
                                <tr style="vertical-align: top;">
                                    <td style="font-weight: bold;" colspan="<?php echo $numCols; ?>">
                                        <label for="categories"><?php _e("Preset categories"); ?></label><br />
                                        <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', true); return false;"><?php _e("Check all"); ?></a> - <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', false); return false;"><?php _e("Uncheck all"); ?></a>
                                    </td>
                                    <td>
                                        <ul id="cat_tree">
                                            <?php CategoryForm::categories_tree($categories, $selected); ?>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </p>
                        <p>
                            <input class="Button" type="button" onclick="window.history.go(-1);" value="<?php _e('Cancel'); ?>" />
                            <input class="Button" type="submit" value="<?php _e('Update'); ?>" />
                        </p>
                    </form>
                </div>

