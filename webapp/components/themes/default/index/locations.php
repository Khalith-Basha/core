<?php
echo $view->render( 'header' );
?>

<h1>- Locations</h1>

<ul>
<?php foreach( $regions as $region ): ?>
	<li><h2>-- <?php echo $region['s_name']; ?></h2></li>
	<ul>
	<?php foreach( $region['cities'] as $city ): ?>
		<li><h3>--- <?php echo $city['s_name']; ?></h3></li>
	<?php endforeach; ?>
	</ul>
<?php endforeach; ?>
</ul>

<?php
echo $view->render( 'footer' );

