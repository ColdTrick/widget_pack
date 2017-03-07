<?php
/**
 * Show the slim highlighted version of an entity
 *
 * @uses $vars['entity']           the entity to show, should be an instance of ElggObject
 * @uses $vars['show_avatar']      show the owner avatar (default: true)
 * @uses $vars['show_timestamp']   show the entity timestamp (default: true)
 * @uses $vars['entity_timestamp'] the entity timestamp to show (default: time_created)
 * @uses $vars['entity_url']       the entity url to show (default: $entity->getURL())
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggObject)) {
	return;
}

$icon = '';
if ((bool) elgg_extract('show_avatar', $vars, true)) {
	$icon = elgg_view_entity_icon($entity->getOwnerEntity(), 'small');
}

$text = elgg_view('output/url', [
	'href' => elgg_extract('entity_url', $vars, $entity->getURL()),
	'text' => $entity->getDisplayName(),
]);
$text .= elgg_format_element('br');

$description = elgg_get_excerpt($entity->description, 170);

if ((bool) elgg_extract('show_timestamp', $vars, true)) {
	
	$entity_timestamp = (int) elgg_extract('entity_timestamp', $vars, $entity->time_created);
	
	$year = date('Y', $entity_timestamp);
	$month = date('m', $entity_timestamp);
	$day = date('j', $entity_timestamp);
	$day_of_the_week = date('w', $entity_timestamp);
	
	$short_month = elgg_echo("date:month:short:{$month}", [$day]);
	$short_day = elgg_echo("date:weekday:short:{$day_of_the_week}");
	$time = date('G:i:s', $entity_timestamp);
	
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
		'is_trusted' => true,
		'class' => 'mls',
	]);
}

echo elgg_view_image_block($icon, $text);
