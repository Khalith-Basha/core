<?php

class Url_Search extends Url_Abstract
{
	public function loadUrls()
	{
	}

	public function loadRules( Rewrite $rewrite )
	{
		$rewrite->addRule('^/feed$', 'index.php?page=search&sFeed=rss');
		$rewrite->addRule('^/feed/(.+)$', 'index.php?page=search&sFeed=$1');
		$rewrite->addRule('^/search/(.*)$', 'index.php?page=search&sPattern=$1');
		$rewrite->addRule('^/s/(.*)$', 'index.php?page=search&sPattern=$1');
		$rewrite->addRule('^/(.+)$', 'index.php?page=search&sCategory=$2');
	}

	/**
	 * Create automatically the url of a category
	 *
	 * @param string $pattern
	 * @return string the url
	 */
	function osc_search_category_url( array $category, $pattern = '') 
	{
		$path = '';
		if (osc_rewrite_enabled()) 
		{
			if ($category != '') 
			{
				$categoryModel = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
				$category = $categoryModel->hierarchy($category['pk_i_id']);
				$sanitized_category = "";
				for ($i = count($category); $i > 0; $i--) 
				{
					$sanitized_category.= $category[$i - 1]['s_slug'] . '/';
				}
				$path = $this->getBaseUrl() . '/' . $sanitized_category;
			}
			if ($pattern != '') 
			{
				if ($path == '') 
				{
					$path = $this->getBaseUrl() . '/search/' . $pattern;
				}
				else
				{
					$path.= '/search/' . $pattern;
				}
			}
		}
		else
		{
			$path = sprintf($this->getBaseUrl(true) . '?page=search&sCategory=%d', $category['pk_i_id']);
		}
		return $path;
	}

	/**
	 * Gets current page url
	 *
	 * @param string $locale
	 * @return string
	 */
	public function getUrl( array $page, $locale = null )
	{
		if( empty( $locale ) ) 
		{
			if (osc_rewrite_enabled()) 
			{
				return $this->getBaseUrl() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id") . "-" . $locale;
			}
			else
			{
				return $this->getBaseUrl(true) . "?page=page&id=" . osc_field( $page, "pk_i_id") . "&lang=" . $locale;
			}
		}
		else
		{
			if (osc_rewrite_enabled()) 
			{
				return $this->getBaseUrl() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id");
			}
			else
			{
				return $this->getBaseUrl(true) . "?page=page&id=" . osc_field( $page, "pk_i_id");
			}
		}
	}

	public function getDetailsUrl( array $item, $locale = null )
	{
		$url = null;
		if( osc_rewrite_enabled() )
		{
			$sanitized_title = osc_sanitizeString( osc_item_title() );
			$sanitized_category = '/';
			$cat = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->hierarchy( osc_item_category_id() );
			for( $i = count( $cat ); $i > 0; $i-- )
			{
				$sanitized_category .= $cat[$i - 1]['s_slug'] . '/';
			}
			if( empty( $locale ) )
			{
				$url = $this->getBaseUrl() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_item_id());
			}
			else
			{
				$url = $this->getBaseUrl() . sprintf('/%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_item_id());
			}
		}
		else
		{
			$url = osc_item_url_ns( osc_item_id(), $locale );
		}

		if( empty( $url ) )
			throw new Exception( 'URL could not be created' );
		
		return $url;
	}
	
	/**
	 * Gets for a default search (all categories, noother option)
	 *
	 * @return string
	 */
	function osc_search_show_all_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . 'search/';
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=search';
		}
	}
	/**
	 * Gets search url given params
	 *
	 * @params array $params
	 * @return string
	 */
	function osc_search_url( array $params = null ) 
	{
		$url = $this->getBaseUrl( true ) . '?page=search';
		if ($params != null) 
		{
			foreach ($params as $k => $v) 
			{
				$url.= "&" . $k . "=" . $v;
			}
		}
		return $url;
	}
	
	/**
	 * Update the search url with new options
	 *
	 * @return string
	 */
	function osc_update_search_url($params, $delimiter = '&amp;') 
	{
		$request = Params::getParamsAsArray('get');
		unset($request['osclass']);
		if (isset($request['sCategory[0]'])) 
		{
			unset($request['sCategory']);
		}
		unset($request['sCategory[]']);
		$merged = array_merge($request, $params);
		return $this->getBaseUrl(true) . "?" . http_build_query($merged, '', $delimiter);
	}

}

