<?php

require_once 'osc/model/BadWord.php';

function getBadWordsList( mysqli $conn )
{
	$badWordModel = new BadWordModel;

	$bwList = array(
		'STRONG_BAD_WORD' => $badWordModel->getWordsBySeverity( 'STRONG' ),
		'SOFT_BAD_WORD' => $badWordModel->getWordsBySeverity( 'SOFT' )
	);

	return $bwList;
}

