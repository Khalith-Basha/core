<?php

class Url_Item extends Url_Abstract
{
	public function loadUrls()
	{
		$this->urls['mark-spam'] = array(
			'default' => $this->getBaseUrl( true ) . '?page=item&action=mark&as=spam&id=%d',
			'friendly' => $this->getBaseUrl( true ) . '?page=item&action=mark&as=spam&id=%d',
		);
	}

	public function loadRules( Rewrite $rewrite )
	{
		$rewrite->addRule( '^/item/mark/(.*?)/([0-9]+)$', 'index.php?page=item&action=mark&as=$1&id=$2');
		$rewrite->addRule( '^/item/send-friend/([0-9]+)$', 'index.php?page=item&action=send_friend&id=$1');
		$rewrite->addRule( '^/item/contact/([0-9]+)$', 'index.php?page=item&action=contact&id=$1');
		$rewrite->addRule( '^/item/new$', 'index.php?page=item&action=add');
		$rewrite->addRule( '^/item/new/([0-9]+)$', 'index.php?page=item&action=add&catId=$1');
		$rewrite->addRule( '^/item/activate/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
		$rewrite->addRule( '^/item/edit/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=edit&id=$1&secret=$2');
		$rewrite->addRule( '^/item/delete/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=delete&id=$1&secret=$2');
		$rewrite->addRule( '^/item/resource/delete/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');
		$rewrite->addRule( '^/([a-zA-Z_]{5})_(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$3&lang=$1&comments-page=$4');
		$rewrite->addRule( '^/(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$2&comments-page=$3');
		$rewrite->addRule( '^/([a-zA-Z_]{5})_(.+)_([0-9]+)$', 'index.php?page=item&id=$3&lang=$1');
		$rewrite->addRule( '^/(.+)_([0-9]+)$', 'index.php?page=item&id=$2');
	}

	public function getDetailsUrl( array $item, $locale = null )
	{
		$url = null;
		if( osc_rewrite_enabled() )
		{
			$sanitized_title = osc_sanitizeString( osc_item_title( $item ) );
			$sanitized_category = '/';
			$cat = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->hierarchy( osc_item_category_id( $item ) );
			for( $i = count( $cat ); $i > 0; $i-- )
			{
				$sanitized_category .= $cat[$i - 1]['s_slug'] . '/';
			}
			if( empty( $locale ) )
			{
				$url = $this->getBaseUrl() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_item_id( $item ) );
			}
			else
			{
				$url = $this->getBaseUrl() . sprintf('/%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_item_id( $item ) );
			}
		}
		else
		{
			$url = $this->osc_item_url_ns( osc_item_id( $item ), $locale );
		}

		if( empty( $url ) )
			throw new Exception( 'URL could not be created' );
		
		return $url;
	}
	public function osc_item_url()
	{
		$classLoader = ClassLoader::getInstance();
		$items = $classLoader->getClassInstance( 'View_Html' )->_get('items');
		if( count( $items ) > 0 )
		{
			$itemUrl = $classLoader->getClassInstance( 'Url_Item' )->getDetailsUrl( $items[0] );
			return $itemUrl;
		}

		return null;
	}
	/**
	 * Create automatically the url of the item's comments page
	 *
	 * @param mixed $page
	 * @param string $locale
	 * @return string
	 */
	public function osc_item_comments_url( array $item, $page = 'all', $locale = '') 
	{
		$itemUrls = ClassLoader::getInstance()->getClassInstance( 'Url_Item' );
		if (osc_rewrite_enabled()) 
		{
			return $itemUrls->getDetailsUrl( $item, $locale) . "?comments-page=" . $page;
		}
		else
		{
			return $itemUrls->getDetailsUrl( $item, $locale) . "&comments-page=" . $page;
		}
	}
	/**
	 * Create automatically the url of the item's comments page
	 *
	 * @param string $locale
	 * @return string
	 */
	public function osc_comment_url($locale = '') 
	{
		return osc_item_url($locale) . "?comment=" . osc_comment_id();
	}
	/**
	 * Create automatically the url of the item details page
	 *
	 * @param string $locale
	 * @return string
	 */
	public function osc_premium_url($locale = '') 
	{
		if (osc_rewrite_enabled()) 
		{
			$sanitized_title = osc_sanitizeString(osc_premium_title());
			$sanitized_category = '';
			$cat = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->hierarchy(osc_premium_category_id());
			for ($i = (count($cat)); $i > 0; $i--) 
			{
				$sanitized_category.= $cat[$i - 1]['s_slug'] . '/';
			}
			if ($locale != '') 
			{
				$path = $this->getBaseUrl() . sprintf('%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_premium_id());
			}
			else
			{
				$path = $this->getBaseUrl() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_premium_id());
			}
		}
		else
		{
			//$path = $this->getBaseUrl( true ) . sprintf('?page=item&id=%d', osc_item_id()) ;
			$path = $this->osc_item_url_ns(osc_premium_id(), $locale);
		}
		return $path;
	}
	/**
	 * Create the no friendly url of the item using the id of the item
	 *
	 * @param int $id the primary key of the item
	 * @param $locale
	 * @return string
	 */
	public function osc_item_url_ns($id, $locale = '') 
	{
		$path = $this->getBaseUrl( true ) . '?page=item&id=' . $id;
		if ($locale != '') 
		{
			$path.= "&lang=" . $locale;
		}
		return $path;
	}

	/**
	 * Gets url for editing an item
	 *
	 * @param string $secret
	 * @param int $id
	 * @return string
	 */
	public function osc_item_edit_url( array $item, $secret = '', $id = '') 
	{
		if ($id == '') $id = osc_item_id( $item );
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/item/edit/' . $id . '/' . $secret;
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=item&action=item_edit&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
		}
	}
	/**
	 * Gets url for delete an item
	 *
	 * @param string $secret
	 * @param int $id
	 * @return string
	 */
	public function osc_item_delete_url( array $item, $secret = '', $id = '') 
	{
		if ($id == '') $id = osc_item_id( $item );
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/item/delete/' . $id . '/' . $secret;
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=item&action=item_delete&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
		}
	}
	/**
	 * Gets url for activate an item
	 *
	 * @param string $secret
	 * @param int $id
	 * @return string
	 */
	function osc_item_activate_url( array $item, $secret = '', $id = '') 
	{
		if ($id == '') $id = osc_item_id( $item );
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/item/activate/' . $id . '/' . $secret;
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=item&action=activate&id=' . $id . ($secret != '' ? '&secret=' . $secret : '');
		}
	}
	/**
	 * Gets url for deleting a resource of an item
	 *
	 * @param int $id of the resource
	 * @param int $item
	 * @param string $code
	 * @param string $secret
	 * @return string
	 */
	function osc_item_resource_delete_url($id, $item, $code, $secret = '') 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/item/resource/delete/' . $id . '/' . $item . '/' . $code . ($secret != '' ? '/' . $secret : '');
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=item&action=deleteResource&id=' . $id . '&item=' . $item . '&code=' . $code . ($secret != '' ? '&secret=' . $secret : '');
		}
	}
	/**
	 * Gets url of send a friend (current item)
	 *
	 * @return string
	 */
	function osc_item_send_friend_url( array $item )
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/item/send-friend/' . osc_item_id( $item );
		}
		else
		{
			return $this->getBaseUrl( true ) . "?page=item&action=send_friend&id=" . osc_item_id( $item );
		}
	}


	/**
	 * Create automatically the url to post an item in a category
	 *
	 * @return string
	 */
	function osc_item_post_url_in_category( $category ) 
	{
		if ( !is_null( $category ) ) 
		{
			$categoryId = $category['pk_i_id'];
			if (osc_rewrite_enabled()) 
			{
				$path = $this->getBaseUrl() . '/item/new/' . $categoryId;
			}
			else
			{
				$path = sprintf($this->getBaseUrl( true ) . '?page=item&action=add&catId=%d', $categoryId );
			}
		}
		else
		{
			$path = $this->osc_item_post_url();
		}
		return $path;
	}
	/**
	 *  Create automatically the url to post an item
	 *
	 * @return string
	 */
	function osc_item_post_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = $this->getBaseUrl() . '/item/new';
		}
		else
		{
			$path = sprintf($this->getBaseUrl( true ) . '?page=item&action=add');
		}
		return $path;
	}

	/**
	 * Retrun link for mark as bad category the current item.
	 *
	 * @return string
	 */
	function osc_item_link_bad_category( array $item ) 
	{
		return $this->getBaseUrl( true ) . '?page=item&action=mark&as=badcat&id=' . osc_item_id( $item );
	}
	/**
	 * Gets link for mark as repeated the current item
	 *
	 * @return string
	 */
	function osc_item_link_repeated( array $item ) 
	{
		return $this->getBaseUrl(true) . "?page=item&action=mark&as=repeated&id=" . osc_item_id( $item );
	}
	/**
	 * Gets link for mark as offensive the current item
	 *
	 * @return string
	 */
	function osc_item_link_offensive( array $item ) 
	{
		return $this->getBaseUrl(true) . "?page=item&action=mark&as=offensive&id=" . osc_item_id( $item );
	}
	/**
	 * Gets link for mark as expired the current item
	 *
	 * @return string
	 */
	function osc_item_link_expired( array $item )
	{
		return $this->getBaseUrl(true) . "?page=item&action=mark&as=expired&id=" . osc_item_id( $item );
	}

}

