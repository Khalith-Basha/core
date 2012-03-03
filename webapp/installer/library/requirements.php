<?php

/*
 * Get the requirements to install OpenSourceClassifieds
 *
 * @return array Requirements
*/
function getMandatoryRequirements() 
{
	$minimumPhpVersion = '5.3';
	$requirements = array(
		'PHP version >= ' . $minimumPhpVersion => array(
			'check' => version_compare( PHP_VERSION, $minimumPhpVersion, '>=' ),
			'solution' => "PHP $minimumPhpVersion is required to run OpenSourceClassifieds. You may talk with your hosting to upgrade your PHP version.",
		),
		'Folder <code>components/uploads</code> exists' => array(
			'check' => file_exists( ABS_PATH . '/components/uploads' ),
			'solution' => 'You have to create <code>uploads</code> folder, i.e.: <code>mkdir ' . ABS_PATH . '/components/uploads</code>'
		),
		'Folder <code>components/uploads</code> is writable' => array(
			'check' => is_writable( ABS_PATH . '/components/uploads' ),
			'solution' => 'Folder <code>uploads</code> has to be writable, i.e.: <code>chmod a+w ' . ABS_PATH . '/components/uploads</code>'
		),
		'Folder <code>components/languages</code> exists' => array(
			'check' => file_exists( ABS_PATH . '/components/languages' ),
			'solution' => 'You have to create <code>languages</code> folder, i.e.: <code>mkdir ' . ABS_PATH . '/components/languages/</code>'
		)
	);
	$phpExtensions = array( 'mysqli', 'gd', 'curl', 'zip', 'mbstring' );
	foreach( $phpExtensions as $php_ext )
	{
		$requirements[$php_ext . ' extension for PHP'] = array(
			'check' => extension_loaded( $php_ext ),
			'solution' => "Install and enable the PHP extension '$php_ext'."
		);
	}

	$configGeneralPath = DEFAULT_CONFIG_FOLDER_PATH . '/general.php';
	$configDatabasePath = DEFAULT_CONFIG_FOLDER_PATH . '/database.php';

	if( file_exists( $configGeneralPath ) || file_exists( $configDatabasePath ) )
	{
		$requirements['File <code>' . $configGeneralPath . '</code> is writable'] = array(
			'check' => file_exists( $configGeneralPath ) && is_writable( $configGeneralPath ),
			'solution' => 'File <code>' . $configGeneralPath . '</code> has to be writable, i.e.: <code>chmod a+w ' . $configGeneralPath . '</code>'
		);
		$requirements['File <code>' . $configDatabasePath . '</code> is writable'] = array(
			'check' => file_exists( $configDatabasePath ) && is_writable( $configDatabasePath ),
			'solution' => 'File <code>' . $configDatabasePath . '</code> has to be writable, i.e.: <code>chmod a+w ' . $configDatabasePath . '</code>'
		);
	}
	else
	{
		$requirements['Config directory "' . DEFAULT_CONFIG_FOLDER_PATH . '" is writable'] = array(
			'check' => is_dir( DEFAULT_CONFIG_FOLDER_PATH ) && is_writable( DEFAULT_CONFIG_FOLDER_PATH ),
			'solution' => 'Config folder "' . DEFAULT_CONFIG_FOLDER_PATH . '" has to be writable, i.e.: <code>chmod a+w ' . DEFAULT_CONFIG_FOLDER_PATH . '</code>'
		);
	}
	return $requirements;
}

function getOptionalRequirements()
{
	$requirements = array();
	$phpExtensions = array( 'memcached' );
	foreach( $phpExtensions as $php_ext )
	{
		$requirements[$php_ext . ' extension for PHP'] = array(
			'check' => extension_loaded( $php_ext ),
			'solution' => "Install and enable the PHP extension '$php_ext'."
		);
	}

	return $requirements;
}

/**
 * Check if some of the requirements to install OpenSourceClassifieds are correct or not
 *
 * @return boolean Check if all the requirements are correct
 */
function checkRequirements( array $requirements ) 
{
	foreach( $requirements as $req )
		if( false === $req['check'] )
			return true;

	return false;
}

