<?php

class Input_Post extends \Cuore\Utils\TypedArray
{
	public function __construct()
	{
		parent::__construct( $_POST );
	}
}


