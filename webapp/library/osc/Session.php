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

class Session
{
	private $session;
	private $config;

	public function __construct()
	{
		$this->session = $this->config = null;

		// @TODO Use DI here.
		$config = ClassLoader::getInstance()
			->getClassInstance( 'Config' );
		if( $config->hasConfig( 'session' ) )
		{
			$sessionConfig = $config->getConfig( 'session' );
			if( !empty( $sessionConfig['saveHandler'] ) )
			{
				ini_set( 'session.save_handler', $sessionConfig['saveHandler'] );
			}
			if( !empty( $sessionConfig['savePath'] ) )
			{
				ini_set( 'session.save_path', $sessionConfig['savePath'] );
			}
		}

		session_name( APP_NAME );
		session_start();

		$this->session = &$_SESSION;
		if ($this->_get('keepForm') == '') 
		{
			$this->_set('keepForm', array());
		}
		if ($this->_get('form') == '') 
		{
			$this->_set('form', array());
		}
	}

	public function setConfig( Config $config )
	{
		$this->config = $config;
	}

	/**
	 * @return boolean
	 */
	public function destroy() 
	{
		return session_destroy();
	}

	public function _set($key, $value) 
	{
		$this->session[$key] = $value;
	}

	public function _get($key) 
	{
		return isset( $this->session[ $key ] ) ?
			$this->session[ $key ] :
			null;
	}

	function remove($key) 
	{
		unset($this->session[$key]);
	}

	function _setReferer($value) 
	{
		$this->session['osc_http_referer'] = $value;
		$this->session['osc_http_referer_state'] = 0;
	}

	function _getReferer() 
	{
		if (isset($this->session['osc_http_referer'])) 
		{
			return ($this->session['osc_http_referer']);
		}

		return null;
	}

	public function addFlashMessage( $text, $type = 'INFO' )
	{
		$messages = $this->_get( 'messages' );
		if( is_null( $messages ) )
			$messages = array();
	
		$messages[] = array(
			'class' => 'FlashMessage',
			'id' => 'FlashMessage',
			'text' => $text,
			'type' => $type
		);

		$this->session['messages'] = $messages;
	}

	public function getFlashMessages()
	{
		$messages = $this->_get( 'messages' );
		unset( $this->session['messages'] );
		return $messages;
	}

	function _keepForm($key) 
	{
		$aKeep = $this->_get('keepForm');
		$aKeep[$key] = 1;
		$this->_set('keepForm', $aKeep);
	}

	function _dropKeepForm($key = '') 
	{
		$aKeep = $this->_get('keepForm');
		if ($key != '') 
		{
			unset($aKeep[$key]);
			$this->_set('keepForm', $aKeep);
		}
		else
		{
			$this->_set('keepForm', array());
		}
	}

	function _setForm($key, $value) 
	{
		$form = $this->_get('form');
		$form[$key] = $value;
		$this->_set('form', $form);
	}

	function _getForm($key = '') 
	{
		$form = $this->_get('form');
		if ($key != '') 
		{
			if (isset($form[$key])) 
			{
				return ($form[$key]);
			}

			return null;
		}
		else
		{
			return $form;
		}
	}
	function _getKeepForm() 
	{
		return $this->_get('keepForm');
	}
	function _clearVariables() 
	{
		$form = $this->_get('form');
		$aKeep = $this->_get('keepForm');
		if (is_array($form)) 
		{
			foreach ($form as $key => $value) 
			{
				if (!isset($aKeep[$key])) 
				{
					unset($this->session['form'][$key]);
				}
			}
		}
		if (isset($this->session['osc_http_referer_state'])) 
		{
			$this->session['osc_http_referer_state']++;
			if ((int)($this->session['osc_http_referer_state']) >= 2) 
			{
				unset($this->session['osc_http_referer']);
				unset($this->session['osc_http_referer_state']);
			}
		}
	}
}
