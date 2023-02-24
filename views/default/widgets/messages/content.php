<?php

if (!elgg_is_logged_in()) {
	echo elgg_echo('widgets:messages:not_logged_in');
	return;
}

$widget = elgg_extract('entity', $vars);

$options = [
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name_value_pairs' => ['toId' => elgg_get_logged_in_user_guid()],
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'full_view' => false,
	'limit' => (int) $widget->max_messages ?: 5,
	'item_view' => 'widgets/messages/item',
	'no_results' => elgg_echo('messages:nomessages'),
	'widget_more' => elgg_view_url(elgg_generate_url('add:object:messages'), elgg_echo('messages:add')),
];

if ($widget->only_unread != 'no') {
	$options['metadata_name_value_pairs']['readYet'] = 0;
}

echo elgg_list_entities($options);
