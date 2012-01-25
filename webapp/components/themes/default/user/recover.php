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
$userForm = ClassLoader::getInstance()->getClassInstance( 'Form_User' );

echo $view->render( 'header' );
?>

            <div class="content user_forms">
                <div class="inner">
                    <h1><?php _e('Recover your password', 'modern'); ?></h1>
		    <form action="<?php echo osc_base_url(true); ?>" method="post" >
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="recover" />
                        <fieldset>
                            <label for="email"><?php _e('E-mail', 'modern'); ?></label> <?php $userForm->email_text(); ?><br />
                            <?php osc_show_recaptcha('recover_password'); ?>
                            <button type="submit"><?php _e('Send me a new password', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

