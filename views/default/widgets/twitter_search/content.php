<?php

$widget = elgg_extract('entity', $vars);

$height = (int) $widget->height;

$widget_id = $widget->widget_id;
$embed_href = $widget->embed_href;

if (empty($widget_id) && empty($embed_href)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

echo elgg_view('output/url', [
	'class' => 'twitter-timeline',
	'data-dnt' => 'true',
	'data-widget-id' => $widget_id,
	'href' => $embed_href,
	'height' => $height ?: null,
]);

echo elgg_format_element('script', [
	'async' => true,
	'src' => '//platform.twitter.com/widgets.js',
	'charset' => 'utf-8',
]);
