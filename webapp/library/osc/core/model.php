<?php

class Model
{
	protected $conn;

	protected function __construct()
	{
		$this->conn = DBConnectionClass::newInstance()->getConnection();
	}

	public function __destruct()
	{
		$this->conn->close();
	}

	public function getConnection()
	{
		return $this->conn;
	}

	protected function replacePrefix( $sql )
	{
		return str_replace( '/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql );
	}

	public function prepareStatement( $sql )
	{
		return $this->conn->prepare( $this->replacePrefix( $sql ) ); 
	}

	public function fetchAll( mysqli_stmt $stmt )
	{
		$results = array();

		$stmt->execute();
		$fields = $stmt->result_metadata()->fetch_fields();
		$fieldNames = array();
		$fieldValues = array();
		foreach( $fields as $field )
		{
			$fieldName = $field->name;
			$fieldNames[] = $fieldName;
			$fieldValues[$fieldName] = &$fieldName;
		}
		call_user_func_array( array( $stmt, 'bind_result' ), $fieldValues);

		while( $stmt->fetch() )
		{
			$result = array();
			foreach( array_keys( $fieldValues ) as $field => $fieldValue )
				$result[$field] = $fieldValue;
			$results[] = $result;
		}

		return $results;
	}
}

