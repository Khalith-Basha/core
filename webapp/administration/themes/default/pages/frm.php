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
$pageForm = ClassLoader::getInstance()->getClassInstance( 'Form_Page' );
$locales = ClassLoader::getInstance()->getClassInstance( 'Model_Locale' )->listAllEnabled();

$edit = isset($page['pk_i_id']);
if ( $edit ) 
{
	$title = __("Edit page");
	$action_frm = "edit";
	$btn_text = __("Update");
}
else
{
	$title = __("Add page");
	$action_frm = "add";
	$btn_text = __('Add');
}
?>

<div id="update_version" style="display:none;"></div>
<div id="content_header" class="content_header">
    <div style="float: left;">
	<img src="<?php echo osc_current_admin_theme_url('images/pages-icon.png'); ?>" title="" alt="" />
    </div>
    <div id="content_header_arrow">&raquo; <?php _e($title); ?></div>
    <div style="clear: both;"></div>
</div>
<div id="content_separator"></div>
<div id="settings_form">
    <form name="pages_form" id="pages_form" action="<?php echo osc_admin_base_url(true); ?>?page=page" method="post" onSubmit="return checkForm()">
	<input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
	<?php $pageForm->primary_input_hidden($page); ?>
	<div class="FormElement">
	    <div class="FormElementName">
		<?php _e('Internal name (name to easily identify this page)'); ?>
	    </div>
	    <div class="FormElementInput">
	       <?php $pageForm->internal_name_input_text($page); ?>
	    </div>
	</div>
	<div class="FormElement">
		<label class="FormElementName" for="tags">Tags</label>
		<div class="FormElementInput"><input type="text" name="tags" id="tags" /></div>
	</div>
	<div class="FormElement">
		<label class="FormElementName" for="description">Description</label>
		<div class="FormElementInput"><input type="text" name="description" id="description" /></div>
	</div>
	<?php $pageForm->multilanguage_name_description($locales, $page); ?>
	<div class="clear50"></div>
	<div class="FormElement">
	    <div class="FormElementName"></div>
	    <div class="FormElementInput">
		<button class="formButton HistoryBack" type="button"><?php _e('Cancel'); ?></button>
		<button class="formButton" type="submit"><?php echo $btn_text; ?></button>
	    </div>
	</div>
    </form>
</div>

