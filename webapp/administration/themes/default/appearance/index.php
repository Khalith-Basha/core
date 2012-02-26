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

<script type="text/javascript">
$(function() {
$("#button_cancel").click(function() {
if(confirm('<?php _e('Are you sure you want to cancel?'); ?>')) {
window.location = 'appearance.php';
}
});
});
</script>
<div id="content_header" class="content_header">
<div style="float: left;">
<img src="<?php echo osc_current_admin_theme_url('images/themes-icon.png'); ?>" title="" alt=""/>
</div>
<div id="content_header_arrow">&raquo; <?php _e('Appearance'); ?></div>
<a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=add" id="button_open"><?php _e('Add a new theme'); ?></a>
<div style="clear: both;"></div>
</div>

<?php osc_show_flash_message('admin'); ?>

<div id="content_separator"></div>
<div id="list_themes_div" style="border: 1px solid #ccc; background: #eee;">
<div style="padding: 20px;">

<div id="current_theme"><?php _e('Current theme'); ?></div>

<div id="current_theme_pic">
<?php if( !empty( $currentTheme['screenshot'] ) ): ?>
<img src="<?php echo $currentTheme['screenshot']; ?>" style="width: 280px;" alt="Theme screenshot" />
<?php else: ?>
<p>(screenshot has not been found)</p>
<?php endif; ?>
</div>

<div id="current_theme_info">
<strong><?php echo $currentTheme['name']; ?> <?php echo $currentTheme['version']; ?>. <?php _e('Author'); ?> <a target="_blank" href="<?php echo $currentTheme['author_url']; ?>"><?php echo $currentTheme['author_name']; ?></a></strong>
</div>
<div id="current_theme_desc"><?php echo $currentTheme['description']; ?></div>

<div id="content_separator"></div>
<div id="current_theme"><?php _e('Available themes'); ?></div>

<div>
<?php if( 0 === count( $availableThemes ) ): ?>
<p>No themes are available yet.</p>
<?php endif; ?>
<?php foreach( $availableThemes as $theme ): ?>
<center>
<div style="width: 49%; float: left; padding-top: 10px; padding-bottom: 20px;">
<div id="available_theme_info">
<strong><?php echo $theme['name']; ?> <?php echo $theme['version']; ?> by <a href="<?php echo $theme['author_url']; ?>"><?php echo $theme['author_name']; ?></a></strong>
</div>
<div id="available_theme_actions">
<a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=activate&amp;theme=<?php echo $theme['int_name']; ?>"><?php _e('Activate'); ?></a> |
<a target="_blank" href="<?php echo osc_base_url(true); ?>?theme=<?php echo $theme['int_name']; ?>"><?php _e('Preview'); ?></a>
</div>
<div id="available_theme_pic">
<?php if( !empty( $theme['screenshot'] ) ): ?>
<img src="<?php echo $theme['screenshot']; ?>" width="280" alt="Theme screenshot" />
<?php else: ?>
<p>(screenshot has not been found)</p>
<?php endif; ?>
</div>
<div id="available_theme_desc"><?php echo $theme['description']; ?></div>
<div style="clear: both;"></div>
</div>
</center>
<?php endforeach; ?>

<div style="clear:both;"></div>
</div>
</div>
</div>

