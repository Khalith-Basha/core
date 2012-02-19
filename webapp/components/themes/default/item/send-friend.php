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
$sendFriendForm = $classLoader->getClassInstance( 'Form_SendFriend' );
echo $view->render( 'header');
?>
            <div class="content user_forms">
                <div id="contact" class="inner">
                    <h1><?php _e('Send to a friend', 'modern'); ?></h1>
                    <ul id="error_list"></ul>
                    <form id="sendfriend" name="sendfriend" action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post">
                        <fieldset>
                            <input type="hidden" name="action" value="send_friend" />
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="id" value="<?php echo osc_item_id( $item ); ?>" />
                            <label><?php _e('Item', 'modern'); ?>: <a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><?php echo osc_item_title( $item ); ?></a></label><br/>
                            <?php if (osc_is_web_user_logged_in())  { ?>
                                <input type="hidden" name="yourName" value="<?php echo osc_logged_user_name(); ?>" />
                                <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email(); ?>" />
                            <?php } else { ?>
                                <label for="yourName"><?php _e('Your name', 'modern'); ?></label> <?php $sendFriendForm->your_name(); ?> <br/>
                                <label for="yourEmail"><?php _e('Your e-mail address', 'modern'); ?></label> <?php $sendFriendForm->your_email(); ?> <br/>
                            <?php }; ?>
                            <label for="friendName"><?php _e("Your friend's name", 'modern'); ?></label> <?php $sendFriendForm->friend_name(); ?> <br/>
                            <label for="friendEmail"><?php _e("Your friend's e-mail address", 'modern'); ?></label> <?php $sendFriendForm->friend_email(); ?> <br/>
                            <label for="message"><?php _e('Message', 'modern'); ?></label> <?php $sendFriendForm->your_message(); ?> <br/>
                            <?php osc_show_recaptcha(); ?>
                            <br/>
                            <button type="submit"><?php _e('Send', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

