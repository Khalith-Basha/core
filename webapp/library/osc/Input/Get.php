<?php

class Input_Get extends \Cuore\Utils\TypedArray
{
	public function __construct()
	{
		parent::__construct( $_GET );
	}
}

