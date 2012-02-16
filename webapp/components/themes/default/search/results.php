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

$searchUrl = $classLoader->getClassInstance( 'Url_Search' );
$resourceUrls = $classLoader->getClassInstance( 'Url_Resource' );
echo $view->render( 'header' );
?>

            <div class="content list">
                <div id="main">
                    <div class="ad_list">
                        <div id="list_head">
                            <div class="inner">
                                <h1>
                                    <strong><?php _e('Search results', 'modern'); ?></strong>
                                </h1>
                                <p class="see_by">
                                    <?php _e('Sort by', 'modern'); ?>:
                                    <?php
$i = 0; ?>
                                    <?php
$orders = osc_list_orders();
foreach ($orders as $label => $params) 
{
	$orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
                                        <?php
	if (osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) 
	{ ?>
                                            <a class="current" href="<?php echo $searchUrl->osc_update_search_url($params); ?>"><?php echo $label; ?></a>
                                        <?php
	}
	else
	{ ?>
                                            <a href="<?php echo $searchUrl->osc_update_search_url($params); ?>"><?php echo $label; ?></a>
                                        <?php } ?>
<?php
	if ($i != count($orders) - 1) 
	{ ?>
                                            <span>|</span>
                                        <?php } ?>
                                        <?php
	$i++; ?>
                                    <?php
} ?>
                                </p>
                            </div>
                        </div>
                        <?php
if (osc_count_items() == 0) 
{ ?>
                            <p class="empty" ><?php
	printf(__('There are no results matching "%s"', 'modern'), osc_search_pattern()); ?></p>
                        <?php
}
else
{ ?>
                            <?php require (osc_search_show_as() == 'list' ? 'list.php' : 'gallery.php'); ?>
                        <?php } ?>
			<div class="paginate"><?php echo $pagination->showLinks(); ?></div>
                    </div>
                </div>
                <div id="sidebar">
                    <div class="filters">
                        <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="get" onSubmit="return checkEmptyCategories()">
                            <input type="hidden" name="page" value="search" />
                            <fieldset class="box location">
                                <h3><strong><?php _e('Your search', 'modern'); ?></strong></h3>
                                <div class="row one_input">
                                    <input type="text" name="sPattern"  id="query" value="<?php echo osc_search_pattern(); ?>" />
                                </div>
                                <h3><strong><?php _e('Location', 'modern'); ?></strong></h3>
                                <div class="row one_input">
                                    <h6><?php _e('Region', 'modern'); ?></h6>
                                    <input type="text" id="sRegion" name="sRegion" value="<?php echo osc_search_region(); ?>" />
                                    <h6><?php _e('City', 'modern'); ?></h6>
                                    <input type="text" id="sCity" name="sCity" value="<?php echo osc_search_city(); ?>" />
                                </div>
                            </fieldset>

                            <fieldset class="box show_only">
                                <?php if (osc_images_enabled_at_items()): ?>
                                <h3><strong><?php _e('Show only', 'modern'); ?></strong></h3>
                                <div class="row checkboxes">
                                    <ul>
                                        <li>
                                            <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked' : ''); ?> />
                                            <label for="withPicture"><?php _e('Show only items with pictures', 'modern'); ?></label>
                                        </li>
                                    </ul>
                                </div>
				<?php endif; ?>
                                <?php if (osc_price_enabled_at_items())  { ?>
                                <div class="row two_input">
                                    <h6><?php _e('Price', 'modern'); ?></h6>
                                    <div><?php _e('Min', 'modern'); ?>.</div>
                                    <input type="text" id="priceMin" name="sPriceMin" value="<?php echo osc_search_price_min(); ?>" size="6" maxlength="6" />
                                    <div><?php _e('Max', 'modern'); ?>.</div>
                                    <input type="text" id="priceMax" name="sPriceMax" value="<?php echo osc_search_price_max(); ?>" size="6" maxlength="6" />
                                </div>
                                <?php } ?>
                                <?php osc_get_non_empty_categories(); ?>
                                <?php if (osc_count_categories())  { ?>
                                    <div class="row checkboxes">
                                        <h6><?php _e('Category', 'modern'); ?></h6>
                                        <ul>
						<?php foreach( $classLoader->getClassInstance( 'Model_Category' )->toTree() as $category ): ?>
                                                <li>
                                                    <input type="checkbox" id="cat<?php echo osc_category_id( $category ); ?>" name="sCategory[]" value="<?php echo osc_category_id( $category ); ?>" <?php echo ((in_array(osc_category_id( $category ), osc_search_category( $category )) || in_array(osc_category_slug( $category ) . "/", osc_search_category( $category )) || count(osc_search_category( $category )) == 0) ? 'checked' : ''); ?> /> <label for="cat<?php echo osc_category_id( $category ); ?>"><strong><?php echo osc_category_name( $category ); ?></strong></label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </fieldset>

                            <?php
if (osc_search_category_id()) 
{
	osc_run_hook('search_form', osc_search_category_id());
} else {
	osc_run_hook('search_form');
}
?>

                            <button type="submit"><?php _e('Apply', 'modern'); ?></button>
                        </form>
                        <?php osc_alert_form(); ?>
                    </div>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

