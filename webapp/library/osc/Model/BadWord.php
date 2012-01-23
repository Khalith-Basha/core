<?php

require_once 'osc/core/model.php';

class Model_BadWord extends Model
{
	public function getWordsBySeverity( $severity )
	{
		$words = array();

		$sql = <<<SQL
SELECT
	word
FROM
	/*TABLE_PREFIX*/bad_word
WHERE
	severity = ?
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 's', $severity );
		if( $stmt->execute() )
		{
			$words = $this->fetchAllColumns( $stmt );
		}
		$stmt->close();

		return $words;
	}
}

