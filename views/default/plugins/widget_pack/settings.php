<?php
$plugin = elgg_extract('entity', $vars);

$noyes_options = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];

echo elgg_view_input('select', [
	'name' => 'params[disable_free_html_filter]',
	'label' => elgg_echo('widget_pack:settings:disable_free_html_filter'),
	'value' => $plugin->disable_free_html_filter,
	'options_values' => $noyes_options,
]);
