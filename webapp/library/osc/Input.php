<?php

class InputGet extends TypedArray
{
	public function __construct()
	{
		parent::__construct( $_GET );
	}
}

class InputPost extends TypedArray
{
	public function __construct()
	{
		parent::__construct( $_POST );
	}
}


