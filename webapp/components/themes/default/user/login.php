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
$indexUrl = $classLoader->getClassInstance( 'Url_Index' );
$userForm = $classLoader->getClassInstance( 'Form_User' );
echo $view->render( 'header' );
?>

            <div class="content user_forms">
                <div class="inner">
                    <h1><?php _e('Access to your account', 'modern'); ?></h1>
                    <form action="<?php echo $indexUrl->getBaseUrl(true); ?>" method="post" >
			<?php
			echo $userForm->getInputHidden( 'page', 'user' );
			echo $userForm->getInputHidden( 'action', 'login_post' );
			?>
                        <fieldset>
                            <label for="email"><?php _e('E-mail', 'modern'); ?></label> <?php $userForm->email_login_text(); ?><br />
                            <label for="password"><?php _e('Password', 'modern'); ?></label> <?php $userForm->password_login_text(); ?><br />
				<p class="checkbox"><?php echo $userForm->getDecoratedInputCheckbox( 'remember', '1', __( 'Remember me', 'modern' ) ); ?></p>
                            <button type="submit"><?php _e("Log in", 'modern'); ?></button>
                            <div class="more-login">
                                <a href="<?php echo osc_register_account_url(); ?>"><?php _e("Register for a free account", 'modern'); ?></a> · <a href="<?php echo osc_recover_user_password_url(); ?>"><?php _e("Forgot password?", 'modern'); ?></a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

