
<div class="paginate">
<?php foreach( $pagination->getPages() as $page ): ?>
	<a class="<?php echo $page['selected'] ? 'searchPaginationSelected' : 'searchPaginationNonSelected'; ?>" href="<?php echo $page['url']; ?>"><?php echo $page['number']; ?></a>
<?php endforeach; ?>
</div>

