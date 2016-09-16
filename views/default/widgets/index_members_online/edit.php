<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->member_count, false);
if (empty($count)) {
	$count = 8;
}

$noyes_options = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];

echo elgg_view_input('text', [
	'name' => 'params[member_count]',
	'label' => elgg_echo('widgets:index_members_online:member_count'),
	'value' => $count,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_view_input('select', [
	'name' => 'params[user_icon]',
	'label' => elgg_echo('widgets:index_members_online:user_icon'),
	'value' => $widget->user_icon,
	'options_values' => $noyes_options,
]);
