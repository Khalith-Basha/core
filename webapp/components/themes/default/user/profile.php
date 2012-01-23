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

            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'modern'); ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu(); ?>
                </div>
                <div id="main" class="modify_profile">
                    <h2><?php _e('Update your profile', 'modern'); ?></h2>
                    <form action="<?php echo osc_base_url(true); ?>" method="post">
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="profile" />
                        <fieldset>
                            <div class="row">
                                <label for="name"><?php _e('Name', 'modern'); ?></label>
                                <?php $userForm->name_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="email"><?php _e('E-mail', 'modern'); ?></label>
                                <span class="update">
                                    <?php echo osc_user_email(); ?><br />
                                    <a href="<?php echo osc_change_user_email_url(); ?>"><?php
_e('Modify e-mail', 'modern'); ?></a> <a href="<?php echo osc_change_user_password_url(); ?>" ><?php
_e('Modify password', 'modern'); ?></a>
                                </span>
                            </div>
                            <div class="row">
                                <label for="user_type"><?php _e('User type', 'modern'); ?></label>
                                <?php $userForm->is_company_select(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="phoneMobile"><?php _e('Cell phone', 'modern'); ?></label>
                                <?php $userForm->mobile_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="phoneLand"><?php _e('Phone', 'modern'); ?></label>
                                <?php $userForm->phone_land_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="country"><?php
_e('Country', 'modern'); ?> *</label>
                                <?php $userForm->country_select(osc_get_countries(), osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="region"><?php
_e('Region', 'modern'); ?> *</label>
                                <?php $userForm->region_select(osc_get_regions(), osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="city"><?php
_e('City', 'modern'); ?> *</label>
                                <?php $userForm->city_select(osc_get_cities(), osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="city_area"><?php
_e('City area', 'modern'); ?></label>
                                <?php
$userForm->city_area_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="address"><?php
_e('Address', 'modern'); ?></label>
                                <?php
$userForm->address_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <label for="webSite"><?php
_e('Website', 'modern'); ?></label>
                                <?php
$userForm->website_text(osc_user()); ?>
                            </div>
                            <div class="row">
                                <button type="submit"><?php
_e('Update', 'modern'); ?></button>
                            </div>
                            <?php
osc_run_hook('user_form'); ?>
                        </fieldset>
                    </form>
                </div>
            </div>
<?php
echo $view->render( 'footer' );

