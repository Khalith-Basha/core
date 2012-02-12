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
$userForm = $classLoader->getClassInstance( 'Form_User' );
$classLoader->loadFile( 'helpers/security' );
echo $view->render( 'header' );
?>

            <div class="content user_forms">
                <div class="inner">
                    <h1><?php _e('Register an account for free', 'modern'); ?></h1>
                    <ul id="error_list"></ul>
                    <form name="register" id="register" action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" >
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="register_post" />
                        
                        <fieldset>
                            <label for="name"><?php _e('Name', 'modern'); ?></label> <?php $userForm->name_text(); ?><br />
                            <label for="password"><?php _e('Password', 'modern'); ?></label> <?php $userForm->password_text(); ?><br />
                            <label for="password"><?php _e('Re-type password', 'modern'); ?></label> <?php $userForm->check_password_text(); ?><br />
                            <p id="password-error" style="display:none;">
                                <?php _e('Passwords don\'t match', 'modern'); ?>.
                            </p>
                            <label for="email"><?php _e('E-mail', 'modern'); ?></label> <?php $userForm->email_text(); ?><br />
                            <?php osc_run_hook('user_register_form'); ?>
                            <?php osc_show_recaptcha('register'); ?>
                            <button type="submit"><?php _e('Create', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>

<?php
echo $view->render( 'footer' );

