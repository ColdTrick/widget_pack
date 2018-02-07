<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'member_count',
	'entity' => $widget,
	'default' => 8,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:index_members_online:user_icon'),
	'name' => 'params[user_icon]',
	'checked' => $widget->user_icon === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);
