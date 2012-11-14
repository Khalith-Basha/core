<?php

class Input_Request extends \Cuore\Utils\TypedArray
{
	public function __construct()
	{
		parent::__construct( $_REQUEST );
	}
}


