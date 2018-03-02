<?php

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;

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

$tag_names = elgg_extract('tag_names', $vars, ['tags', 'universal_categories']);

$options = [
	'type' => 'object',
	'subtypes' => $object_subtypes,
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
	'wheres' => [],
	'joins' => [],
	'preload_owners' => true,
	'preload_containers' => true,
];

// do not include container object in results
$container = $widget->getContainerEntity();
if ($container instanceof \ElggObject) {
	$options['wheres'][] = function(QueryBuilder $qb) {
		return $qb->compare('e.guid', '!=', $container->guid, ELGG_VALUE_INTEGER);
	};
}

$values = string_to_tag_array($widget->tags);
if (!empty($values)) {
	$options['metadata_name_value_pairs'] = [];
	if ($tags_option === 'or') {
		$options['metadata_name_value_pairs'][] = [
			'name' => $tag_names,
			'value' => $values,
		];
	} else {
		foreach($values as $index => $value) {
			$alias = "tag_where_{$index}";
			
			$on = function (QueryBuilder $qb, $joined_alias, $main_alias) {
				return $qb->compare("$joined_alias.entity_guid", '=', "$main_alias.guid");
			};
			
			$options['joins'][] = new JoinClause(QueryBuilder::TABLE_METADATA, $alias, $on);
			
			$options['wheres'][] = function(QueryBuilder $qb) use ($alias, $value, $tag_names) {
				$md = new MetadataWhereClause();
				$md->names = $tag_names;
				$md->values = $value;
				return $md->prepare($qb, $alias);
			};
		}
	}
}

// excluded tags
$excluded_values = string_to_tag_array($widget->excluded_tags);
if ($excluded_values) {
	$options['wheres'][] = function(QueryBuilder $qb, $main_alias) use ($tag_names, $excluded_values) {
		$subquery = $qb->subquery('metadata', 'ex_tags');
		$subquery->select('DISTINCT entity_guid')
			->where($qb->compare('ex_tags.name', 'IN', $tag_names, ELGG_VALUE_STRING))
			->andWhere($qb->compare('ex_tags.value', 'IN', $excluded_values, ELGG_VALUE_STRING));

		return $qb->compare("{$main_alias}.guid", "NOT IN", $subquery->getSQL());
	};
}

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
