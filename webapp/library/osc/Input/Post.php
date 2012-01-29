<?php

class Input_Post extends TypedArray
{
	public function __construct()
	{
		parent::__construct( $_POST );
	}
}


