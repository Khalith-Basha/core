<?php

class Input_Request extends TypedArray
{
	public function __construct()
	{
		parent::__construct( $_REQUEST );
	}
}


