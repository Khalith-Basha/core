<h2 class="target">Error</h2>
<p class="bottom space-left-10"><?php echo $error['message'] ?></p>
<?php if( !empty( $error['description'] ) ): ?>
<textarea style="width: 100%;" readonly="readonly"><?php echo $error['description']; ?></textarea>
<?php endif; ?>
<a href="<?php echo osc_get_absolute_url(); ?>installer/index.php?step=<?php echo $step; ?>" class="button">Go back</a>
<div class="clear bottom"></div>

