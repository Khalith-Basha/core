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
$new_item = __get("new_item");
$itemForm = ClassLoader::getInstance()->getClassInstance( 'Form_Item' );
?>

        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{display:none;}<\/style>');
$(document).ready(function(){

		$( 'a#lowercaseTitle' ).click( function( e )
			{
				$( 'input[id^="title"]' ).each( function()
					{
						this.value = this.value.toLowerCase();
					}
				);
				e.preventDefault();
			}
		);
		$( 'a#lowercaseDescription' ).click( function( e )
			{
				$( 'textarea[id^="description"]' ).each( function()
					{
						this.value = this.value.toLowerCase();
					}
				);
				e.preventDefault();
			}
		);


                $("#userId").change(function(){
                    if($(this).val()=='') {
                        $("#contact_info").show();
                    } else {
                        $("#contact_info").hide();
                    }
                });
                if($($("#userId")).val()=='') {
                    $("#contact_info").show();
                } else {
                    $("#contact_info").hide();
                }
            });
        </script>
                <?php osc_show_flash_message('admin'); ?>
                <div class="content_header" id="content_header">
                    <div style="float: left;">
                        <img alt="" title="" src="<?php echo osc_current_admin_theme_url('images/new-folder-icon.png'); ?>">
                    </div>
                    <div id="content_header_arrow">» <?php
if ($new_item) 
{
	_e('New item');
}
else
{
	_e('Edit item');
} ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="add_item_form" class="item-form">
                    <h1 style="display: none;"><?php
if ($new_item) 
{
	_e('New item');
}
else
{
	_e('Edit item');
} ?></h1>
                    <ul id="error_list"></ul>
                    <form name="item" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data" >
                        <input type="hidden" name="page" value="item" />
                        <?php
if ($new_item) 
{ ?>
                            <input type="hidden" name="action" value="post" />
                        <?php
}
else
{ ?>
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
                            <input type="hidden" name="secret" value="<?php echo osc_item_secret(); ?>" />
                        <?php
}; ?>

                        <div class="user-post">
                            <h2><?php _e('User'); ?></h2>
                            <label><?php _e('Item posted by'); ?></label>
                            <?php $itemForm->user_select(null, null, __('Non-registered user')); ?>
                            <div  id="contact_info">
                                <label for="contactName"><?php _e('Name'); ?></label>
                                <?php $itemForm->contact_name_text(); ?><br/>
                                <label for="contactEmail"><?php _e('E-Mail'); ?></label>
                                <?php $itemForm->contact_email_text(); ?>
                            </div>
                        </div>
                        <h2>
                            <?php _e('General information'); ?>
                        </h2>
                        <label for="catId">
                            <?php _e('Category') ?>:
                        </label>
                        <?php $itemForm->category_select(); ?>

                        <?php
$itemForm->multilanguage_title_description(osc_get_locales()); ?>

                        <?php
if (osc_price_enabled_at_items()) 
{ ?>
                            <div class="_200 auto">
                                <h2><?php _e('Price'); ?></h2>
                                <?php $itemForm->price_input_text(); ?>
                                <?php $itemForm->currency_select(); ?>
                            </div>
                        <?php
} ?>

                        <?php
if (osc_images_enabled_at_items() && false) 
{ ?>
                            <div class="photos">
                                <h2><?php _e('Photos'); ?></h2>
                                <?php $itemForm->photos(); ?>
                                <div id="photos">
                                    
                                    <?php
	if (osc_max_images_per_item() == 0 || (osc_max_images_per_item() != 0 && osc_count_item_resources() < osc_max_images_per_item())) 
	{ ?>
                                    <div>
                                        <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                                    </div>
                                    <?php
	}; ?>
                                </div>
                                <p><a style="font-size: small;" href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a></p>
                            </div>
                        <?php
} ?>

                        <div class="location-post _200 clear">
                            <!-- location info -->
                            <h2><?php _e('Location'); ?></h2>
                            <div class="row">
                                <label><?php _e('Country'); ?></label>
                                <?php $itemForm->country_select(); ?>
                            </div>
                            <div class="row">
                                <label><?php _e('Region'); ?></label>
                                <?php $itemForm->region_text(); ?>
                            </div>
                            <div class="row">
                                <label><?php _e('City'); ?></label>
                                <?php $itemForm->city_text(); ?>
                            </div>
                            <div class="row">
                                <label><?php _e('City area'); ?></label>
                                <?php $itemForm->city_area_text(); ?>
                            </div>
                            <div class="row">
                                <label><?php _e('Address'); ?></label>
                                <?php $itemForm->address_text(); ?>
                            </div>
                        </div>

                        <div class="clear"></div>
                        <div align="center" style="margin-top: 30px; padding: 20px; ">
                            <button type="submit"><?php
if ($new_item) 
{
	_e('Add item');
}
else
{
	_e('Update');
} ?></button>
                            <button type="button" onclick="window.location='<?php echo osc_admin_base_url(true); ?>?page=item';" ><?php _e('Cancel'); ?></button>
                        </div>
                    </form>
                </div>

