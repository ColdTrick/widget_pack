<?php
use Elgg\Database\QueryBuilder;
use Elgg\Values;
				
// Get entity statistics
$entity_stats = elgg_get_entity_statistics();
$selected_entities = (array) $vars['entity']->selected_entities;

if (empty($entity_stats)) {
	return;
}

$result = '';
foreach ($entity_stats as $k => $entry) {
	arsort($entry);
	foreach ($entry as $a => $b) {
		$key = $k . '|' . $a;
		if (!empty($selected_entities) && !in_array($key, $selected_entities)) {
			continue;
		}
		
		if (elgg_language_key_exists("collection:{$k}:{$a}")) {
			$text = elgg_echo("collection:{$k}:{$a}");
		} elseif (elgg_language_key_exists("item:{$k}:{$a}")) {
			$text = elgg_echo("item:{$k}:{$a}");
		} else {
			$text = "{$k} {$a}";
		}
		
		$result .= "<tr><td>{$text}:</td><td>{$b}</td></tr>";
	}
}

echo elgg_format_element('table', ['class' => 'elgg-table'], $result);
