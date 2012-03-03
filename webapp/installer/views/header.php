<?php
$javaScripts = array(
	osc_get_absolute_url() . '/static/scripts/jquery.js',
	osc_get_absolute_url() . '/installer/data/jquery-ui.js',
	osc_get_absolute_url() . '/installer/data/vtip/vtip.js',
	osc_get_absolute_url() . '/installer/data/jquery.jsonp.js',
	osc_get_absolute_url() . '/installer/data/install.js',
	osc_get_absolute_url() . '/administration/themes/modern/js/location.js'
);
?>
<!DOCTYPE HTML>
<html dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>OpenSourceClassifieds Installation</title>
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
                    <h1 id="logo">
                        <img src="<?php echo osc_get_absolute_url(); ?>/static/images/osclass-logo.png" alt="OpenSourceClassifieds" title="OpenSourceClassifieds"/>
                    </h1>
                    <?php if (in_array($step, array(2, 3, 4)))  { ?>
                    <ul id="nav">
                        <li class="<?php if ($step == 2)  { ?>actual<?php } elseif ($step < 2)  { ?>next<?php } else { ?>past<?php } ?>">1 - Database</li>
                        <li class="<?php if ($step == 3)  { ?>actual<?php } elseif ($step < 3)  { ?>next<?php } else { ?>past<?php } ?>">2 - Target</li>
                        <li class="<?php if ($step == 4)  { ?>actual<?php } elseif ($step < 4)  { ?>next<?php } else { ?>past<?php } ?>">3 - Categories</li>
                    </ul>
                    <div class="clear"></div>
                    <?php } ?>
                </div>
                <div id="content">

