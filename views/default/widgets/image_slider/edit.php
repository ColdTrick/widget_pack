<?php

$widget = elgg_extract('entity', $vars);

$max_slider_options = (int) elgg_extract('max_slider_options', $vars, 5);

for ($i = 1; $i <= $max_slider_options; $i++) {
	$slider_settings = elgg_view_input('url', [
		'name' => 'params[slider_' . $i . '_url]',
		'label' => elgg_echo('widgets:image_slider:label:url'),
		'value' => $widget->{'slider_' . $i . '_url'},
	]);

	$slider_settings .= elgg_view_input('text', [
		'name' => 'params[slider_' . $i . '_text]',
		'label' => elgg_echo('widgets:image_slider:label:text'),
		'value' => $widget->{'slider_' . $i . '_text'},
	]);

	$slider_settings .= elgg_view_input('url', [
		'name' => 'params[slider_' . $i . '_link]',
		'label' => elgg_echo('widgets:image_slider:label:link'),
		'value' => $widget->{'slider_' . $i . '_link'},
	]);
	
	echo elgg_view_module('info', elgg_echo('widgets:image_slider:title') . ' - ' . $i, $slider_settings);
}
