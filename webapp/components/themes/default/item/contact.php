<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
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

$contactForm = ClassLoader::getInstance()->getClassInstance( 'Form_Contact' );
echo $view->render( 'header' );
?>

            <div class="content user_forms">
                <div id="contact" class="inner">
                    <h1><?php _e('Contact seller', 'modern'); ?></h1>
                    <ul id="error_list"></ul>
                    <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" name="contact_form" id="contact_form" >
                        <fieldset>
                            <?php $contactForm->primary_input_hidden(); ?>
                            <?php $contactForm->action_hidden(); ?>
                            <?php $contactForm->page_hidden(); ?>
                            <label><?php _e('To (seller)', 'modern'); ?>: <?php echo osc_item_contact_name(); ?></label><br/>
                            <label><?php _e('Item', 'modern'); ?>: <a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a></label><br/>
                            <?php if (osc_is_web_user_logged_in())  { ?>
                                <input type="hidden" name="yourName" value="<?php echo osc_logged_user_name(); ?>" />
                                <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email(); ?>" />
                            <?php
}
else
{ ?>
                                <label for="yourName"><?php _e('Your name', 'modern'); ?></label> <?php $contactForm->your_name(); ?><br/>
                                <label for="yourEmail"><?php  _e('Your e-mail address', 'modern'); ?></label> <?php 	$contactForm->your_email(); ?><br />
                            <?php }; ?>
                            <label for="phoneNumber"><?php _e('Phone number', 'modern'); ?> (<?php _e('optional', 'modern'); ?>)</label> <?php $contactForm->your_phone_number(); ?><br/>
                            <label for="message"><?php _e('Message', 'modern'); ?></label> <?php $contactForm->your_message(); ?><br />
                            <?php osc_show_recaptcha(); ?>
                            <button type="submit"><?php _e('Send message', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

