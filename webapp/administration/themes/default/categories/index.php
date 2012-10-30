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

<script src="<?php echo osc_current_admin_theme_url('static/scripts/vtip/vtip.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_current_admin_theme_url('static/scripts/vtip/css/vtip.css'); ?>" />
<script type="text/javascript" src="<?php echo osc_current_admin_theme_url('static/scripts/jquery.ui.nestedSortable.js'); ?>"></script>
<div id="content_header" class="content_header">
    <div style="float: left;">
	<img src="<?php echo osc_current_admin_theme_url('images/cat-icon.png'); ?>" title="" alt="" />
    </div>
    <div id="content_header_arrow">&raquo; <?php _e('Categories'); ?></div>
    <div style="clear: both;"></div>
</div>
<div id="content_separator"></div>

<p><?php _e('Drag&drop the categories to reorder them the way you like. Click on edit link to edit the category'); ?></p>
<p><a href="<?php echo osc_admin_base_url(true); ?>?page=category&action=add_post_default">+ <?php _e('Add new category'); ?></a></p>

<div id="categoriesTree">
<ul>
<?php foreach( $categories as $category ): ?>
	<li data-categoryId="<?php echo $category['pk_i_id']; ?>">
		<a href="#"><?php echo $category['s_name']; ?></a>
		<?php if( 0 < count( $category['categories'] ) ): ?>
			<ul>
				<?php foreach( $category['categories'] as $subCategory ): ?>
					<li data-categoryId="<?php echo $subCategory['pk_i_id']; ?>"><a href="#"><?php echo $subCategory['s_name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
</div>

