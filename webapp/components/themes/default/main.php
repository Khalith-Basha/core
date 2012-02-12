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
$searchUrl = $classLoader->getClassInstance( 'Url_Search' );

$total_categories = osc_count_categories();
$col1_max_cat = ceil($total_categories / 3);
$col2_max_cat = ceil(($total_categories - $col1_max_cat) / 2);
$col3_max_cat = $total_categories - ($col1_max_cat + $col2_max_cat);
$i = 1;
$x = 1;
$col = 1;

echo $view->render( 'header' );
?>
            <div id="form_publish">
                <?php echo $view->render( 'search/form' ); ?>
            </div>
            <div class="content home">
                <div id="main">
                    <div class="categories <?php echo 'c' . $total_categories; ?>">
                        <?php
if (osc_count_categories() > 0) 
{
	echo '<div class="col c1">';
}
?>
			<?php foreach( $categories as $category ): ?>
                            <div class="category">
                                <h1><strong><a class="category <?php echo osc_category_field( $category, 's_slug' ); ?>" href="<?php echo $searchUrl->osc_search_category_url( $category ); ?>"><?php echo osc_category_field( $category, 's_name' ); ?></a> <span>(<?php echo osc_category_field( $category, 'i_num_items' ); ?>)</span></strong></h1>
                                <?php if( count( $category['categories'] ) > 0 ): ?>
				    <ul>
					<?php foreach( $category['categories'] as $subCategory ): ?>
                                            <li><a class="category <?php echo osc_category_field( $subCategory, 's_slug' ); ?>" href="<?php echo $searchUrl->osc_search_category_url( $subCategory ); ?>"><?php echo osc_category_field( $subCategory, 's_name' ); ?></a> <span>(<?php echo osc_category_field( $subCategory, 'i_num_items' ); ?>)</span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <?php
	if (($col == 1 && $i == $col1_max_cat) || ($col == 2 && $i == $col2_max_cat) || ($col == 3 && $i == $col3_max_cat)) 
	{
		$i = 1;
		$col++;
		echo '</div>';
		if ($x < $total_categories) 
		{
			echo '<div class="col c' . $col . '">';
		}
	}
	else
	{
		$i++;
	}
	$x++;
?>
                        <?php endforeach; ?>
                   </div>

                   <div class="latest_ads">
                        <h1><strong><?php _e('Latest Items', 'modern'); ?></strong></h1>
                        <?php if( count( $latestItems ) === 0 ): ?>
                            <p class="empty"><?php _e('No Latest Items', 'modern'); ?></p>
                        <?php else: ?>
                            <table border="0" cellspacing="0">
                                 <tbody>
				    <?php $class = "even"; ?>
					<?php foreach( $latestItems as $item ): ?>
                                     <tr class="<?php echo $class . (osc_item_is_premium( $item ) ? " premium" : ""); ?>">
                                            <?php if( osc_images_enabled_at_items() ): ?>
                                             <td class="photo">
                                                <?php if (osc_count_item_resources( $item )): ?>
                                                    <a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><img src="<?php echo osc_resource_thumbnail_url( $item ); ?>" width="75px" height="56px" title="" alt="" /></a>
                                                <?php else: ?>
                                                    <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" alt="" title=""/>
                                                <?php endif; ?>
                                             </td>
                                            <?php endif; ?>
                                             <td class="text">
                                                 <h3><a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><?php echo osc_item_title( $item ); ?></a></h3>
                                                 <p><strong><?php if (osc_price_enabled_at_items()) { echo osc_item_formated_price( $item ); ?> - <?php } echo osc_item_city( $item ); ?> (<?php echo osc_item_region( $item ); ?>) - <?php echo osc_format_date( osc_item_pub_date( $item ) ); ?></strong></p>
                                                 <p><?php echo osc_highlight( strip_tags( osc_item_description( $item ) ) ); ?></p>
                                             </td>                                       
                                         </tr>
                                        <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
				    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if( count( $latestItems ) === osc_max_latest_items() ): ?>
                                <p class="see_more_link"><a href="<?php echo osc_search_show_all_url(); ?>"><strong><?php _e("See all offers", 'modern'); ?> &raquo;</strong></a></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
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

