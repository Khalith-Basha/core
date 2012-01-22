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
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('OpenSourceClassifieds Admin Panel'); ?></title>

	<link href="<?php echo osc_current_admin_theme_styles_url('backoffice.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('demo_table.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('admins_list_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('new_item_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('item_list_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('tabs.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('appearance_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('languages_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('settings_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('location_layout.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo osc_current_admin_theme_styles_url('cat_list_layout.css'); ?>" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="/static/scripts/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery-ui.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.cookie.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.json.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.uniform.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('osclass_datatables.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tiny_mce/tiny_mce.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.min.js'); ?>"></script>

	<script type="text/javascript">
	    $(function() {
		$("#menu").accordion({
		    active: false,
		    collapsible: true,
		    navigation: true,
		    autoHeight: false,
		    icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' }
		});

		if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {
		    jQuery('#accordion *').css('zoom', '1');
		}

		if($('.Header')) $('.Header').hide(); //XXX: remove it.
		if($('.FlashMessage')) $('.FlashMessage').animate({opacity: 1.0}, 5000).fadeOut();
		$("#static").hover(function(){ $(this).css('margin-right', '2px') }, function(){ $(this).css('margin-right','-2px') } );
	    });
	</script>

	<script>
	    var fileDefaultText = '<?php _e('No file selected', 'modern'); ?>';
	    var fileBtnText     = '<?php _e('Choose File', 'modern'); ?>';
	</script>
<body>
<div id="header">
    <div id="logo"><?php _e('OpenSourceClassifieds'); ?></div>
    <div id="arrow">&raquo;</div>
    <div id="hostname"><?php echo osc_page_title(); ?></div>
    <em id="visit_site"><a title="<?php _e('Visit website'); ?>" href="<?php echo osc_base_url(); ?>" target="_blank"><?php _e('Visit website'); ?></a><!-- &crarr; --></em>
    <div id="user_links"><?php _e('Hi'); ?>, <a title="<?php _e('Your profile'); ?>" href="<?php echo osc_admin_base_url(true); ?>?page=admins&action=edit"><?php echo osc_logged_admin_username(); ?>!</a> | <a title="<?php _e('Log Out'); ?>" href="index.php?action=logout"><?php _e('Log Out'); ?></a></div>
    <?php osc_run_hook('admin_header'); ?>
</div>
        <div id="update_version" style="display:none;"></div>

	<div id="content">
            <div id="separator"></div>

            <?php
osc_current_admin_theme_path('include/backoffice_menu.php'); ?>
            
            <div id="right_column">

