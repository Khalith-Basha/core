<?php

class Url_Item
{
	private $urls;

	public function __construct()
	{
		$this->urls = array();

		$this->loadUrls();
	}

	protected function loadUrls()
	{
		$this->urls['mark-spam'] = array(
			'default' => osc_base_url( true ) . '?page=item&action=mark&as=spam&id=%d'
		);
		$this->urls['item-details'] = array(
			'default' => 'xxx',
			'friendly' => 'xxx'
		);
	}

	public function create( $name )
	{
		$arguments = func_get_args();
		if( count( $arguments ) > 0 )
		{
			$name = $arguments[0];
			$arguments = array_slice( $arguments, 1 );
		}
		$url = null;

		if( !empty( $this->urls[ $name ]['friendly'] ) )
			$url = $this->urls[ $name ]['friendly'];
		if( !empty( $this->urls[ $name ]['default'] ) )
			$url = $this->urls[ $name ]['default'];

		if( is_null( $url ) )
			throw new Exception( 'URL not found: ' . $name );

		return vsprintf( $url, $arguments );
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
				$url = osc_base_url() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_item_id());
			}
			else
			{
				$url = osc_base_url() . sprintf('/%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_item_id());
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

