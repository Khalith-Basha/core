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

            <div class="content user_forms">
                <div class="inner">
                    <h1><?php _e('Recover your password', 'modern'); ?></h1>
                    <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" >
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="forgot" />
                        <input type="hidden" name="userId" value="<?php echo Params::getParam('userId'); ?>" />
                        <input type="hidden" name="code" value="<?php echo Params::getParam('code'); ?>" />
                        <fieldset>
                            <p>
                                <label for="new_email"><?php _e('New pasword', 'modern'); ?></label><br />
                                <input type="password" name="new_password" value="" />
                            </p>
                            <p>
                                <label for="new_email"><?php _e('Repeat new pasword', 'modern'); ?></label><br />
                                <input type="password" name="new_password2" value="" />
                            </p>
                            <button type="submit"><?php _e('Change password', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

