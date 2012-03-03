<?php

/*
 * The url of the site
 *
 * @return string The url of the site
*/
function osc_get_absolute_url() 
{
	$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
	$pos = strpos( $_SERVER['REQUEST_URI'], '/installer' );
	return $protocol . '://' . $_SERVER['HTTP_HOST'] . substr( $_SERVER['REQUEST_URI'], 0, $pos );
}

/*
 * The relative url on the domain url
 *
 * @return string The relative url on the domain url
*/
function osc_get_relative_url()
{
	$url = $_SERVER['REQUEST_URI'];
	return substr( $url, 0, strpos( $url, '/installer' ) );
}

