<?php

function getBadWordsList( mysqli $conn )
{
	$badWordModel = new \Osc\Model\BadWord;

	$bwList = array(
		'STRONG_BAD_WORD' => $badWordModel->getWordsBySeverity( 'STRONG' ),
		'SOFT_BAD_WORD' => $badWordModel->getWordsBySeverity( 'SOFT' )
	);

	return $bwList;
}

