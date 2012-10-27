<?php

class Model
{
	protected $conn;

	public function __construct()
	{
		/* @TODO
		$this->conn = ClassLoader::getInstance()
			->getClassInstance( 'cuore_db_Connection' )
			->getResource();
		 */
		if( defined( 'DB_HOST' ) )
		{
			$this->conn = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
		}
	}

	public function __destruct()
	{
		if( is_resource( $this->conn ) )
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
		$result = $this->conn->prepare( $this->replacePrefix( $sql ) ); 
		if( false === $result )
			throw new Exception( $this->conn->error );
		return $result;
	}

	public function fetch( mysqli_stmt $stmt )
	{
		$results = $this->fetchAll( $stmt );
		if( 1 === count( $results ) )
			return $results[0];
		else
			return null;
	}

        public function fetchAll( mysqli_stmt $stmt )
        {   
                $results = array();

                if( false === $stmt->execute() )
                        return false;

                $numFields = $stmt->field_count;
                $fieldNames = $fieldValues = array();
    
                $fields = $stmt->result_metadata()->fetch_fields();
                foreach( $fields as $field )
                {   
                        $fieldNames[] = $field->name;
                        $fieldValues[] = ''; 
                }   

                for( $i = 0; $i < $numFields; $i++ )
                        $fieldValues[$i] = &$fieldValues[$i];

                call_user_func_array( array( $stmt, 'bind_result' ), $fieldValues );

                while( $stmt->fetch() )
                {   
                        $result = array();
                        for( $i = 0; $i < $numFields; $i++ )
                                $result[ $fieldNames[ $i ] ] = $fieldValues[ $i ];
                        $results[] = $result;
                }   

                return $results;
        }   

        public function fetchAllColumns( mysqli_stmt $stmt )
        {   
                $results = array();

                if( false === $stmt->execute() )
                        return false;

		$stmt->bind_result( $result );

                while( $stmt->fetch() )
                {   
                        $results[] = $result;
                }   

                return $results;
        }   

}

