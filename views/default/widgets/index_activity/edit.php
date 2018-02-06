<?php

$widget = elgg_extract('entity', $vars);

$contents = [];

$registered_entities = elgg_get_config('registered_entities');

if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $ar) {
		if (count($registered_entities[$type])) {
			foreach ($registered_entities[$type] as $subtype) {
				$keyname = 'item:' . $type . ':' . $subtype;
				$contents[elgg_echo($keyname)] = "{$type},{$subtype}";
			}
		} else {
			$keyname = 'item:' . $type;
			$contents[elgg_echo($keyname)] = "{$type},";
		}
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
