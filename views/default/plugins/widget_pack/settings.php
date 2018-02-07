<?php
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_pack:settings:disable_free_html_filter'),
	'name' => 'params[disable_free_html_filter]',
	'checked' => $plugin->disable_free_html_filter === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);
