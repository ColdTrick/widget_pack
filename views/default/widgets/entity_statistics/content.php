<?php
// Get entity statistics
$entity_stats = get_entity_statistics();
$selected_entities = (array) $vars['entity']->selected_entities;

if (empty($entity_stats)) {
	return;
}

$result = '';
foreach ($entity_stats as $k => $entry) {
	arsort($entry);
	foreach ($entry as $a => $b) {
		$key = $k . "|" . $a;
		if (!empty($selected_entities) && !in_array($key, $selected_entities)) {
			continue;
		}

		if ($a == '__base__') {
			$a = elgg_echo("item:{$k}");
			if ($k == 'user') {
				$b .= " (" . elgg_echo("admin:users:online") . " " . find_active_users(["seconds" => 600, "count" => true])  . ")";
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
		$result .= "<tr><td>{$a}:</td><td>{$b}</td></tr>";
	}
}
echo elgg_format_element('table', ['class' => 'elgg-table'], $result);