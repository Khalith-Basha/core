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
        
<div style="clear: both;"></div>
<div id="footer">
    <?php
osc_run_hook('admin_footer'); ?>
    <div id="footer_left">
        <?php
_e('Thank you for using'); ?> <a href="http://opensourceclassifieds.org/" target="_blank"><?php
_e('OpenSourceClassifieds'); ?></a> |
        <a title="<?php
_e('Documentation'); ?>" href="http://wiki.opensourceclassifieds.org/" target="_blank"><?php
_e('Documentation'); ?></a> |
        <a title="<?php
_e('Forums'); ?>" href="http://forums.opensourceclassifieds.org" target="_blank"><?php
_e('Forums'); ?></a>
    </div>
    <div id="footer_right"><?php echo _e('OpenSourceClassifieds'); ?> <?php echo OSC_VERSION; ?></div>
</div>
