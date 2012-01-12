<?php
class Controller{}

abstract class CommandLineController extends Controller
{
	public function start()
	{
		$this->run();
	}

	abstract public function run();
}

class DeleteOld extends CommandLineController
{

	public function run()
	{
		echo 'Running';
	}
}


$cron = new DeleteOld;
$cron->start();

