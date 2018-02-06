<?php

$widget = elgg_extract('entity', $vars);

$noyes_options = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'member_count',
	'entity' => $widget,
	'default' => 8,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:index_members:user_icon'),
	'name' => 'params[user_icon]',
	'options_values' => $noyes_options,
	'value' => $widget->user_icon,
]);
