<?php

function textHasWord( $text, $word )
{
	return preg_match( "/\b$word\b/i", $text );
}

