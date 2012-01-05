<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>OpenSourceClassifieds Installation</title>
        <script src="<?php
echo get_absolute_url(); ?>/installer/data/jquery.js" type="text/javascript"></script>
        <script src="<?php
echo get_absolute_url(); ?>/installer/data/jquery-ui.js" type="text/javascript"></script>
        <script src="<?php
echo get_absolute_url(); ?>/installer/data/vtip/vtip.js" type="text/javascript"></script>
        <script src="<?php
echo get_absolute_url(); ?>/installer/data/jquery.jsonp.js" type="text/javascript"></script>
        <script src="<?php
echo get_absolute_url(); ?>/installer/data/install.js" type="text/javascript"></script>
        <script src="<?php
echo get_absolute_url(); ?>/administration/themes/modern/js/location.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php
echo get_absolute_url(); ?>/installer/data/install.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php
echo get_absolute_url(); ?>/installer/data/vtip/css/vtip.css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div id="header" class="installation">
                    <h1 id="logo">
                        <img src="<?php
echo get_absolute_url(); ?>/library/images/osclass-logo.png" alt="OpenSourceClassifieds" title="OpenSourceClassifieds"/>
                    </h1>
                    <?php
if (in_array($step, array(2, 3, 4))) 
{ ?>
                    <ul id="nav">
                        <li class="<?php
	if ($step == 2) 
	{ ?>actual<?php
	}
	elseif ($step < 2) 
	{ ?>next<?php
	}
	else
	{ ?>past<?php
	} ?>">1 - Database</li>
                        <li class="<?php
	if ($step == 3) 
	{ ?>actual<?php
	}
	elseif ($step < 3) 
	{ ?>next<?php
	}
	else
	{ ?>past<?php
	} ?>">2 - Target</li>
                        <li class="<?php
	if ($step == 4) 
	{ ?>actual<?php
	}
	elseif ($step < 4) 
	{ ?>next<?php
	}
	else
	{ ?>past<?php
	} ?>">3 - Categories</li>
                    </ul>
                    <div class="clear"></div>
                    <?php
} ?>
                </div>
                <div id="content">

