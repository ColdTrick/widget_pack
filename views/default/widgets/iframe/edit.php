<?php
$widget = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('widgets:iframe:settings:iframe_url'),
	'name' => 'params[iframe_url]',
	'value' => $widget->iframe_url,
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widgets:iframe:settings:iframe_height'),
	'name' => 'params[iframe_height]',
	'value' => $widget->iframe_height,
]);
