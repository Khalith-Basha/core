<form action="<?php echo $urlFactory->getBaseUrl(true); ?>" method="get" onSubmit="return checkEmptyCategories()">
    <input type="hidden" name="page" value="search" />
    <fieldset class="box location">
	<h3><strong><?php _e('Your search', 'modern'); ?></strong></h3>
	<div class="row one_input">
	    <input type="text" name="sPattern"  id="query" value="<?php echo osc_search_pattern(); ?>" />
	</div>
	<h3><strong><?php _e('Location', 'modern'); ?></strong></h3>
	<div class="row one_input">
	    <h6><?php _e('Region', 'modern'); ?></h6>
	    <input type="text" id="sRegion" name="sRegion" value="<?php echo osc_search_region(); ?>" />
	    <h6><?php _e('City', 'modern'); ?></h6>
	    <input type="text" id="sCity" name="sCity" value="<?php echo osc_search_city(); ?>" />
	</div>
    </fieldset>

    <fieldset class="box show_only">
	<?php if (osc_images_enabled_at_items()): ?>
	<h3><strong><?php _e('Show only', 'modern'); ?></strong></h3>
	<div class="row checkboxes">
	    <ul>
		<li>
		    <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked' : ''); ?> />
		    <label for="withPicture"><?php _e('Show only items with pictures', 'modern'); ?></label>
		</li>
	    </ul>
	</div>
	<?php endif; ?>
	<?php if (osc_price_enabled_at_items())  { ?>
	<div class="row two_input">
	    <h6><?php _e('Price', 'modern'); ?></h6>
	    <div><?php _e('Min', 'modern'); ?>.</div>
	    <input type="text" id="priceMin" name="sPriceMin" value="<?php echo osc_search_price_min(); ?>" size="6" maxlength="6" />
	    <div><?php _e('Max', 'modern'); ?>.</div>
	    <input type="text" id="priceMax" name="sPriceMax" value="<?php echo osc_search_price_max(); ?>" size="6" maxlength="6" />
	</div>
	<?php } ?>
	<?php foreach( $search->getFacets() as $facetName => $facetValues ): ?>
	    <div class="row checkboxes">
	    <h6><?php echo $facetName; ?></h6>
		<ul>
		<?php foreach( $facetValues as $valueName => $valueCount ): ?>
			<li><?php echo $valueName; ?> (<?php echo $valueCount; ?>)</li>
		<?php endforeach; ?>
		</ul>
		</div>
	<?php endforeach; ?>
	<?php osc_get_non_empty_categories(); ?>
	<?php if (osc_count_categories())  { ?>
	    <div class="row checkboxes">
		<h6><?php _e('Category', 'modern'); ?></h6>
		<ul>
			<?php foreach( $classLoader->getClassInstance( 'Model_Category' )->toTree() as $category ): ?>
			<li>
			    <input type="checkbox" id="cat<?php echo osc_category_id( $category ); ?>" name="sCategory[]" value="<?php echo osc_category_id( $category ); ?>" <?php echo ((in_array(osc_category_id( $category ), osc_search_category( $category )) || in_array(osc_category_slug( $category ) . "/", osc_search_category( $category )) || count(osc_search_category( $category )) == 0) ? 'checked' : ''); ?> /> <label for="cat<?php echo osc_category_id( $category ); ?>"><strong><?php echo osc_category_name( $category ); ?></strong></label>
			</li>
		    <?php endforeach; ?>
		</ul>
	    </div>
	<?php } ?>
    </fieldset>

    <?php
if (osc_search_category_id())  {
	osc_run_hook('search_form', osc_search_category_id());
} else {
	osc_run_hook('search_form');
}
?>

    <button type="submit"><?php _e('Apply', 'modern'); ?></button>
</form>
