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

echo $view->render( 'header' );
?>

            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'modern'); ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu(); ?>
                </div>
                <div id="main" class="modify_profile">
                    <h2><?php _e('Change your password', 'modern'); ?></h2>
                    <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post">
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="change_password" />
                        <fieldset>
                            <p>
                                <label for="password"><?php _e('Current password', 'modern'); ?> *</label>
                                <input type="password" name="password" id="password" value="" />
                            </p>
                            <p>
                                <label for="new_password"><?php _e('New password', 'modern'); ?> *</label>
                                <input type="password" name="new_password" id="new_password" value="" />
                            </p>
                            <p>
                                <label for="new_password2"><?php _e('Repeat new password', 'modern'); ?> *</label>
                                <input type="password" name="new_password2" id="new_password2" value="" />
                            </p>
                            <div style="clear:both;"></div>
                            <button type="submit"><?php _e('Update', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

