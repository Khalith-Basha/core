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
$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
echo $view->render( 'header' );
?>

            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'modern'); ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu(); ?>
                </div>
                <div id="main">
                    <h2><?php _e('Your items', 'modern'); ?> <a href="<?php echo $itemUrls->osc_item_post_url(); ?>">+ <?php _e('Post a new item', 'modern'); ?></a></h2>
                    <?php if( 0 === count( $items ) )  { ?>
                        <h3><?php _e('You don\'t have any items yet', 'modern'); ?></h3>
		    <?php } else { ?>
			<?php foreach( $items as $item ): ?>
                                <div class="item" >
                                        <h3>
                                            <a href="<?php echo $itemUrls->osc_item_url( $item ); ?>"><?php echo osc_item_title( $item ); ?></a>
                                        </h3>
                                        <p>
                                        <?php _e('Publication date', 'modern'); ?>: <?php echo osc_format_date( osc_item_pub_date( $item ) ); ?><br />
                                        <?php if (osc_price_enabled_at_items())  { _e('Price', 'modern'); ?>: <?php echo osc_format_price(osc_item_price( $item )); } ?>
                                        </p>
                                        <p class="options">
                                            <strong><a href="<?php echo $itemUrls->osc_item_edit_url( $item ); ?>"><?php _e('Edit', 'modern'); ?></a></strong>
                                            <span>|</span>
                                            <a class="delete" onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?', 'modern'); ?>')" href="<?php echo $itemUrls->osc_item_delete_url( $item ); ?>" ><?php _e('Delete', 'modern'); ?></a>
                                            <?php if (osc_item_is_inactive())  { ?>
                                            <span>|</span>
                                            <a href="<?php echo $itemUrls->osc_item_activate_url( $item ); ?>" ><?php _e('Activate', 'modern'); ?></a>
                                            <?php } ?>
                                        </p>
                                        <br />
                                </div>
                        <?php endforeach; ?>
                        <br />
			<div class="paginate">
			<?php foreach( $pagination->getPages() as $page ): ?>
			<a class="<?php echo $page['selected'] ? 'searchPaginationSelected' : 'searchPaginationNonSelected'; ?>" href="%s">%d</a>
			<?php endforeach; ?>
			</div>
			osc_list_total_pages()
			osc_user_list_items_url($i)
                    <?php } ?>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

