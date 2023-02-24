<?php

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\OrderByClause;

$widget = elgg_extract('entity', $vars);

echo elgg_list_entities([
	'type' => 'user',
	'limit' => (int) $widget->member_count ?: 8,
	'wheres' => [function(QueryBuilder $qb) {
		return $qb->compare('e.last_action', '>=', (time() - 600), ELGG_VALUE_INTEGER);
	}],
	'order_by' => [new OrderByClause('last_action', 'desc')],
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
	'size' => 'small',
	'no_results' => elgg_echo('widgets:index_members_online:no_result'),
	'metadata_name' => $widget->user_icon === 'yes' ? 'icontime' : null,
]);
