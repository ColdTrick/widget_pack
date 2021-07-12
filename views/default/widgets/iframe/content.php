<?php
$widget = elgg_extract('entity', $vars);

$url = $widget->iframe_url;
if (empty($url)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

$height = (int) $widget->iframe_height;
if (empty($height)) {
	$height = '100%';
} else {
	$height .= 'px';
}

echo elgg_view('output/iframe', [
	'src' => $url,
	'width' => '100%',
	'height' => $height,
]);
