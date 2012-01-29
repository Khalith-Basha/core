<?php

class Input_Get extends TypedArray
{
	public function __construct()
	{
		parent::__construct( $_GET );
	}
}

