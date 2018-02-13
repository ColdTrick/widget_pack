<?php
$widget = elgg_extract('entity', $vars);

if (empty($widget->html_content)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

echo elgg_view('output/longtext', [
	'value' => $widget->html_content,
	'sanitize' => false,
	'autop' => false,
]);
