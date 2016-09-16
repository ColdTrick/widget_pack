<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view_input('longtext', [
	'name' => 'params[html_content]',
	'label' => elgg_echo('widgets:free_html:settings:html_content'),
	'value' => $widget->html_content,
]);
