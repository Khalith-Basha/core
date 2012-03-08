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
            <h1>
                <a href="<?php echo osc_base_url(); ?>" title="OpenSourceClassifieds"><img src="images/osclass-logo.png" border="0" title="" alt=""/></a>
	    </h1>
	<?php echo $view->render( 'flashMessages' ); ?>

