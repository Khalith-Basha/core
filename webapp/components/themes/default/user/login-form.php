<?php
$indexUrl = $classLoader->getClassInstance( 'Url_Index' );
$userForm = $classLoader->getClassInstance( 'Form_User' );
$userUrls = $classLoader->getClassInstance( 'Url_User' );
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
                                <a href="<?php echo $userUrls->osc_register_account_url(); ?>"><?php _e("Register for a free account", 'modern'); ?></a> · <a href="<?php echo $userUrls->osc_recover_user_password_url(); ?>"><?php _e("Forgot password?", 'modern'); ?></a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

