<?php

class Server
{

	public function hasHttpReferer()
	{
		return !empty( $_SERVER['HTTP_REFERER'] );
	}

	public function getHttpReferer()
	{
		return $this->hasHttpReferer() ? urldecode( $_SERVER['HTTP_REFERER'] ) : null;
	}

	public function getSearchEngineKeywords()
	{
		if( !$this->hasHttpReferer() )
			return null;

		$httpReferer = $this->getHttpReferer();
		$searchEngine = null;
		$keywords = array();

		$paramSeparator = eregi( '(\?q=|\?qt=|\?p=)', $httpReferer) ? '\?' : '\&';
		// Google
		if (eregi('www\.google', $httpReferer))
		{
			preg_match('#{$paramSeparator}q=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'Google';
		}
		// Yahoo
		else if (eregi('(yahoo\.com|search\.yahoo)', $httpReferer))
		{
			preg_match('#{$paramSeparator}p=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'Yahoo';
		}
		// MSN
		else if (eregi('search\.msn', $httpReferer))
		{
			preg_match('#{$paramSeparator}q=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'MSN';
		}
		// AllTheWeb
		else if (eregi('www\.alltheweb', $httpReferer))
		{
			preg_match('#{$paramSeparator}q=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'AllTheWeb';
		}
		// Looksmart
		else if (eregi('(looksmart\.com|search\.looksmart)', $httpReferer))
		{
			preg_match('#{$paramSeparator}qt=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'Looksmart';
		}
		// AskJeeves
		else if (eregi('(askjeeves\.com|ask\.com)', $httpReferer))
		{
			preg_match('#{$paramSeparator}q=(.*?)\&#si', $httpReferer, $keywords);
			$searchEngine = 'AskJeeves';
		}

		return array(
			'searchEngine' => $searchEngine,
			'keywords' => $keywords,
			'httpReferer' => $httpReferer
		);
	}
}

