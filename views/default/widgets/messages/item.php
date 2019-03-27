<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggMessage) {
	return;
}

$icon = '';
$byline = '';
$user_link = elgg_echo('messages:deleted_sender');

$class = ['message'];

$user = get_user($entity->fromId);
if ($user) {
	$icon = elgg_view_entity_icon($user, 'small');
	$user_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:messages', [
			'send_to' => $user->guid,
		]),
		'text' => $user->getDisplayName(),
		'is_trusted' => true,
	]);
	
	$byline = elgg_echo('email:from') . ' ' . $user_link;
}

$class[] = $entity->readYet ? 'read': 'unread';

$params = [
	'access' => false,
	'byline' => $byline,
	'show_social_menu' => false,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $summary, ['class' => $class]);
