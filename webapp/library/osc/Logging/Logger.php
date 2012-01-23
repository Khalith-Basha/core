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
abstract class Abstract_Logging_Logger
{
	/**
	 * Log a message with the INFO level.
	 * @param <type> $message
	 */
	abstract function info($message = '', $caller = null);
	/**
	 * Log a message with the WARN level.
	 * @param <type> $message
	 */
	abstract function warn($message = '', $caller = null);
	/**
	 * Log a message with the ERROR level.
	 * @param <type> $message
	 */
	abstract function error($message = '', $caller = null);
	/**
	 * Log a message with the DEBUG level.
	 * @param <type> $message
	 */
	abstract function debug($message = '', $caller = null);
	/**
	 * Log a message object with the FATAL level including the caller.
	 * @param <type> $message
	 */
	abstract function fatal($message = '', $caller = null);
}

class Logging_Logger extends Abstract_Logging_Logger
{
	public function info( $message = '', $caller = null )
	{
	}
	public function warn( $message = '', $caller = null )
	{
	}
	public function error( $message = '', $caller = null )
	{
	}
	public function debug( $message = '', $caller = null )
	{
	}
	public function fatal( $message = '', $caller = null )
	{
	}
	public function insertLog()
	{
	}
}

