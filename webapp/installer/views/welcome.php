    <h2 class="target">Welcome</h2>
    <form action="index.php" method="POST">
	<div class="form-table">
<?php if( !$error ): ?>
<p>All right! All the requirements have met:</p>
<?php else: ?>
<p>You have to fix the errors marked in red in order to proceed with the installation.</p>
<?php endif; ?>

<ul>
<?php foreach( $requirements['mandatory'] as $k => $v ): ?>
<li style="border-left: 20px solid <?php echo $v['check'] ? 'green' : 'red'; ?>; padding-left: 10px; list-style-type: none; margin-bottom: 2px;">
<?php echo $k; ?>
<?php if( $v['check'] == false ): ?><br /><span style="color: red;"><?php echo $v['solution']; ?></span><?php endif; ?>
</li>
<?php endforeach; ?>
<?php foreach( $requirements['optional'] as $k => $v ): ?>
<li style="border-left: 20px solid <?php echo $v['check'] ? 'green' : 'yellow'; ?>; padding-left: 10px; list-style-type: none; margin-bottom: 2px;">Optional: <?php echo $k; ?></li>
<?php endforeach; ?>
</ul>

	    <div class="more-stats">
		<input type="checkbox" name="ping_engines" id="ping_engines" checked="checked" value="1"/>
		<label for="ping_engines">
		    Allow my site to appear in search engines like Google.
		</label>
		<br/>
		<input type="checkbox" name="save_stats" id="save_stats" value="1"/>
		<input type="hidden" name="step" value="2" />
		<label for="save_stats">
		    Help make OpenSourceClassifieds better by automatically sending usage statistics and crash reports to OpenSourceClassifieds.
		</label>
	    </div>
	</div>
	<p class="margin20">
	<?php if( $error ): ?>
	    <input type="button" class="button" onclick="document.location = 'index.php?step=1'" value="Try again" />
	<?php else: ?>
	    <input type="submit" class="button" value="Run the install" />
    <?php endif; ?>
	</p>
    </form>

