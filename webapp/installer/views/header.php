<?php

$javaScripts = array(
	osc_get_absolute_url() . '/static/scripts/jquery.js',
	osc_get_absolute_url() . '/installer/data/scripts/jquery-ui.js',
	osc_get_absolute_url() . '/installer/data/scripts/jquery.jsonp.js',
	osc_get_absolute_url() . '/installer/data/scripts/install.js',
	osc_get_absolute_url() . '/administration/themes/modern/js/location.js'
);

$progressBarIndicator = ( 100 / $numSteps ) * $step;

?>
<!DOCTYPE HTML>
<html dir="ltr" lang="en-US" xml:lang="en-US">
<head>
	<meta charset="utf-8" />
	<title>OpenSourceClassifieds - Installer</title>
	<?php foreach( $javaScripts as $js ): ?>
	<script src="<?php echo $js; ?>" type="text/javascript"></script>
	<?php endforeach; ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_get_absolute_url(); ?>/installer/data/styles/install.css" />
</head>
<body>

<div id="wrapper">
<div id="container">
<div id="header" class="installation">
	<div class="OpenSourceClassifieds_Logo" style="font-family: serif; width: 60%; position: relative; background-color: white; height: 100px; margin-left: auto; margin-right: auto;">
		<h2 style="color: #2960EC; font-weight: normal; position: absolute;; font-size: 2.2em; padding-left: 36%; margin: 0px;">OpenSource</h2>
		<h1 style="color: #2F3A8F; font-weight: bold; text-align: center; font-size: 5.4em; padding-top: 3%;">Classifieds</h1>
		<br style="clear: both;" />
	</div>
	<ol class="StepList">
	<?php foreach( $steps as $stepNumber => $stepLabel ): ?>
		<li class="<?php if( $stepNumber < $step ): ?>past<?php elseif( $stepNumber === $step ):  ?>actual<?php else: ?>next<?php endif; ?>"><?php echo $stepLabel; ?></li>
	<?php endforeach; ?>
	</ol>
	<div class="clear"></div>
	<div class="ProgressBar"><div style="width: <?php echo $progressBarIndicator; ?>%;"></div></div>
</div>
<div id="content">

