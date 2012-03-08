<?php
$flashMessages = $classLoader->getClassInstance( 'Session' )->getFlashMessages();
if( !empty( $flashMessages ) )
{
	foreach( $flashMessages as $flashMessage )
	{
		?>
		<div id="<?php echo $flashMessage['id']; ?>"
			class="<?php echo $flashMessage['class']; ?> <?php echo $flashMessage['type']; ?>"><?php echo $flashMessage['text']; ?></div>
		<?php
	}
}

