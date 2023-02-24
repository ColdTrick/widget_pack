<?php

$widget = elgg_extract('entity', $vars);

echo elgg_list_entities([
	'type' => 'user',
	'limit' => (int) $widget->member_count ?: 8,
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
	'size' => 'small',
	'no_results' => elgg_echo('widgets:index_members:no_result'),
	'metadata_name' => $widget->user_icon === 'yes' ? 'icontime' : null,
]);
