<?php
$searchUrls = $classLoader->getClassInstance( 'Url_Search' );
$orders = osc_list_orders();
?>
<div id="list_head">
    <div class="inner">
	<h1>
	    <strong><?php _e('Search results', 'modern'); ?></strong>
	</h1>
	<p class="see_by"><?php _e('Sort by', 'modern'); ?>:
	    <?php $i = 0; ?>
	    <?php 	foreach ($orders as $label => $params) 
	{
		$orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
		<?php if (osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType)  { ?>
		    <a class="current" href="<?php echo $searchUrls->osc_update_search_url($params); ?>"><?php echo $label; ?></a>
		<?php } else { ?>
		    <a href="<?php echo $searchUrls->osc_update_search_url($params); ?>"><?php echo $label; ?></a>
		<?php } ?>
		<?php if ($i != count($orders) - 1)  { ?>
		    <span>|</span>
		<?php } ?>
		<?php $i++; ?>
	    <?php } ?>
	</p>
    </div>
</div>

