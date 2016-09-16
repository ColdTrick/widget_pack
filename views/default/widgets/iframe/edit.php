<?php
$widget = elgg_extract('entity', $vars);

echo elgg_view_input('url', [
	'name' => 'params[iframe_url]',
	'label' => elgg_echo('widgets:iframe:settings:iframe_url'),
	'value' => $widget->iframe_url,
]);

echo elgg_view_input('text', [
	'name' => 'params[iframe_height]',
	'label' => elgg_echo('widgets:iframe:settings:iframe_height'),
	'value' => $widget->iframe_height,
]);
