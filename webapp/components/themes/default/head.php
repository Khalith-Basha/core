
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo meta_title(); ?></title>
<meta name="title" content="<?php echo meta_title(); ?>" />
<meta name="description" content="<?php echo meta_description(); ?>" />
<?php if( $view->hasMetaRobots() ): ?>
<meta name="robots" content="<?php echo $view->getMetaRobots(); ?>" />
<?php endif; ?>
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Fri, Jan 01 1970 00:00:00 GMT" />

<link href="<?php echo osc_current_web_theme_url('combine.php?type=css&files=style.css,tabs.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var fileDefaultText = '<?php _e('No file selected', 'modern'); ?>';
var fileBtnText     = '<?php _e('Choose File', 'modern'); ?>';
</script>

<script type="text/javascript" src="<?php
echo osc_current_web_theme_url('combine.php?type=js&files=js/jquery.js,js/jquery-ui.js,js/jquery.uniform.js,js/global.js,js/tabber-minimized.js'); ?>"></script>

