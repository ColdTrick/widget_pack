<?php
/**
 * quickly start a discussion
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);
$embed = (bool) elgg_extract('embed', $vars, false);

// check if logged if
$user = elgg_get_logged_in_user_entity();
if (empty($user)) {
	if (!$embed) {
		// you have to be logged in in order to use this widget
		echo elgg_view('output/longtext', [
			'value' => elgg_echo('widgets:start_discussion:login_required'),
		]);
	}
	return;
}

// check if member of a group
/* @var $group_membership \ElggBatch */
$group_membership = $user->getGroups([
	'limit' => false,
	'batch' => true,
]);
if (empty($group_membership->count())) {
	if (!$embed) {
		// you must join a group in order to use this widget
		$url = elgg_generate_url('collection:group:group:all');
		$link_start = "<a href='{$url}'>";
		$link_end = '</a>';
	
		$text = elgg_echo('widgets:start_discussion:membership_required', [$link_start, $link_end]);
		echo elgg_view('output/longtext', [
			'value' => $text,
		]);
	}
	return;
}

$owner = $widget->getOwnerEntity();
$selected_group = ELGG_ENTITIES_ANY_VALUE;
if ($owner instanceof \ElggGroup && $owner->isToolEnabled('forum')) {
	// preselect the current group
	$selected_group = $owner->guid;
}

$group_selection_options = [];
$group_access_options = [];

/* @var $group ElggGroup */
foreach ($group_membership as $group) {
	
	// does the group have discussions disabled
	if (!$group->isToolEnabled('forum')) {
		continue;
	}
	
	$group_acl = $group->getOwnedAccessCollection('group_acl');
	if (empty($group_acl)) {
		continue;
	}
	
	$group_selection_options[$group->guid] = $group->getDisplayName();
	$group_access_options[] = [
		'text' => $group->guid,
		'value' => $group_acl->id,
	];
}

if (empty($group_selection_options)) {
	// non of your groups have discussions enabled
	if (!$embed) {
		echo elgg_view('output/longtext', [
			'value' => elgg_echo('widgets:start_discussion:not_enabled'),
		]);
	}
	return;
}

// sort the groups by name
natcasesort($group_selection_options);

if (empty($selected_group)) {
	// no group container, so add empty record, so a user is required to select a group (instead of defaulting to the first option)
	$group_selection_options = ['' => elgg_echo('widgets:start_discussion:quick_start:group:required')] + $group_selection_options;
	$group_access_options['-1'] = '';
}

$form_vars = [
	'action' => elgg_generate_action_url('discussion/save', [], false),
	'body' => elgg_view('widgets/start_discussion/quick_start', [
		'groups' => $group_selection_options,
		'access' => $group_access_options,
		'container_guid' => $selected_group,
	]),
];

echo elgg_view_form('start_discussion/quick_start', $form_vars);
