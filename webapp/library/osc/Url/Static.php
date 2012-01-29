<?php

class Url_Static extends Url_Abstract 
{
	public function loadUrls()
	{
		$this->urls['assets-javascripts'] = array(
			'default' => osc_base_url( true ) . '?action=assets&type=javaScript&files=%s',
			'friendly' => osc_base_url( false ) . '/assets/javaScript/%s'
		);
		$this->urls['assets-stylesheets'] = array(
			'default' => osc_base_url( true ) . '?action=assets&type=styleSheet&files=%s',
			'friendly' => osc_base_url( false ) . '/assets/styleSheet/%s'
		);
	}
}

