<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$searchUrl = $classLoader->getClassInstance( 'Url_Search' );
$resourceUrls = $classLoader->getClassInstance( 'Url_Resource' );
echo $view->render( 'header' );
?>
    <div class="content list">
	<div id="main">
	    <div class="ad_list">
		<?php echo $view->render( 'search/listing-header' ); ?>
		<?php if (osc_count_items() == 0)  { ?>
		    <p class="empty" ><?php printf(__('There are no results matching "%s"', 'modern'), osc_search_pattern()); ?></p>
		<?php } else { ?>
		    <?php echo $view->render( 'list' === osc_search_show_as() ? 'search/list' : 'search/gallery' ); ?>
		<?php } ?>
		<?php echo $view->render( 'search/pagination' ); ?>
	    </div>
	</div>
	<div id="sidebar">
		<?php echo $view->render( 'search/sidebar' ); ?>
	</div>
    </div>
<?php
echo $view->render( 'footer' );

