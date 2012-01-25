<?php
echo $view->render( 'header' );
?>

<ul>
<?php foreach( $latestSearches as $latestSearch ): ?>
<li><?php echo $latestSearch['query']; ?></li>
<?php endforeach; ?>
</ul>

<?php
echo $view->render( 'footer' );

