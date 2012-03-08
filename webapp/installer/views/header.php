<?php
$javaScripts = array(
	osc_get_absolute_url() . '/static/scripts/jquery.js',
	osc_get_absolute_url() . '/installer/data/jquery-ui.js',
	osc_get_absolute_url() . '/installer/data/vtip/vtip.js',
	osc_get_absolute_url() . '/installer/data/jquery.jsonp.js',
	osc_get_absolute_url() . '/installer/data/install.js',
	osc_get_absolute_url() . '/administration/themes/modern/js/location.js'
);

$steps = array(
	1 => 'Welcome',
	2 => 'Database',
	3 => 'Target',
	4 => 'Categories'
);
?>
<!DOCTYPE HTML>
<html dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>OpenSourceClassifieds - Installer</title>
	<?php foreach( $javaScripts as $js ): ?>
        <script src="<?php echo $js; ?>" type="text/javascript"></script>
	<?php endforeach; ?>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_get_absolute_url(); ?>/installer/data/install.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_get_absolute_url(); ?>/installer/data/vtip/css/vtip.css" />
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
                    <ul id="nav">
			<?php foreach( $steps as $stepNumber => $stepLabel ): ?>
			<li class="<?php if( $stepNumber == $step ): ?>actual<?php elseif( $step < 2 ):  ?>next<?php else: ?>past<?php endif; ?>"><?php echo $stepNumber; ?> - <?php echo $stepLabel; ?></li>
			<?php endforeach; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div id="content">

