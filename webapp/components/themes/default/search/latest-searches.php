<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php
echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php
osc_current_web_theme_path('head.php'); ?>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex, nofollow" />
        <script type="text/javascript" src="<?php
echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
    </head>
    <body>
        <div class="container">
	    <?php osc_current_web_theme_path('header.php'); ?>


<ul>
<?php foreach( $latestSearches as $latestSearch ): ?>
<li><?php echo $latestSearch['query']; ?></li>
<?php endforeach; ?>
</ul>

            <?php
osc_current_web_theme_path('footer.php'); ?>
        </div>
        <?php
osc_show_flash_message(); ?>
        <?php
osc_run_hook('footer'); ?>
    </body>
</html>
