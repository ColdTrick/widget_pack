<?php
/**
 * Show the simple version of an entity
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

// simple
$owner = $entity->getOwnerEntity();

$icon = '';
if ((bool) elgg_extract('show_avatar', $vars, true)) {
	$icon = elgg_view_entity_icon($owner, 'small');
}

$text = elgg_view('output/url', [
	'href' => elgg_extract('entity_url', $vars, $entity->getURL()),
	'text' => $entity->getDisplayName(),
	'is_trusted' => true,
]);
$text .= elgg_format_element('br');
$text .= elgg_view('output/url', [
	'href' => $owner->getURL(),
	'text' => $owner->getDisplayName(),
	'is_trusted' => true,
]);

if ((bool) elgg_extract('show_timestamp', $vars, true)) {
	$friendly_time = elgg_view_friendly_time((int) elgg_extract('entity_timestamp', $vars, $entity->time_created));
	
	$text .= elgg_format_element('span', ['class' => 'elgg-quiet mls'], $friendly_time);
}

echo elgg_view_image_block($icon, $text);
