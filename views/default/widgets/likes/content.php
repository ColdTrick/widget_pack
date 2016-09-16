<?php

$widget = elgg_extract('entity', $vars);

$like_type = $widget->like_type;
$interval = sanitise_int($widget->interval, false);
$limit = $widget->limit;

$options = [
	'annotation_names' => ['likes'],
	'limit' => $limit,
];

$widget_context = $widget->context;

if (!empty($interval)) {
	$options['annotation_created_time_lower'] = time() - ($interval * 24 * 60 * 60);
}

if (empty($like_type)) {
	if (in_array($widget_context, ['dashboard', 'profile'])) {
		$like_type = 'user_likes';
	} else {
		$like_type = 'most_liked';
	}
}

switch ($like_type) {
	case 'user_likes':
		// liked by user
		
		$options['annotation_owner_guids'] = [$widget->owner_guid];
		$entities = elgg_get_entities_from_annotations($options);
		break;
	case 'most_liked':
		// most liked in specific container
		
		if (in_array($widget_context, ['dashboard', 'profile'])) {
			// personal
			$options['owner_guid'] = $widget->owner_guid;
		} elseif ($widget_context == 'groups') {
			// group
			$options['container_guid'] = $widget->container_guid;
		}
		
		$entities = elgg_get_entities_from_annotation_calculation($options);
		
		break;
	case 'recently_liked':
		// recently like in specific container

		if (in_array($widget_context, ['dashboard', 'profile'])) {
			// personal
			$options['owner_guid'] = $widget->owner_guid;
		} elseif ($widget_context == 'groups') {
			// group
			$options['container_guid'] = $widget->container_guid;
		}
		
		$entities = elgg_get_entities_from_annotation_calculation($options);
		
		break;
}

if (empty($entities)) {
	echo elgg_echo('notfound');
	return;
}

$list_options = [
	'offset' => 0,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
	'list_type_toggle' => false,
];
echo elgg_view_entity_list($entities, $list_options);
