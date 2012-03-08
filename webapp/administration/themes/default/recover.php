<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

echo $view->render( 'header-simple' );
?>

<div class="message warning" style="text-align:center;">
	<?php _e('Please enter your username or e-mail address'); ?>.<br />
	<?php _e('You will receive a new password via e-mail'); ?>.
</div>

<form action="<?php echo osc_admin_base_url(true); ?>" method="post">
	<input type="hidden" name="action" value="recover" />
	<p>
	<label><?php _e('E-mail'); ?><br />
	<input type="email" name="email" id="user_email" class="input" value="" size="20" tabindex="10" autofocus="autofocus" required="required" /></label>
	</p>
	<?php osc_show_recaptcha(); ?>
	<p class="submit"><input type="submit" name="submit" id="submit" value="<?php _e('Get new password'); ?>" tabindex="100" /></p>
</form>

<p id="nav">
	<a title="<?php _e('Log in'); ?>" href="<?php echo osc_admin_base_url(); ?>"><?php _e('Log in'); ?></a>
</p>

<?php
echo $view->render( 'footer-simple' );

