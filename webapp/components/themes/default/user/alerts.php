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
echo $view->render( 'header' );
?>

            <div class="content user_account">
                <h1><strong><?php _e('User account manager', 'modern'); ?></strong></h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu(); ?>
                </div>
                <div id="main">
                    <h2><?php _e('Your alerts', 'modern'); ?></h2>
                    <?php if (osc_count_alerts() == 0)  { ?>
                        <h3><?php _e('You do not have any alerts yet', 'modern'); ?>.</h3>
                    <?php } else { ?>
                        <?php while (osc_has_alerts())  { ?>
                            <div class="userItem" >
                                <div><?php
		_e('Alert', 'modern'); ?> | <a onclick="javascript:return confirm('<?php
		_e('This action can\'t be undone. Are you sure you want to continue?', 'modern'); ?>');" href="<?php
		echo osc_user_unsubscribe_alert_url(); ?>"><?php
		_e('Delete this alert', 'modern'); ?></a></div>
                                <div style="width: 75%; padding-left: 100px;" >
                                <?php while (osc_has_items())  { ?>
                                    <div class="userItem" >
                                        <div><a href="<?php echo osc_item_url( $item ); ?>"><?php echo osc_item_title( $item ); ?></a></div>
                                        <div class="userItemData" >
                                        <?php _e('Publication date', 'modern'); ?>: <?php echo osc_format_date( osc_item_pub_date( $item ) ); ?><br />
                                        <?php if (osc_price_enabled_at_items()) { _e('Price', 'modern'); ?>: <?php echo osc_format_price(osc_item_price()); } ?>
                                        </div>
                                    </div>
                                    <br />
                                <?php } ?>
                                </div>
                            </div>
                            <br />
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

