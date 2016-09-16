<?php

$widget = elgg_extract('entity', $vars);

$max_messages = sanitise_int($widget->max_messages, false);
if (empty($max_messages)) {
	$max_messages = 5;
}

$yes_no_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

echo elgg_view_input('text', [
	'name' => 'params[max_messages]',
	'label' => elgg_echo('widget:numbertodisplay'),
	'value' => $max_messages,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_view_input('select', [
	'name' => 'params[only_unread]',
	'label' => elgg_echo('widgets:messages:settings:only_unread'),
	'value' => $widget->only_unread,
	'options_values' => $yes_no_options,
]);
