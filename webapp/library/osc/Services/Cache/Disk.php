<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
define('CACHE_PATH', CONTENT_PATH . '/uploads');

require 'osc/Services/Cache/Interface.php';

/**
 * This is the simplest cache service on earth.
 *
 * @author OpenSourceClassifieds
 * @version 1.0
 */
class Services_Cache_Disk implements CacheService
{
	private $objectKey;
	private $expiration;
	public function __construct($objectKey, $expiration = 900 /* 15 minutes */
	) 
	{
		$this->objectKey = $objectKey;
		$this->expiration = $expiration;
	}
	public function __destruct() 
	{
	}
	/**
	 * @return true if the object is cached and has not expired, false otherwise.
	 */
	public function check() 
	{
		$path = $this->preparePath();
		if (!file_exists($path)) return false;
		if (time() - filemtime($path) > $this->expiration) 
		{
			unlink($path);
			return false;
		}
		return true;
	}

	public function store( $key, $content ) { $this->write( $key, $content ); }

	/**
	 * Stores the object passed as parameter in the cache backend (filesystem).
	 */
	public function write( $key, $content )
	{
		$serialized = serialize( $content );
		file_put_contents( $this->preparePath(), $serialized );
	}

	/**
	 * Returns the data of the current cached object.
	 */
	public function retrieve( $key = null ) { return $this->read(); }

	public function read( $key = null )
	{
		$content = file_get_contents( $this->preparePath() );
		return unserialize( $content );
	}

	/**
	 * Constructs the path to object in filesystem.
	 */
	private function preparePath() 
	{
		return CACHE_PATH . '/' . $this->objectKey . '.cache';
	}
}

