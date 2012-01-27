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

class RewriteRule
{
	public $reDomain;
	public $rePath;

	public $request;

	public function __construct( $request, $rePath, $reDomain = null )
	{
		$this->request = $request;
		$this->rePath = $rePath;
		$this->reDomain = $reDomain;
	}
}

class Rewrite
{
	private $rules;
	private $location;
	private $section;

	public function __construct() 
	{
		$this->rules = array();
		$this->location = null;
		$this->section = null;
	}
	public function getRules() 
	{
		return $this->rules;
	}
	public function addRule( $rePath, $request, $reDomain = null )
	{
		$rule = new RewriteRule( $request, $rePath, $reDomain );
		$this->rules[] = $rule;
	}
	public function init() 
	{
		if( empty( $_SERVER['REQUEST_URI'] ) )
			return;

		$generalConfig = ClassLoader::getInstance()->getClassInstance( 'Config' )->getConfig( 'general' );
		$relativeWebUrl = $generalConfig['relativeWebUrl'];

		$serverName = $_SERVER['SERVER_NAME'];
		$request_uri = urldecode( preg_replace( '@^' . $relativeWebUrl. '@', '', $_SERVER['REQUEST_URI'] ) );
		$this->extractParams( $request_uri );
		if( osc_rewrite_enabled() )
		{
			$tmp_ar = explode( '?', $request_uri );
			$request_uri = $tmp_ar[0];
			foreach( $this->rules as $rule )
			{
				$match = $rule->rePath;
				$uri = $rule->request;
				if(
					preg_match( '#' . $match . '#', $request_uri, $m ) &&
					(
						is_null( $rule->reDomain ) ||
						preg_match( $rule->reDomain, $serverName )
					)
				)
				{
					$request_uri = preg_replace('#' . $match . '#', $uri, $request_uri);
					break;
				}
			}
		}

		$page = Params::getParam( 'page' );
		if( !empty( $page ) )
			$this->location = $page;
		$action = Params::getParam( 'action' );
		if( !empty( $action ) )
			$this->section = $action;
	}

	public function extractParams($uri = '') 
	{
		$uri_array = explode('?', $uri);
		$url = substr($uri_array[0], 1);
		$length_i = count($uri_array);
		for ($var_i = 1; $var_i < $length_i; $var_i++) 
		{
			if (preg_match_all('|&([^=]+)=([^&]*)|', '&' . $uri_array[$var_i] . '&', $matches)) 
			{
				$length = count($matches[1]);
				for ($var_k = 0; $var_k < $length; $var_k++) 
				{
					Params::setParam($matches[1][$var_k], $matches[2][$var_k]);
				}
			}
		}
	}

	public function loadRules()
	{
		$this->clearRules();

		$this->addRule('assets', 'index.php?page=index&action=assets&$1');
		// Contact rules
		$this->addRule('^contact/?$', 'index.php?page=contact');
		// Feed rules
		$this->addRule('^feed$', 'index.php?page=search&sFeed=rss');
		$this->addRule('^feed/(.+)$', 'index.php?page=search&sFeed=$1');
		// Language rules
		$this->addRule('^language/(.*?)/?$', 'index.php?page=index&action=change-language&locale=$1');
		// Search rules
		$this->addRule('^search/(.*)$', 'index.php?page=search&sPattern=$1');
		$this->addRule('^s/(.*)$', 'index.php?page=search&sPattern=$1');

		// Item rules
		$this->addRule('^item/mark/(.*?)/([0-9]+)$', 'index.php?page=item&action=mark&as=$1&id=$2');
		$this->addRule('^item/send-friend/([0-9]+)$', 'index.php?page=item&action=send_friend&id=$1');
		$this->addRule('^item/contact/([0-9]+)$', 'index.php?page=item&action=contact&id=$1');
		$this->addRule('^item/new$', 'index.php?page=item&action=add');
		$this->addRule('^item/new/([0-9]+)$', 'index.php?page=item&action=add&catId=$1');
		$this->addRule('^item/activate/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
		$this->addRule('^item/edit/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=edit&id=$1&secret=$2');
		$this->addRule('^item/delete/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=delete&id=$1&secret=$2');
		$this->addRule('^item/resource/delete/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');
		$this->addRule('^([a-zA-Z_]{5})_(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$3&lang=$1&comments-page=$4');
		$this->addRule('^(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$2&comments-page=$3');
		$this->addRule('^([a-zA-Z_]{5})_(.+)_([0-9]+)$', 'index.php?page=item&id=$3&lang=$1');
		$this->addRule('^(.+)_([0-9]+)$', 'index.php?page=item&id=$2');
		// User rules
		$this->addRule('^user/login$', 'index.php?page=user&action=login');
		$this->addRule('^user/dashboard/?$', 'index.php?page=user&action=dashboard');
		$this->addRule('^user/logout$', 'index.php?page=user&action=logout');
		$this->addRule('^user/register$', 'index.php?page=user&action=register');
		$this->addRule('^user/activate/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=validate&id=$1&code=$2');
		$this->addRule('^user/activate_alert/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&email=$2&secret=$1');
		$this->addRule('^user/profile$', 'index.php?page=user&action=profile');
		$this->addRule('^user/profile/([0-9]+)$', 'index.php?page=user&action=pub_profile&id=$1');
		$this->addRule('^user/items$', 'index.php?page=user&action=items');
		$this->addRule('^user/alerts$', 'index.php?page=user&action=alerts');
		$this->addRule('^user/recover/?$', 'index.php?page=user&action=recover');
		$this->addRule('^user/forgot/([0-9]+)/(.*)$', 'index.php?page=user&action=forgot&userId=$1&code=$2');
		$this->addRule('^user/change_password$', 'index.php?page=user&action=change_password');
		$this->addRule('^user/change_email$', 'index.php?page=user&action=change_email');
		$this->addRule('^user/change_email_confirm/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');
		// Page rules
		$this->addRule('^(.*?)-p([0-9]*)$', 'index.php?page=page&id=$2');
		$this->addRule('^(.*?)-p([0-9]*)-([a-zA-Z_]*)$', 'index.php?page=page&id=$2&lang=$3');
		// Clean archive files
		$this->addRule('^(.+?)\.php(.*)$', '$1.php$2');
		// Category rules
		$this->addRule('^(.+)$', 'index.php?page=search&sCategory=$2');
	}

	public function clearRules() 
	{
		$this->rules = array();
	}
	public function set_location($location) 
	{
		$this->location = $location;
	}
	public function get_location() 
	{
		return $this->location;
	}
	public function get_section() 
	{
		return $this->section;
	}
}

