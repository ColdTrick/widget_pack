<?php

$widget = elgg_extract('entity', $vars);
$result = '';

$dbprefix = elgg_get_config('dbprefix');

$count = sanitise_int($widget->content_count, false) ?: 8;

$object_subtypes = $widget->content_type;
if (empty($object_subtypes)) {
	$object_subtypes = array_shift(widget_pack_content_by_tag_get_supported_content());
}

$tags_option = $widget->tags_option;
if (!in_array($tags_option,['and', 'or'])) {
	$tags_option = 'and';
}

$options = [
	'type' => 'object',
	'subtypes' => $object_subtypes,
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
	'preload_owners' => true,
	'preload_containers' => true,
];

// do not include container object in results
$container = $widget->getContainerEntity();
if ($container instanceof \ElggObject) {
	$options['wheres'] = [
		function(QueryBuilder $qb) {
			return $qb->compare('e.guid', '!=', $container->guid, ELGG_VALUE_INTEGER);
		},
	];
}

$values = string_to_tag_array($widget->tags);

if (!empty($values)) {
	$options['metadata_names'] = ['tags', 'universal_categories'];
	$options['metadata_values'] = $values;
}


// get names wheres and joins
// $names_where = '';
// $values_where = '';

// $name_ids = [];
// $names = ['tags', 'universal_categories'];

// foreach ($names as $name) {
// 	$name_ids[] = elgg_get_metastring_id($name);
// }

// $values = string_to_tag_array($widget->tags);

// if (!empty($values)) {
// 	$names_str = implode(',', $name_ids);
// 	$names_where = "(n_table.name_id IN ($names_str))";
	
// 	$value_ids = [];
// 	foreach ($values as $value) {
// 		$value_ids[] = elgg_get_metastring_id($value, false);
// 	}
	
// 	$values_where .= '(';
// 	foreach ($value_ids as $i => $value_id) {
// 		$value_id = implode(',', $value_id);
		
// 		if ($i !== 0) {
// 			if ($tags_option == 'and') {
// 				// AND
				
// 				if ($i > 2) {
// 					// max 3 ANDs
// 					break;
// 				}
				
// 				$joins[] = "JOIN {$dbprefix}metadata n_table{$i} on e.guid = n_table{$i}.entity_guid";
				
// 				$values_where .= " AND (n_table{$i}.name_id IN ($names_str) AND n_table{$i}.value_id IN ({$value_id}))";
// 			} else {
// 				$values_where .= " OR (n_table.value_id IN ({$value_id}))";
// 			}
// 		} else {
// 			$values_where .= "(n_table.value_id IN ({$value_id}))";
// 		}
// 	}
// 	$values_where .= ')';
// }

// excluded tags
// $excluded_values = string_to_tag_array($widget->excluded_tags);
// if ($excluded_values) {
// 	// and value_id not in
// 	$value_ids = [];
	
// 	foreach ($excluded_values as $excluded_value) {
// 		$value_ids += elgg_get_metastring_id($excluded_value, false);
// 	}
	
// 	if (!empty($values_where)) {
// 		$values_where .= ' AND ';
// 	}
	
// 	$values_where .= "e.guid NOT IN (SELECT DISTINCT entity_guid FROM {$dbprefix}metadata WHERE name_id IN (" . implode(",", $name_ids) . ") AND value_id IN (" . implode(",", $value_ids) . "))";
// }

// owner_guids
if (!is_array($widget->owner_guids)) {
	$options['owner_guids'] = string_to_tag_array($widget->owner_guids);
} else {
	$options['owner_guids'] = $widget->owner_guids;
}

if ($widget->context == 'groups') {
	if ($widget->group_only !== 'no') {
		$options['container_guids'] = [$widget->getContainerGUID()];
	}
} elseif (elgg_view_exists('input/grouppicker')) {
	$options['container_guids'] = $widget->container_guids;
}

if ($widget->order_by == 'alpha') {
	$options['order_by_metadata'] = [
		'name' => 'title',
		'direction' => 'asc',
		'as' => 'text',
	];
}

if (in_array($display_option, ['slim', 'simple'])) {
	$num_highlighted = (int) $widget->highlight_first;
	$show_avatar = ($widget->show_avatar !== 'no');
	$show_timestamp = ($widget->show_timestamp !== 'no');
	
	$options['item_view'] = "widgets/content_by_tag/display/{$display_option}";
	$options['show_avatar'] = $show_avatar;
	$options['show_timestamp'] = $show_timestamp;
// 	$options['show_highlighted'] = ($index < $num_highlighted);
}

$result = elgg_list_entities($options);

if (empty($result)) {
	echo elgg_echo('notfound');
	return;
}

echo $result;

if ($widget->show_search_link !== 'yes' || empty($widget->tags) || !elgg_is_active_plugin('search')) {
	return;
}
	
$link_params = [
	'q' => $widget->tags,
];

if (count($object_subtypes) == 1) {
	$link_params['entity_subtype'] = $content_type[0];
	$link_params['entity_type'] = 'object';
	$link_params['search_type'] = 'entities';
}

$search_link = elgg_view('output/url', [
	'text' => elgg_echo('searchtitle', [$link_params['q']]),
	'href' => elgg_http_add_url_query_elements('search', $link_params),
]);

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $search_link);
