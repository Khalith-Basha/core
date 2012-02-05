<?php

require 'CommandLine.php';

class Controller_Item_DeactivateOlds extends Controller_CommandLine
{
	public function run()
	{
		echo 'Deleting old ads...', PHP_EOL;
		return 0;
	}
}

$controller = new Controller_Item_DeactivateOlds;
$controller->start();

