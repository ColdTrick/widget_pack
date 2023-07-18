<?php

$widget = elgg_extract('entity', $vars);

$url = $widget->iframe_url;
if (empty($url)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

$style = 'width: 100%;';

$height = (int) $widget->iframe_height;
if (empty($height)) {
	$style .= 'height: 100%;';
} else {
	$style .= "height: {$height}px;";
}

echo elgg_view('output/iframe', [
	'src' => $url,
	'style' => $style,
]);
