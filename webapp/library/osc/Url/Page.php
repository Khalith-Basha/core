<?php

class Url_Page extends Url_Abstract
{
	public function loadUrls()
	{
		$this->urls['mark-spam'] = array(
			'default' => $this->getBaseUrl( true ) . '?page=item&action=mark&as=spam&id=%d',
			'friendly' => $this->getBaseUrl( false ) . '/item/mark/spam'
		);
		$this->urls['item-details'] = array(
			'default' => 'xxx',
			'friendly' => 'xxx'
		);
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
				return $this->getBaseUrl( true ) . "?page=page&id=" . osc_field( $page, "pk_i_id") . "&lang=" . $locale;
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
				return $this->getBaseUrl( true ) . "?page=page&id=" . osc_field( $page, "pk_i_id");
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
}

