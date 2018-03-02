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

$icon = '';
if ((bool) elgg_extract('show_avatar', $vars, true)) {
	$owner = $entity->getOwnerEntity();
	$icon = elgg_view_entity_icon($owner, 'small');
}

$imprint = elgg_view('object/elements/imprint/byline', $vars);

if ((bool) elgg_extract('show_timestamp', $vars, true)) {
	$vars['time'] = elgg_extract('entity_timestamp', $vars, $entity->time_created);
	$imprint .= elgg_view('object/elements/imprint/time', $vars);
}

$imprint = elgg_format_element('div', ['class' => 'elgg-listing-imprint'], $imprint);

$subtitle = elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-subtitle',
		'elgg-subtext',
	]
], $imprint);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'tags' => false,
	'metadata' => false,
	'subtitle' => $subtitle,
	'icon' => $icon,
]);
