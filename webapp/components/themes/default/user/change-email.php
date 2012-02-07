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
                    <h2><?php _e('Change your e-mail', 'modern'); ?></h2>
                    <form action="<?php echo osc_base_url(true); ?>" method="post">
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="change_email" />
                        <fieldset>
                            <p>
                                <label for="email"><?php _e('Current e-mail', 'modern'); ?></label>
                                <span><?php echo osc_logged_user_email(); ?></span>
                            </p>
                            <p>
                                <label for="new_email"><?php _e('New e-mail', 'modern'); ?> *</label>
                                <input type="text" name="new_email" id="new_email" value="" />
                            </p>
                            <div style="clear:both;"></div>
                            <button type="submit"><?php _e('Update', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

