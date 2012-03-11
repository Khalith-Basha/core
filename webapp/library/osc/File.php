<?php

class File
{
	private $path;

	public function __construct()
	{
		$this->path = null;
	}

	public function setPath( $path )
	{
		$this->path = $path;
	}

	private function checkFile()
	{
		if( empty( $this->path ) )
		{
			throw new Exception( 'File path is empty' );
		}
	}

	public function getContents()
	{
		return file_get_contents( $this->path );
	}

	public function exists()
	{
		return file_exists( $this->path );
	}

	public function copyFrom( $path )
	{
		return copy( $path, $this->path );
	}

	public function touch()
	{
		$this->checkFile();
		if( false === touch( $this->path ) )
		{
			throw new Exception( 'File could not be touched: ' . $this->path );
		}
		return true;
	}

	public function delete()
	{
		$this->checkFile();
		if( false === unlink( $this->path ) )
		{
			throw new Exception( 'File could not be deleted: ' . $this->path );
		}
		return true;

	}
}

