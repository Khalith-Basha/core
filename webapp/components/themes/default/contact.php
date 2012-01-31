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
$contactForm = $classLoader->getClassInstance( 'Form_Contact' );

echo $view->render( 'header' );
?>

    <div class="content user_forms">
	<div class="inner">
	    <h1><?php _e('Contact us', 'modern'); ?></h1>
	    <ul id="error_list"></ul>
	    <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact" id="contact">
		<input type="hidden" name="page" value="contact" />
		<fieldset>
		    <label for="subject"><?php _e('Subject', 'modern'); ?> (<?php _e('optional', 'modern'); ?>)</label> <?php $contactForm->the_subject(); ?><br />
		    <label for="message"><?php _e('Message', 'modern'); ?></label> <?php $contactForm->your_message(); ?><br />
		    <label for="yourName"><?php _e('Your name', 'modern'); ?> (<?php _e('optional', 'modern'); ?>)</label> <?php $contactForm->your_name(); ?><br />
		    <label for="yourEmail"><?php _e('Your e-mail address', 'modern'); ?></label> <?php $contactForm->your_email(); ?><br />
		    <?php osc_show_recaptcha(); ?>
		    <button type="submit"><?php _e('Send', 'modern'); ?></button>
		    <?php osc_run_hook('user_register_form'); ?>
		</fieldset>
	    </form>
	</div>
    </div>

<?php 
echo $view->render( 'footer' );

