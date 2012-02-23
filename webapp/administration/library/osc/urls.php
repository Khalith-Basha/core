<?php

function osc_base_url()
{
	return ClassLoader::getInstance()
		->getClassInstance( 'Url_Abstract' )
		->getBaseUrl();
}

/**
 * Gets the root url of administration for your installation
 *
 * @param boolean $with_index true if index.php in the url is needed
 * @return string
 */
function osc_admin_base_url( $withIndex = false )
{
	$generalConfig = ClassLoader::getInstance()->getClassInstance( 'Config' )->getConfig( 'general' );
	$path = $generalConfig['webUrl'] . '/administration';
	if( $withIndex )
		$path .= '/index.php';
	return $path;
}
/**
 * Create automatically the url to for admin to edit an item
 *
 * @param int $id
 * @return string
 */
function osc_item_admin_edit_url($id) 
{
	return osc_admin_base_url(true) . '?page=items&action=item_edit&id=' . $id;
}
/**
 * Gets url for confirmation admin password recover proces
 *
 * @param int $adminId
 * @param string $code
 * @return string
 */
function osc_forgot_admin_password_confirm_url($adminId, $code) 
{
	return osc_admin_base_url(true) . '?page=user&action=forgot&adminId=' . $adminId . '&code=' . $code;
}

