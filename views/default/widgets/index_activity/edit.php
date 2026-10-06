<?php

$widget = elgg_extract('entity', $vars);

$contents = [];

$registered_entities = elgg_entity_types_with_capability('river_emittable');

foreach ($registered_entities as $type => $subtypes) {
	foreach ($subtypes as $subtype) {
		$keyname = "collection:{$type}:{$subtype}";
		if (!elgg_language_key_exists($keyname)) {
			$keyname = "item:{$type}:{$subtype}";
		}
		
		$contents[elgg_echo($keyname)] = "{$type},{$subtype}";
	}
}

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'activity_count',
	'entity' => $widget,
	'default' => 10,
]);

echo elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('filter'),
	'name' => 'params[activity_content]',
	'value' => $widget->activity_content,
	'options' => $contents,
]);
