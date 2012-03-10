<?php

require_once 'osc/File.php';

class FileMaintenance extends File
{
	public function __construct()
	{
		parent::__construct();

		$this->setPath( ABS_PATH . '/.maintenance' );
	}
}

