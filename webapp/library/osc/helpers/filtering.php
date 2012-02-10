<?php

function getBadWordsList( mysqli $conn )
{
	$badWordModel = ClassLoader::getInstance()->getClassInstance( 'Model_BadWord' );

	$bwList = array(
		'STRONG_BAD_WORD' => $badWordModel->getWordsBySeverity( 'STRONG' ),
		'SOFT_BAD_WORD' => $badWordModel->getWordsBySeverity( 'SOFT' )
	);

	return $bwList;
}

