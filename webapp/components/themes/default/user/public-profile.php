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
$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
$resourceUrls = $classLoader->getClassInstance( 'Url_Resource' );
$contactForm = $classLoader->getClassInstance( 'Form_Contact' );
$address = '';
if (osc_user_address( $user ) != '') 
{
	if (osc_user_city_area() != '') 
	{
		$address = osc_user_address( $user ) . ", " . osc_user_city_area();
	}
	else
	{
		$address = osc_user_address( $user );
	}
}
else
{
	$address = osc_user_city_area( $user );
}
$location_array = array();
if (trim(osc_user_city( $user ) . " " . osc_user_zip( $user )) != '') 
{
	$location_array[] = trim(osc_user_city( $user ) . " " . osc_user_zip( $user ));
}
if (osc_user_region( $user ) != '') 
{
	$location_array[] = osc_user_region( $user );
}
if (osc_user_country( $user ) != '') 
{
	$location_array[] = osc_user_country( $user );
}
$location = implode(", ", $location_array);
unset($location_array);

echo $view->render( 'header' );
?>

            <div class="content item">
                <div id="item_head">
                    <div class="inner">
                        <h1><?php echo sprintf(__('%s\'s profile', 'modern'), osc_user_name( $user )); ?></h1>
                    </div>
                </div>
                <div id="main">
                    <br/>
                    <div id="description">
                    <h2><?php _e('Profile'); ?></h2>
                        <ul id="user_data">
                            <li><?php _e('Full name'); ?>: <?php echo osc_user_name( $user ); ?></li>
                            <li><?php _e('Address'); ?>: <?php echo $address; ?></li>
                            <li><?php _e('Location'); ?>: <?php echo $location; ?></li>
                            <li><?php _e('Website'); ?>: <?php echo osc_user_website( $user ); ?></li>
                        </ul>
                    </div>
                    <div id="description">
                        <h2><?php _e('Latest items'); ?></h2>
                        <table border="0" cellspacing="0">
                            <tbody>
                                <?php $class = "even"; ?>
                                <?php foreach( $items as $item ): ?>
                                    <tr class="<?php echo $class; ?>">
                                        <?php if (osc_images_enabled_at_items())  { ?>
                                         <td class="photo">
                                             <?php if (false&&osc_count_item_resources( $item ))  { ?>
                                                <a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><img src="<?php echo $resourceUrls->osc_resource_thumbnail_url( $resource ); ?>" width="75px" height="56px" title="" alt="" /></a>
                                            <?php } else { ?>
                                                <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" title="" alt="" />
                                            <?php } ?>
                                         </td>
                                         <?php } ?>
                                         <td class="text">
                                             <h3>
                                                 <a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><?php echo osc_item_title( $item ); ?></a>
                                             </h3>
                                             <p>
                                                 <strong><?php
	if (osc_price_enabled_at_items()) 
	{
		echo osc_item_formated_price( $item ); ?> - <?php
	} 
	echo osc_item_city( $item ); ?> (<?php echo osc_item_region( $item ); ?>) - <?php echo osc_format_date( $item, osc_item_pub_date( $item )); ?></strong>
                                             </p>
                                             <p><?php echo osc_highlight(strip_tags(osc_item_description( $item ))); ?></p>
                                         </td>
                                     </tr>
                                    <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="sidebar">
                    <?php if (osc_logged_user_id() != osc_user_id( $user ))  { ?>
                    <?php if (osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact())  { ?>
                    <div id="contact">
                        <h2><?php _e("Contact publisher", 'modern'); ?></h2>
                        <p class="name"><?php _e('Name', 'modern') ?>: <?php echo osc_user_name( $user ); ?></p>
                        <?php if (osc_item_show_email( $user ))  { ?>
                        <p class="email"><?php
			_e('E-mail', 'modern'); ?>: <?php echo osc_user_email( $user ); ?></p>
                        <?php } ?>
                        <?php if (osc_user_phone( $user ) != '')  { ?>
                        <p class="phone"><?php _e("Tel", 'modern'); ?>.: <?php echo osc_user_phone( $user ); ?></p>
                        <?php } ?>
                        <ul id="error_list"></ul>
                        <form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="post" name="contact_form" id="contact_form">
                            <input type="hidden" name="action" value="contact" />
                            <input type="hidden" name="page" value="user" />
                            <input type="hidden" name="id" value="<?php echo osc_user_id( $user ); ?>" />
                            <fieldset>
                                <label for="yourName"><?php _e('Your name', 'modern'); ?>:</label> <?php $contactForm->your_name(); ?>
                                <label for="yourEmail"><?php _e('Your e-mail address', 'modern'); ?>:</label> <?php $contactForm->your_email(); ?>
                                <label for="phoneNumber"><?php _e('Phone number', 'modern'); ?> (<?php _e('optional', 'modern'); ?>):</label> <?php $contactForm->your_phone_number(); ?>
                                <label for="message"><?php _e('Message', 'modern'); ?>:</label> <?php $contactForm->your_message(); ?>
                                <?php if (osc_recaptcha_public_key()) { ?>
                                <script type="text/javascript">
                                    var RecaptchaOptions = {
                                        theme : 'custom',
                                        custom_theme_widget: 'recaptcha_widget'
                                    };
                                </script>
                                <style type="text/css"> div#recaptcha_widget, div#recaptcha_image > img { width:280px; } </style>
                                <div id="recaptcha_widget">
                                    <div id="recaptcha_image"><img /></div>
                                    <span class="recaptcha_only_if_image"><?php _e('Enter the words above', 'modern'); ?>:</span>
                                    <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                                    <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'modern'); ?></a></div>
                                </div>
                                <?php } ?>
                                <?php osc_show_recaptcha(); ?>
                                <button type="submit"><?php _e('Send', 'modern'); ?></button>
                            </fieldset>
                        </form>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

