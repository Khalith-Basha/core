<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
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
?>
<!DOCTYPE HTML>
<html>
<html dir="ltr" lang="en-US">
    <head>
	<meta charset="utf8" />
        <title><?php _e('OpenSourceClassifieds admin panel login'); ?></title>
        <script type="text/javascript" src="<?php echo osc_admin_base_url(); ?>/themes/modern/js/jquery.js"></script>
	<link type="text/css" href="<?php echo osc_admin_base_url( false ) . '/static/styles/backoffice_login.css'; ?>" media="screen" rel="stylesheet" />
    </head>
    <body class="login">
        <div id="login">
            <h1>
                <a href="<?php echo osc_base_url(); ?>" title="OpenSourceClassifieds"><img src="images/osclass-logo.png" border="0" title="" alt=""/></a>
            </h1>
            <?php osc_show_flash_message('admin'); ?>
            <form name="loginform" id="loginform" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                <input type="hidden" name="page" value="index" />
                <input type="hidden" name="action" value="login" />
                <p>
                    <label>
                        <?php _e('Username'); ?>
			<input type="text" name="user" id="user_login" class="input" size="20" tabindex="10" autofocus="autofocus" required="required" />
                    </label>
                </p>
                <p>
                    <label>
                        <?php _e('Password'); ?>
                        <input type="password" name="password" id="user_pass" class="input" size="20" tabindex="20" required="required" />
                    </label>
                </p>

                <?php $locales = osc_all_enabled_locales_for_admin(); ?>
                <?php if (count($locales) > 1)  { ?>
                    <p>
                        <label><?php _e('Language'); ?><br />
                            <select name="locale" id="user_language">
                                <?php foreach ($locales as $locale)  { ?>
                                    <option value="<?php echo $locale['pk_c_code']; ?>" <?php if (osc_admin_language() == $locale['pk_c_code']) echo 'selected="selected"'; ?>><?php echo $locale['s_short_name']; ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </p>
                <?php } else { ?>
                    <input type="hidden" name="locale" value="<?php echo $locales[0]["pk_c_code"]; ?>" />
                <?php } ?>
                    
                <p class="forgetmenot">
                    <label>
                        <input name="remember" type="checkbox" id="remember" value="1" tabindex="90" /> <?php _e('Remember me'); ?>
                    </label>
                </p>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                </p>
            </form>

            <p id="nav">
                <a href="<?php echo osc_admin_base_url(true); ?>?action=recover" title="<?php _e('Forgot your password?'); ?>"><?php _e('Forgot your password?'); ?></a>
            </p>
        </div>
        <p id="backtoblog"><a href="<?php echo osc_base_url(); ?>" title="<?php _e('Back to') . ' ' . osc_page_title(); ?>">&larr; <?php _e('Back to'); ?> <?php echo osc_page_title(); ?></a></p>
    </body>
</html>
