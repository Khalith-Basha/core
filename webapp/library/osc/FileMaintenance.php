<?php

require_once 'Cuore/Fs/File.php';

class FileMaintenance extends \Cuore\Fs\File
{
	public function __construct()
	{
		parent::__construct( ABS_PATH . '/.maintenance' );
	}
}

