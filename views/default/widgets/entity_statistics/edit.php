<?php

$widget = elgg_extract('entity', $vars);

$entity_stats = get_entity_statistics();

$options_values = [];
foreach ($entity_stats as $k => $entry) {
	arsort($entry);
	foreach ($entry as $a => $b) {
		$key = $k . "|" . $a;
		
		if ($a == '__base__') {
			$a = elgg_echo("item:{$k}");
			if (empty($a)) {
				$a = $k;
			}
		} else {
			if (empty($a)) {
				$a = elgg_echo("item:{$k}");
			} else {
				$a = elgg_echo("item:{$k}:{$a}");
			}

			if (empty($a)) {
				$a = "$k $a";
			}
		}
		
		$options_values[$a] = $key;
	}
}

echo elgg_view_input('checkboxes', [
	'name' => 'params[selected_entities]',
	'label' => elgg_echo('widgets:entity_statistics:settings:selected_entities'),
	'options' => $options_values,
	'value' => $widget->selected_entities,
]);
