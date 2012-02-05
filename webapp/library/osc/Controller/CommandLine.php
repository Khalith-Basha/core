<?php

abstract class Controller_CommandLine
{
	public final function start()
	{
		echo 'Starting command line controller: ', get_class( $this ), PHP_EOL;
		$exitCode = $this->run();
		printf( 'Controller execution finished. Exit code: %d' . PHP_EOL, $exitCode );
	}

	abstract public function run();
}

