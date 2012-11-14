<?php
namespace Osc;

class FileMaintenance extends \Cuore\Fs\File
{
	public function __construct()
	{
		parent::__construct( ABS_PATH . '/.maintenance' );
	}
}

