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

require_once 'osc/Services/Cache/Interface.php';

class Services_Cache_Memcached implements CacheService
{
	private $service;

	public function __construct() 
	{
		$this->service = null;

		$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
		if( $config->hasConfig( 'memcached' ) )
		{
			$this->service = new Memcached;
			$memcachedConfig = $config->getConfig( 'memcached' );
			foreach( $memcachedConfig['servers'] as $server )
			{
				if( false === $this->service->addServer( $server['host'], $server['port'] ) )
				{
					trigger_error( $this->service->getResultMessage(), E_USER_ERROR );
				}
			}
		}
	}

	public function read( $key )
	{
		if( is_null( $this->service ) )
			return null;

		return $this->service->get( $key );
	}

	public function write( $key, $content, $expiration = 3600 )
	{
		if( is_null( $this->service ) )
			return;

		if( false === $this->service->set( $key, $content, $expiration ) )
		{
			trigger_error( $this->service->getResultMessage(), E_USER_ERROR );
		}
	}
}

