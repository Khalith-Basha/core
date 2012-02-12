<?php

class Url_Static extends Url_Abstract 
{
	public function loadUrls()
	{
		$this->urls['assets-javascripts'] = array(
			'default' => $this->getBaseUrl( true ) . '?action=assets&type=javaScript&files=%s',
			'friendly' => $this->getBaseUrl( false ) . '/assets/javaScript/%s'
		);
		$this->urls['assets-stylesheets'] = array(
			'default' => $this->getBaseUrl( true ) . '?action=assets&type=styleSheet&files=%s',
			'friendly' => $this->getBaseUrl( false ) . '/assets/styleSheet/%s'
		);
	}
}

