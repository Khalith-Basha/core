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

$menuBar = array(
	'Items' => array(
		'page=item' => 'Listing',
		'page=item&action=post' => 'Add',
		'page=item&action=settings' => 'Settings',
		'page=comment' => 'Comment listing',
		'page=media' => 'Media listing',
	),
	'Categories' => array(
		'page=category' => 'Listing',
		'page=category&action=settings' => 'Settings',
	),
	'Pages' => array(
		'page=page' => 'Listing',
		'page=page&action=add' => 'Add'
	),
	'Emails and alerts' => array(
		'page=email' => 'Settings',
	),
	'Custom fields' => array(
		'page=field' => 'Settings',
	),

	0 => null,

	'Appearance' => array(
		'page=appearance' => 'Theme listing',
		'page=appearance&action=add' => 'Add',
		'page=appearance&action=widgets' => 'Add or remove widgets'
	),
	'Plugins' => array(
		'page=plugin' => 'Listing',
		'page=plugin&action=add' => 'Add'
	),
	'Languages' => array(
		'page=language' => 'Listing',
		'page=language&action=add' => 'Add'
	),
	'General settings' => array(
                'page=settings' => 'General settings',
                'page=settings&action=comments' => 'Comments',
                'page=settings&action=contact' => 'Contact',
                'page=settings&action=locations' => 'Locations',
                'page=settings&action=permalinks' => 'Permalinks',
                'page=settings&action=spamNbots' => 'Spam and bots',
                'page=settings&action=currencies' => 'Currencies',
                'page=settings&action=mailserver' => 'Mail Server',
                'page=settings&action=media' => 'Media',
                'page=settings&action=cron' => 'Cron system',
                'page=settings&action=latestsearches' => 'Last Searches',
	),
	'Tools' => array(
                'page=tool&action=import' => 'Import data',
                'page=tool&action=images' => 'Regenerate thumbnails',
                'page=tool&action=maintenance' => 'Maintenance mode',
	),

	1 => null,

	'Users' => array(
                'page=user' => 'Listing',
                'page=user&action=create' => 'Add new user',
                'page=user&action=settings' => 'Settings',
	),
	'Statistics' => array(
                'page=stats&action=users' => 'Users',
                'page=stats&action=items' => 'Items',
                'page=stats&action=comments' => 'Comments',
                'page=stats' => 'Reports',
	)
);
?>

<div id="left_column"> 
    <div style="padding-top: 9px;">
        <div style="float: left; padding-left: 5px; padding-top: 5px;">
            <img src="<?php echo osc_current_admin_theme_url('images/home_icon.gif'); ?>" alt="" title="" />
        </div>
        <div style="float: left; padding-top: 5px; padding-left: 5px;">&raquo; <a href="<?php echo osc_admin_base_url(); ?>"><?php _e('Dashboard'); ?></a></div>
        <div style="clear: both;"></div>
        <div style="border-top: 1px solid #ccc; width: 99%;">&nbsp;</div>
    </div>

    <div id="menu">

	<?php foreach( $menuBar as $menuHeader => $menuItems ): ?>
		<?php if( is_integer( $menuHeader ) ) { echo '&nbsp;'; continue; } ?>
		<h3><a href="#"><?php echo _e( $menuHeader ); ?></a></h3>
		<ul>
		<?php foreach( $menuItems as $itemUrl => $itemLabel ): ?>
			<li>&raquo; <a href="<?php echo osc_admin_base_url( true ) . '?' . $itemUrl; ?>"><?php echo _e( $itemLabel ); ?></a></li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>

        <?php osc_run_hook('admin_menu'); ?>

    </div>
</div>
