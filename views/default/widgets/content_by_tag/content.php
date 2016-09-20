<?php

$widget = elgg_extract('entity', $vars);
$result = '';

$dbprefix = elgg_get_config('dbprefix');

$count = sanitise_int($widget->content_count, false);
if (empty($count)) {
	$count = 8;
}

$content_type = $widget->content_type;

if (empty($content_type)) {
	foreach (widget_pack_content_by_tag_get_supported_content() as $plugin => $subtype) {
		if (elgg_is_active_plugin($plugin)) {
			$content_type = $subtype;
			break;
		}
	}
}

if (!is_array($content_type)) {
	$content_type = [$content_type];
}

foreach ($content_type as $key => $type) {
	$content_type[$key] = sanitise_string($type);
	if ($type == 'page') {
		// merge top and bottom pages
		$content_type[] = 'page_top';
	}
}

$tags_option = $widget->tags_option;
if (!in_array($tags_option,['and', 'or'])) {
	$tags_option = 'and';
}

$wheres = [];
$joins = [];

// will always want to join these tables if pulling metastrings.
$joins[] = "JOIN {$dbprefix}metadata n_table on e.guid = n_table.entity_guid";

// get names wheres and joins
$names_where = '';
$values_where = '';

$name_ids = [];
$names = ['tags', 'universal_categories'];

foreach ($names as $name) {
	$name_ids[] = elgg_get_metastring_id($name);
}

$values = string_to_tag_array($widget->tags);

if (!empty($values)) {
	$names_str = implode(',', $name_ids);
	$names_where = "(n_table.name_id IN ($names_str))";
	
	$value_ids = [];
	foreach ($values as $value) {
		$value_ids[] = elgg_get_metastring_id($value, false);
	}

	$values_where .= '(';
	foreach ($value_ids as $i => $value_id) {
		$value_id = implode(',', $value_id);
		
		if ($i !== 0) {
			if ($tags_option == 'and') {
				// AND
				
				if ($i > 2) {
					// max 3 ANDs
					break;
				}

				$joins[] = "JOIN {$dbprefix}metadata n_table{$i} on e.guid = n_table{$i}.entity_guid";

				$values_where .= " AND (n_table{$i}.name_id IN ($names_str) AND n_table{$i}.value_id IN ({$value_id}))";
			} else {
				$values_where .= " OR (n_table.value_id IN ({$value_id}))";
			}
		} else {
			$values_where .= "(n_table.value_id IN ({$value_id}))";
		}
	}
	$values_where .= ')';
}

// excluded tags
$excluded_values = string_to_tag_array($widget->excluded_tags);
if ($excluded_values) {
	// and value_id not in
	$value_ids = [];

	foreach ($excluded_values as $excluded_value) {
		$value_ids += elgg_get_metastring_id($excluded_value, false);
	}

	if (!empty($values_where)) {
		$values_where .= ' AND ';
	}

	$values_where .= "e.guid NOT IN (SELECT DISTINCT entity_guid FROM {$dbprefix}metadata WHERE name_id IN (" . implode(",", $name_ids) . ") AND value_id IN (" . implode(",", $value_ids) . "))";
}

$access = _elgg_get_access_where_sql(['table_alias' => 'n_table']);

if ($names_where && $values_where) {
	$wheres[] = "($names_where AND $values_where AND $access)";
} elseif ($names_where) {
	$wheres[] = "($names_where AND $access)";
} elseif ($values_where) {
	$wheres[] = "($values_where AND $access)";
}

// do not include container object in results
$container = $widget->getContainerEntity();
if ($container instanceof \ElggObject) {
	$wheres[] = 'e.guid != ' . $container->guid;
}

if ($widget->order_by == 'alpha') {
	$joins[] = "JOIN {$dbprefix}objects_entity oe ON e.guid = oe.guid";
}

$options = [
	'type' => 'object',
	'subtypes' => $content_type,
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
	'joins' => $joins,
	'wheres' => $wheres,
	'preload_owners' => true,
	'preload_containers' => true,
];

// owner_guids
if (!empty($widget->owner_guids)) {
	if (!is_array($widget->owner_guids)) {
		$owner_guids = string_to_tag_array($widget->owner_guids);
	} else {
		$owner_guids = $widget->owner_guids;
	}
	
	if (!empty($owner_guids)) {
		$options['owner_guids'] = $owner_guids;
	}
}

if ($widget->context == 'groups') {
	if ($widget->group_only !== 'no') {
		$options['container_guids'] = [$widget->getContainerGUID()];
	}
} elseif (elgg_view_exists('input/grouppicker')) {
	$container_guids = $widget->container_guids;
	if (!empty($container_guids)) {
		$options['container_guids'] = $container_guids;
	}
}

if ($widget->order_by == 'alpha') {
	$options['order_by'] = 'oe.title ASC';
}

elgg_push_context('search');

$display_option = $widget->display_option;
if (in_array($display_option, ['slim','simple'])) {
	$entities = elgg_get_entities($options);
	if ($entities) {
		$num_highlighted = (int) $widget->highlight_first;

		$show_avatar = true;
		if ($widget->show_avatar == 'no') {
			$show_avatar = false;
		}

		$show_timestamp = true;
		if ($widget->show_timestamp == 'no') {
			$show_timestamp = false;
		}
		
		$list_items = '';
		foreach ($entities as $index => $entity) {
			$icon = '';
			$list_item = '';
			
			$target = null;

			if (elgg_instanceof($entity, 'object', 'bookmarks')) {
				$entity_url = $entity->address;
				if (elgg_is_active_plugin('bookmarks_tools')) {
					$link_behaviour = elgg_get_plugin_setting('link_behaviour', 'bookmarks_tools');
					if ((stripos($href, 'http:') === 0) || (stripos($href, 'https:') === 0)) {
						if (stristr($href, elgg_get_site_url()) === false) {
							$target = '_blank';
						}
					}
				}
			} else {
				$entity_url = $entity->getURL();
			}
			
			if ($display_option == 'slim') {
				// slim
				if ($index < $num_highlighted) {

					$icon = '';
					if ($show_avatar) {
						$icon = elgg_view_entity_icon($entity->getOwnerEntity(), 'small');
					}

					$text = elgg_view('output/url', [
						'href' => $entity_url,
						'text' => $entity->title,
						'target' => $target,
					]);
					$text .= elgg_format_element('br');

					$description = elgg_get_excerpt($entity->description, 170);
					
					if ($show_timestamp) {
						$year = date('Y', $entity->time_created);
						$month = date('m', $entity->time_created);
						$day = date('j', $entity->time_created);
						$day_of_the_week = date('w', $entity->time_created);
						
						$short_month = elgg_echo("date:month:short:{$month}", [$day]);
						$short_day = elgg_echo("date:weekday:short:{$day_of_the_week}");
						$time = date('G:i:s', $entity->time_created);
						
						$text .= elgg_format_element('span', ['title' => "{$short_day}, {$short_month} {$year} {$time}"], "{$short_day}, {$short_month} {$year}");
						
						if (!empty($description)) {
							$text .= ' - ';
						}
					}
					
					$text .= $description;
					if (elgg_substr($description, -3, 3) == '...') {
						$text .= elgg_view('output/url', [
							'href' => $entity->getURL(),
							'text' => strtolower(elgg_echo('more')),
							'class' => 'mls',
						]);
					}

					$list_item = elgg_view_image_block($icon, $text);
				} else {
					
					$text = '';
					if ($show_timestamp) {
						$year = date('Y', $entity->time_created);
						$month = date('m', $entity->time_created);
						$day = date('j', $entity->time_created);
						$day_of_the_week = date('w', $entity->time_created);
						
						$short_month = elgg_echo("date:month:short:{$month}", [$day]);
						$short_day = elgg_echo("date:weekday:short:{$day_of_the_week}");
						$time = date('G:i:s', $entity->time_created);
						
						$text .= elgg_format_element('span', ['title' => "{$short_day}, {$short_month} {$year} {$time}"], $short_month);
						$text .= ' - ';
					}
					
					$text .= elgg_view('output/url', [
						'href' => $entity_url,
						'text' => $entity->title,
					]);
					
					$list_item = elgg_format_element('div', [], $text);
				}
			} else {
				// simple
				$owner = $entity->getOwnerEntity();

				$icon = '';
				if ($show_avatar) {
					$icon = elgg_view_entity_icon($owner, 'small');
				}
				
				$text = elgg_view('output/url', ['href' => $entity_url, 'text' => $entity->title]);
				$text .= elgg_format_element('br');
				$text .= elgg_view('output/url', [
					'href' => $owner->getURL(),
					'text' => $owner->name,
				]);

				if ($show_timestamp) {
					$text .= elgg_format_element('span', ['class' => 'elgg-quiet mls'], elgg_view_friendly_time($entity->time_created));
				}

				$list_item = elgg_view_image_block($icon, $text);
			}

			$list_items .= elgg_format_element('li', ['class' => 'elgg-item'], $list_item);
		}

		$result .= elgg_format_element('ul', ['class' => 'elgg-list'], $list_items);
	}
} else {
	$result = elgg_list_entities($options);
}

if (empty($result)) {
	$result = elgg_echo('notfound');
} elseif ($widget->show_search_link == 'yes' && !empty($widget->tags) && elgg_is_active_plugin('search')) {
	$tags = $widget->tags;
	
	if (elgg_is_active_plugin('search_advanced')) {
		$tags_text = $tags;
	} else {
		$tags = string_to_tag_array($tags);
		$tags_text = $tags[0];
	}
	
	$search_postfix = '';
	if (count($content_type) == 1) {
		$search_postfix = '&entity_subtype=' . $content_type[0] . '&entity_type=object&search_type=entities';
	}
	
	$search_link = elgg_view('output/url', [
		'text' => elgg_echo('searchtitle', [$tags_text]),
		'href' => 'search?q=' . $tags_text . $search_postfix,
	]);
	
	$result .= elgg_format_element('div', ['class' => 'elgg-widget-more'], $search_link);
}

echo $result;

elgg_pop_context();
