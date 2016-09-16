<?php
$widget = elgg_extract('entity', $vars);

if (empty($widget->html_content)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-output'], $widget->html_content);