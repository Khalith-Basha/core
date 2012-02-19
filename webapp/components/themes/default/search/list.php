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
$itemUrls = ClassLoader::getInstance()->getClassInstance( 'Url_Item' );
if( 0 < count( $premiums ) ):
?>
<table border="0" cellspacing="0">
     <tbody>
        <?php
	$class = "even"; ?>
	<?php foreach( $premiums as $premium ): ?>
            <tr class="premium_<?php
		echo $class; ?>">
                <?php if (osc_images_enabled_at_items())  { ?>
                 <td class="photo">
                     <?php if (osc_count_premium_resources( $premium ))  { ?>
                        <a href="<?php echo osc_premium_url( $premium ); ?>"><img src="<?php echo osc_resource_thumbnail_url( $premium ); ?>" width="75px" height="56px" title="" alt="" /></a>
                    <?php } else { ?>
                        <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" title="" alt="" />
                    <?php } ?>
                 </td>
                 <?php } ?>
                 <td class="text">
                     <h3>
                         <span style="float:left;"><a href="<?php echo osc_premium_url( $premium ); ?>"><?php echo osc_premium_title( $premium ); ?></a></span><span style="float:right;"><?php _e("Sponsored ad", "modern"); ?></span>
                     </h3>
                     <p style="clear: left;">
                         <strong><?php
		if (osc_price_enabled_at_items()) 
		{
			echo osc_premium_formated_price(); ?> - <?php
		}
		echo osc_premium_city( $premium ); ?> (<?php
		echo osc_premium_region( $premium ); ?>) - <?php
		echo osc_format_date(osc_premium_pub_date( $premium )); ?></strong>
                     </p>
                     <p><?php echo osc_highlight(strip_tags(osc_premium_description( $premium ))); ?></p> </td>
             </tr>
            <?php
		$class = ($class == 'even') ? 'odd' : 'even'; ?>
	<?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php if( 0 < count( $items ) ): ?>
<table border="0" cellspacing="0">
    <tbody>
        <?php $class = "even"; ?>
<?php foreach( $items as $item ): ?>
	<?php
	$itemUrl = $itemUrls->getDetailsUrl( $item, osc_current_user_locale() )
	?>
            <tr class="<?php echo $class; ?>">
                <?php if (osc_images_enabled_at_items())  { ?>
                 <td class="photo">
		     <?php if( osc_count_item_resources( $item ) ): ?>
                        <a href="<?php echo $itemUrl; ?>"><img src="<?php echo $resourceUrls->osc_resource_thumbnail_url( $item ); ?>" width="75px" height="56px" title="" alt="" /></a>
		    <?php else: ?>
                        <img src="<?php
			echo osc_current_web_theme_url('images/no_photo.gif'); ?>" title="" alt="" />
		    <?php endif; ?>
                 </td>
                 <?php } ?>
                 <td class="text">
                     <h3>
			 <a href="<?php echo $itemUrl; ?>"><?php echo osc_item_title( $item ); ?></a>
                     </h3>
                     <p>
                         <strong><?php if (osc_price_enabled_at_items())  { echo osc_item_formated_price( $item ); ?> - <?php } echo osc_item_city( $item ); ?> (<?php echo osc_item_region( $item ); ?>) - <?php echo osc_format_date( osc_item_pub_date( $item ) ); ?></strong>
                     </p>
                     <p><?php echo osc_highlight(strip_tags(osc_item_description( $item ))); ?></p>
                 </td>
             </tr>
            <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
<?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

