<?php

$widget = elgg_extract('entity', $vars);

$entity_stats = elgg_get_entity_statistics();

$options_values = [];
foreach ($entity_stats as $k => $entry) {
	arsort($entry);
	foreach ($entry as $a => $b) {
		$key = $k . '|' . $a;
		
		if (elgg_language_key_exists("collection:{$k}:{$a}")) {
			$text = elgg_echo("collection:{$k}:{$a}");
		} elseif (elgg_language_key_exists("item:{$k}:{$a}")) {
			$text = elgg_echo("item:{$k}:{$a}");
		} else {
			$text = "{$k} {$a}";
		}
		
		$options_values[$key] = $text;
	}
}

echo elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('widgets:entity_statistics:settings:selected_entities'),
	'name' => 'params[selected_entities]',
	'options_values' => $options_values,
	'value' => $widget->selected_entities,
]);
