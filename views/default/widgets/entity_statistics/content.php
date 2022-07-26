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

		if ($a == '__base__') {
			$a = elgg_echo("item:{$k}");
			if ($k == 'user') {
				$count = elgg_count_entities([
					'type' => 'user',
					'wheres' => [
						function(QueryBuilder $qb, $main_alias) {
							return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
						}
					],
				]);
				
				$b .= ' (' . elgg_echo('admin:users:online') . ' ' . $count  . ')';
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