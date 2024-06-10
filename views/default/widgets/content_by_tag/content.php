<?php

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use ColdTrick\WidgetPack\ContentByTag;

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->content_count;
if ($count < 1) {
	$count = 8;
}

$object_subtypes = $widget->content_type;
if (empty($object_subtypes)) {
	$supported_types = ContentByTag::getSupportedContent();
	$object_subtypes = array_shift($supported_types);
}

$tags_option = $widget->tags_option;
if (!in_array($tags_option, ['and', 'or'])) {
	$tags_option = 'and';
}

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
	'no_results' => true,
];

// do not include container object in results
$container = $widget->getContainerEntity();
if ($container instanceof \ElggObject) {
	$options['wheres'][] = function(QueryBuilder $qb) use ($container) {
		return $qb->compare('e.guid', '!=', $container->guid, ELGG_VALUE_INTEGER);
	};
}

$values = is_array($widget->tags) ? $widget->tags : elgg_string_to_array((string) $widget->tags);
if (!empty($values)) {
	$options['metadata_name_value_pairs'] = [];
	if ($tags_option === 'or') {
		$options['metadata_name_value_pairs'][] = [
			'name' => 'tags',
			'value' => $values,
			'case_sensitive' => false,
		];
	} else {
		foreach ($values as $index => $value) {
			$alias = "tag_where_{$index}";
			
			$on = function (QueryBuilder $qb, $joined_alias, $main_alias) {
				return $qb->compare("{$joined_alias}.entity_guid", '=', "{$main_alias}.guid");
			};
			
			$options['joins'][] = new JoinClause(\Elgg\Database\MetadataTable::TABLE_NAME, $alias, $on);
			
			$options['wheres'][] = function(QueryBuilder $qb) use ($alias, $value) {
				$md = new MetadataWhereClause();
				$md->names = 'tags';
				$md->values = $value;
				$md->case_sensitive = false;
				return $md->prepare($qb, $alias);
			};
		}
	}
}

// excluded tags
$excluded_values = is_array($widget->excluded_tags) ? $widget->excluded_tags : elgg_string_to_array((string) $widget->excluded_tags);
if ($excluded_values) {
	foreach ($excluded_values as $value) {
		$options['wheres'][] = function(QueryBuilder $qb, $main_alias) use ($value) {
			$subquery = $qb->subquery('metadata');
			$subquery->select('entity_guid')
				->where($qb->compare('name', '=', 'tags', ELGG_VALUE_STRING))
				->andWhere($qb->compare('value', '=', $value, ELGG_VALUE_STRING));
	
			return $qb->compare("{$main_alias}.guid", 'NOT IN', $subquery->getSQL());
		};
	}
}

// owner_guids
if (!is_array($widget->owner_guids)) {
	$options['owner_guids'] = elgg_string_to_array((string) $widget->owner_guids);
} else {
	$options['owner_guids'] = $widget->owner_guids;
}

if ($widget->context === 'groups') {
	if ($widget->group_only !== 'no') {
		$options['container_guids'] = [$widget->getContainerGUID()];
	}
} elseif (elgg_view_exists('input/grouppicker')) {
	$options['container_guids'] = $widget->container_guids;
}

if ($widget->order_by === 'alpha') {
	$options['sort_by'] = [
		'property' => 'title',
		'direction' => 'asc',
	];
}

$display_option = $widget->display_option;
if (in_array($display_option, ['slim', 'simple'])) {
	$show_avatar = ($widget->show_avatar !== 'no');
	$show_timestamp = ($widget->show_timestamp !== 'no');
	
	$options['item_view'] = "widgets/content_by_tag/display/{$display_option}";
	$options['show_avatar'] = $show_avatar;
	$options['show_timestamp'] = $show_timestamp;
	$options['item_class'] = "widgets-content-by-tag-{$display_option}";
}

if ($widget->show_search_link === 'yes' && !empty($widget->tags) && elgg_is_active_plugin('search')) {
	$link_params = [
		'q' => $widget->tags,
	];
	
	if (is_string($object_subtypes)) {
		$link_params['entity_subtype'] = $object_subtypes;
		$link_params['entity_type'] = 'object';
		$link_params['search_type'] = 'entities';
	}
	
	$options['widget_more'] = elgg_view_url(elgg_http_add_url_query_elements('search', $link_params), elgg_echo('more'));
}

echo elgg_list_entities($options);
