<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->member_count , false) ?: 8;

$options = [
	'type' => 'user',
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
	'size' => 'small',
	'no_results' => elgg_echo('widgets:index_members:no_result'),
];

if ($widget->user_icon == 'yes') {
	$options['metadata_name'] = 'icontime';
}

echo elgg_list_entities($options);
