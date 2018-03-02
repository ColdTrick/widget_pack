<?php
/**
 * Show the slim (single line) version of an entity
 *
 * @uses $vars['entity']           the entity to show, should be an instance of ElggObject
 * @uses $vars['show_timestamp']   show the entity timestamp (default: true)
 * @uses $vars['entity_timestamp'] the entity timestamp to show (default: time_created)
 * @uses $vars['entity_url']       the entity url to show (default: $entity->getURL())
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggObject)) {
	return;
}

$title = '';
if ((bool) elgg_extract('show_timestamp', $vars, true)) {
	$entity_timestamp = (int) elgg_extract('entity_timestamp', $vars, $entity->time_created);
	
	$year = date('Y', $entity_timestamp);
	$month = date('m', $entity_timestamp);
	$day = date('j', $entity_timestamp);
	$day_of_the_week = date('w', $entity_timestamp);
	
	$short_month = elgg_echo("date:month:short:{$month}", [$day]);
	$short_day = elgg_echo("date:weekday:short:{$day_of_the_week}");
	$time = date('G:i:s', $entity_timestamp);
	
	$title .= elgg_format_element('span', ['title' => "{$short_day}, {$short_month} {$year} {$time}"], $short_month);
	$title .= ' - ';
}

$title .= elgg_view('output/url', [
	'text' => elgg_get_excerpt($entity->getDisplayName(), 100),
	'href' => elgg_extract('entity_url', $vars, $entity->getURL()),
]);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'title' => $title,
	'tags' => false,
	'metadata' => false,
	'subtitle' => false,
]);
