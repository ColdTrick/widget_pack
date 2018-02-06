<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('widgets:free_html:settings:html_content'),
	'name' => 'params[html_content]',
	'value' => $widget->html_content,
]);
