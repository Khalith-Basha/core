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

	public function getUrlByPattern( $pattern )
	{
		return $this->osc_search_url( array( 'sPattern' => $pattern ) );
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

