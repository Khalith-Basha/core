<!DOCTYPE HTML>
<html>
<html dir="ltr" lang="en-US">
    <head>
	<meta charset="utf8" />
        <title><?php _e('OpenSourceClassifieds admin panel login'); ?></title>
        <script type="text/javascript" src="<?php echo osc_admin_base_url(); ?>/themes/modern/js/jquery.js"></script>
	<link type="text/css" href="<?php echo osc_admin_base_url( false ) . '/static/styles/backoffice_login.css'; ?>" media="screen" rel="stylesheet" />
    </head>
    <body class="login">
        <div id="login">
	<div class="OpenSourceClassifieds_Logo" style="font-family: serif; width: 60%; position: relative; background-color: white; height: 100px; margin-left: auto; margin-right: auto;">
		<h2 style="color: #2960EC; font-weight: normal; position: absolute;; font-size: 2.2em; padding-left: 36%; margin: 0px;">OpenSource</h2>
		<h1 style="color: #2F3A8F; font-weight: bold; text-align: center; font-size: 5.4em; padding-top: 3%;">Classifieds</h1>
		<br style="clear: both;" />
	</div>
	<?php echo $view->render( 'flashMessages' ); ?>

