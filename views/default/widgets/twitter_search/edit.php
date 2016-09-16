<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view_input('plaintext', [
	'name' => 'params[embed_code]',
	'label' => elgg_echo('widgets:twitter_search:embed_code'),
	'help' => elgg_view('output/url', [
		'href' => 'https://twitter.com/settings/widgets',
		'target' => '_blank',
		'text' => elgg_echo('widgets:twitter_search:embed_code:help'),
	]),
]);

echo elgg_view_input('text', [
	'name' => 'params[height]',
	'label' => elgg_echo('widgets:twitter_search:height'),
	'value' => sanitise_int($widget->height, false),
	'size' => 4,
	'maxlength' => 4,
]);
