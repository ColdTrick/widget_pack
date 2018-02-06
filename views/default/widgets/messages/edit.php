<?php

$widget = elgg_extract('entity', $vars);

$yes_no_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'max_messages',
	'entity' => $widget,
	'default' => 5,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:messages:settings:only_unread'),
	'name' => 'params[only_unread]',
	'value' => $widget->only_unread,
	'options_values' => $yes_no_options,
]);
