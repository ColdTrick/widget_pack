<?php

$widget = elgg_extract('entity', $vars);

if (empty($widget)) {
	$widget = get_entity((int) get_input('guid'));
	if (!$widget instanceof \ElggWidget) {
		return;
	}
}

elgg_require_js('widgets/user_search/content');

$q = get_input('q');

echo elgg_view('input/form', [
	'body' => elgg_view_field([
		'#type' => 'text',
		'name' => 'q',
		'placeholder' => elgg_echo('search'),
		'value' => $q,
	]) . elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $widget->guid,
	]),
	'class' => 'widget-user-search-form',
	'action' => false,
]);

if (empty($q)) {
	return;
}

$entities = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($q) {
	return elgg_search([
		'type' => 'user',
		'fields' => [
			'metadata' => ['username', 'email', 'name'],
		],
		'partial_match' => true,
		'query' => $q,
		'widget' => 'user_search', // used to get info into event params to determine if email is searchable
	]);
});

if (empty($entities)) {
	echo elgg_echo('notfound');
	return;
}

$result = [];
foreach ($entities as $entity) {
	$entity_data = [];
	
	$entity_data[] = elgg_view_entity_url($entity);
	
	$entity_data[] = $entity->username;
	$entity_data[] = $entity->email;
	
	if ($entity->isValidated()) {
		$entity_data[] = elgg_echo('option:yes');
	} else {
		$entity_data[] = elgg_echo('option:no');
	}
	
	$entity_data[] = elgg_echo('option:' . $entity->enabled);
					
	$entity_data[] = htmlspecialchars(date(elgg_echo('friendlytime:date_format'), $entity->time_created));
	
	$result[] = '<td>' . implode('</td><td>', $entity_data) . '</td>';
}

echo '<table class="elgg-table mtm"><thead><tr>';
echo '<th>' . elgg_echo('name') . '</th>';
echo '<th>' . elgg_echo('username') . '</th>';
echo '<th>' . elgg_echo('email') . '</th>';
echo '<th>' . elgg_echo('widgets:user_search:validated') . '</th>';
echo '<th>' . elgg_echo('status:enabled') . '</th>';
echo '<th>' . elgg_echo('table_columns:fromView:time_created') . '</th>';
echo '</tr></thead><tbody><tr>';
echo implode('</tr><tr>', $result);
echo '</tr></tbody></table>';
