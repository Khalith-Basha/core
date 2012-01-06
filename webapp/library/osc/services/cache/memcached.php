<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
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

require_once 'osc/services/cache/interface.php';

class MemcachedCacheService implements CacheService
{
	private static $singleton = null;
	public static function getInstance() 
	{
		if (is_null(self::$singleton)) 
		{
			self::$singleton = new self;
		}
		return self::$singleton;
	}
	private $service;
	private function __construct() 
	{
		$this->service = new Memcached;
		$this->service->addServer('127.0.0.1', 11211);
	}
	public function read($key) 
	{
		return $this->service->get($key);
	}
	public function write($key, $content) 
	{
		$this->service->set($key, $content, 3600);
	}
}

