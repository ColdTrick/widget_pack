<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'max_messages',
	'entity' => $widget,
	'default' => 5,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:messages:settings:only_unread'),
	'name' => 'params[only_unread]',
	'checked' => $widget->only_unread !== 'no',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);
