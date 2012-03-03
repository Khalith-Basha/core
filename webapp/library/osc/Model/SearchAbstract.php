<?php

abstract class Model_SearchAbstract extends DAO
{
	private $queryString;
	private $rowsPerPage;
	private $page;
	private $facets;
	private $orderColumn;
	private $orderDirection;

	public function __construct( $expired = false )
	{
		parent::__construct();

		$this->queryString = '';
		$this->rowsPerPage = 10;
		$this->page = 0;
		$this->facets = array();
	}

	public function setOrderColumn( $orderColumn )
	{
		$this->orderColumn = $orderColumn;
	}

	public function setOrderDirection( $orderDirection )
	{
		$this->orderDirection = $orderDirection;
	}

	public function setQueryString( $queryString )
	{
		$this->queryString = $queryString;
	}

	public function setRowsPerPage( $rowsPerPage )
	{
		$this->rowsPerPage = $rowsPerPage;
	}

	public function setPage( $page )
	{
		$this->page = $page;
	}

	public function getFacets()
	{
		return $this->facets;
	}
}

