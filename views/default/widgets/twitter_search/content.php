<?php

$widget = elgg_extract('entity', $vars);

$height = sanitise_int($widget->height, false);

$widget_id = $widget->widget_id;
$embed_href = $widget->embed_href;

if (empty($widget_id) && empty($embed_href)) {
	echo elgg_echo('widgets:twitter_search:not_configured');
	return;
}

$options = [
	'class' => 'twitter-timeline',
	'data-dnt' => 'true',
	'data-widget-id' => $widget_id,
	'href' => $embed_href,
];

if ($height) {
	$options['height'] = $height;
}

echo elgg_view('output/url', $options);

echo elgg_format_element('script', [
	'async' => true,
	'src' => '//platform.twitter.com/widgets.js',
	'charset' => 'utf-8',
]);
