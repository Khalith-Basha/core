<?php

class Url_Resource extends Url_Abstract
{
	public function loadUrls()
	{
	}

	/**
	 * Gets thumbnail url of current resource
	 *
	 * @return <type>
	 */
	function osc_resource_thumbnail_url( array $resource ) 
	{
		return (string)osc_resource_path( $resource ) . osc_resource_id( $resource ) . "_thumbnail." . osc_resource_field( $resource, "s_extension");
	}
	/**
	 * Gets url of current resource
	 *
	 * @return string
	 */
	function osc_resource_url( array $resource ) 
	{
		return (string)osc_resource_path( $resource ) . osc_resource_id( $resource ) . "." . osc_resource_field( $resource, "s_extension");
	}
	/**
	 * Gets original resource url of current resource
	 *
	 * @return string
	 */
	function osc_resource_original_url( array $resource ) 
	{
		return (string)osc_resource_path( $resource ) . osc_resource_id( $resource ) . "_original." . osc_resource_field( $resource, "s_extension");
	}
}

