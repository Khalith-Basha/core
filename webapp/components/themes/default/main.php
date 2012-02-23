<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
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
$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
$resourceUrls = $classLoader->getClassInstance( 'Url_Resource' );
$searchUrl = $classLoader->getClassInstance( 'Url_Search' );

echo $view->render( 'header' );
?>

<div id="form_publish"><?php echo $view->render( 'search/form' ); ?></div>

<div class="content home">
	<div id="main">
		<?php
		echo $view->render( 'item/categories' );
		echo $view->render( 'item/latest' );
		?>
	</div>
	<div id="sidebar">
		<div class="navigation">
			<?php if( 0 < count( $regions ) ): ?>
			<div class="box location">
				<h3><strong><?php _e("Location", 'modern'); ?></strong></h3>
				<ul>
				<?php foreach( $regions as $region ): ?>
				<li><a href="<?php echo $searchUrl->osc_search_url(array('sRegion' => osc_list_region_name( $region ) ) ); ?>"><?php echo osc_list_region_name( $region ); ?></a> <em>(<?php echo osc_list_region_items( $region ); ?>)</em></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
echo $view->render( 'footer' );

