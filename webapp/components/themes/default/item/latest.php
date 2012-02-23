<?php
$itemUrls = $classLoader->getClassInstance( 'Url_Item' );
?>

<div class="latest_ads">
<h1><strong><?php _e('Latest Items', 'modern'); ?></strong></h1>
<?php if( count( $latestItems ) === 0 ): ?>
	<p class="empty"><?php _e('No Latest Items', 'modern'); ?></p>
<?php else: ?>
	<table border="0" cellspacing="0">
	<tbody>
	<?php $class = "even"; ?>
	<?php foreach( $latestItems as $item ): ?>
		<tr class="<?php echo $class . (osc_item_is_premium( $item ) ? " premium" : ""); ?>">
		<?php if( osc_images_enabled_at_items() ): ?>
			<td class="photo">
			<?php if( 0 < count( $item['resources'] ) ): ?>
				<a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><img src="<?php echo $resourceUrls->osc_resource_thumbnail_url( $item['resources'][0] ); ?>" width="75px" height="56px" title="" alt="" /></a>
			<?php else: ?>
				<img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" alt="" title=""/>
			<?php endif; ?>
			</td>
		<?php endif; ?>
		<td class="text">
		<h3><a href="<?php echo $itemUrls->getDetailsUrl( $item ); ?>"><?php echo osc_item_title( $item ); ?></a></h3>
		<p><strong><?php if (osc_price_enabled_at_items()) { echo osc_item_formated_price( $item ); ?> - <?php } echo osc_item_city( $item ); ?> (<?php echo osc_item_region( $item ); ?>) - <?php echo osc_format_date( osc_item_pub_date( $item ) ); ?></strong></p>
		<p><?php echo osc_highlight( strip_tags( osc_item_description( $item ) ) ); ?></p>
		</td>                                       
		</tr>
		<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?php if( count( $latestItems ) === osc_max_latest_items() ): ?>
		<p class="see_more_link"><a href="<?php echo osc_search_show_all_url(); ?>"><strong><?php _e("See all offers", 'modern'); ?> &raquo;</strong></a></p>
	<?php endif; ?>
<?php endif; ?>
</div>

