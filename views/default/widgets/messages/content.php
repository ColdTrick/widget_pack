<?php

if (!elgg_is_logged_in()) {
	echo elgg_echo('widgets:messages:not_logged_in');
	return;
}

$widget = elgg_extract('entity', $vars);
	
$max_messages = sanitise_int($widget->max_messages, false) ?: 5;

$options = [
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name_value_pairs' => ['toId' => elgg_get_logged_in_user_guid()],
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'full_view' => false,
	'limit' => $max_messages,
	'item_view' => 'widgets/messages/item',
	'no_results' => elgg_echo('messages:nomessages'),
];

if ($widget->only_unread != 'no') {
	$options['metadata_name_value_pairs']['readYet'] = 0;
}

echo elgg_list_entities($options);

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], elgg_view('output/url', [
	'href' => elgg_generate_url('add:object:messages'),
	'text' => elgg_echo('messages:add'),
]));
