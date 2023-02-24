<?php
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->activity_count ?: 10;

$activity_content = (array) $widget->activity_content;
if ($activity_content === 'all') {
	unset($activity_content);
}

$activity_content = (array) $activity_content;

$river_options = [
	'pagination' => false,
	'limit' => $count,
	'type_subtype_pairs' => [],
	'no_results' => elgg_echo('river:none'),
];

foreach ($activity_content as $content) {
	list($type, $subtype) = explode(',', $content);
	if (empty($type)) {
		continue;
	}
	
	$value = $subtype;
	if (array_key_exists($type, $river_options['type_subtype_pairs'])) {
		if (!is_array($river_options['type_subtype_pairs'][$type])) {
			$value = [$river_options['type_subtype_pairs'][$type]];
		} else {
			$value = $river_options['type_subtype_pairs'][$type];
		}
		
		$value[] = $subtype;
	}
	
	$river_options['type_subtype_pairs'][$type] = $value;
}

echo elgg_list_river($river_options);
