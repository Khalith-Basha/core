<?php
$searchUrl = $classLoader->getClassInstance( 'Url_Search' );

$numCategories = count( $categories );
$col1_max_cat = ceil($numCategories / 3);
$col2_max_cat = ceil(($numCategories - $col1_max_cat) / 2);
$col3_max_cat = $numCategories - ($col1_max_cat + $col2_max_cat);
$i = 1;
$x = 1;
$col = 1;
?>

<div class="categories <?php echo 'c' . $numCategories; ?>">
<?php if( 0 < $numCategories ): ?><div class="col c1"><?php endif; ?>
<?php foreach( $categories as $category ): ?>
	<div class="category">
	<h1><strong><a class="category <?php echo osc_category_field( $category, 's_slug' ); ?>" href="<?php echo $searchUrl->osc_search_category_url( $category ); ?>"><?php echo osc_category_field( $category, 's_name' ); ?></a> <span>(<?php echo osc_category_field( $category, 'i_num_items' ); ?>)</span></strong></h1>
	<?php if( count( $category['categories'] ) > 0 ): ?>
		<ul>
		<?php foreach( $category['categories'] as $subCategory ): ?>
			<li><a class="category <?php echo osc_category_field( $subCategory, 's_slug' ); ?>" href="<?php echo $searchUrl->osc_search_category_url( $subCategory ); ?>"><?php echo osc_category_field( $subCategory, 's_name' ); ?></a> <span>(<?php echo osc_category_field( $subCategory, 'i_num_items' ); ?>)</span></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
	<?php
	if (($col == 1 && $i == $col1_max_cat) || ($col == 2 && $i == $col2_max_cat) || ($col == 3 && $i == $col3_max_cat)) 
	{
		$i = 1;
		$col++;
		echo '</div>';
		if ($x < $numCategories) 
		{
			echo '<div class="col c' . $col . '">';
		}
	}
	else
	{
		$i++;
	}
	$x++;
	?>
<?php endforeach; ?>
</div>

