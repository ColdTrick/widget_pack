<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('widgets:twitter_search:embed_code'),
	'#help' => elgg_view('output/url', [
		'href' => 'https://twitter.com/settings/widgets',
		'target' => '_blank',
		'text' => elgg_echo('widgets:twitter_search:embed_code:help'),
	]),
	'name' => 'params[embed_code]',
	
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widgets:twitter_search:height'),
	'name' => 'params[height]',
	'value' => sanitise_int($widget->height, false),
	'size' => 4,
	'maxlength' => 4,
]);
