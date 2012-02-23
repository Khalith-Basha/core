<?php

/**
 * Print all widgets belonging to $location
 *
 * @param string $location
 * @return void
 */
function osc_show_widgets($location) 
{
	$widget = ClassLoader::getInstance()->getClassInstance( 'Model_Widget' );
	$widgets = $widget->findByLocation($location);
	foreach ($widgets as $w)
		echo $w['s_content'];
}

function osc_meta_publish($catId = null) 
{
	echo '<div class="row">';
	FieldForm::meta_fields_input($catId);
	echo '</div>';
}

function osc_meta_edit($catId = null, $item_id = null) 
{
	echo '<div class="row">';
	FieldForm::meta_fields_input($catId, $item_id);
	echo '</div>';
}

osc_add_hook('item_form', 'osc_meta_publish');
osc_add_hook('item_edit', 'osc_meta_edit');

/**
 * Generic function for view layer, return the $field of $item
 * with specific $locate
 *
 * @param array $item
 * @param string $field
 * @param string $locale
 * @return string
 */
function osc_field( array $item, $field, $locale = null ) 
{
	if( !is_null( $item ) ) 
	{
		if( empty( $locale ) )
		{
			if (isset($item[$field])) 
			{
				return $item[$field];
			}
		}
		else
		{
			if (isset($item["locale"]) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) 
			{
				return $item["locale"][$locale][$field];
			}
			else
			{
				if (isset($item["locale"])) 
				{
					foreach ($item["locale"] as $locale => $data) 
					{
						if (isset($item["locale"][$locale][$field])) 
						{
							return $item["locale"][$locale][$field];
						}
					}

				}
			}
		}
	}
	return null;
}

/**
 * Gets prepared text, with:
 * - higlight search pattern and search city
 * - maxim length of text
 *
 * @param string $txt
 * @param int  $len
 * @param string $start_tag
 * @param string $end_tag
 * @return string
 */
function osc_highlight($txt, $len = 300, $start_tag = '<strong>', $end_tag = '</strong>') 
{
	if (strlen($txt) > $len) 
	{
		$txt = mb_substr($txt, 0, $len, 'utf-8') . "...";
	}
	$query = osc_search_pattern() . " " . osc_search_city();
	$query = trim(preg_replace('/\s\s+/', ' ', $query));
	$aQuery = explode(' ', $query);
	foreach ($aQuery as $word) 
	{
		$txt = str_replace($word, $start_tag . $word . $end_tag, $txt);
	}
	return $txt;
}

