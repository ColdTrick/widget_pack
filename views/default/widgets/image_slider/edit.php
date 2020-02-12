<?php

$widget = elgg_extract('entity', $vars);

$max_slider_options = (int) elgg_extract('max_slider_options', $vars, 5);

for ($i = 1; $i <= $max_slider_options; $i++) {
	$slider_settings = '';
	
	$img_name = "slider_image_{$i}";
	
	if ($widget->hasIcon('master', $img_name)) {
		$slider_settings .= elgg_view('output/img', [
			'src' => $widget->getIconURL([
				'size' => 'master',
				'type' => $img_name,
			]),
			'alt' => $img_name,
		]);
		
		$slider_settings .= elgg_view_field([
			'#type' => 'checkbox',
			'#label' => elgg_echo('entity:edit:icon:remove:label'),
			'name' => "{$img_name}_remove",
			'value' => 1,
			'switch' => true,
		]);
	} else {
		$slider_settings .= elgg_view_field([
			'#type' => 'url',
			'#label' => elgg_echo('widgets:image_slider:label:url'),
			'name' => 'params[slider_' . $i . '_url]',
			'value' => $widget->{'slider_' . $i . '_url'},
		]);
			
		$slider_settings .= elgg_view_field([
			'#type' => 'file',
			'#label' => elgg_echo('widgets:image_slider:label:file'),
			'name' => $img_name,
		]);
	}

	$slider_settings .= elgg_format_element('hr');
	
	$slider_settings .= elgg_view_field([
		'#type' => 'text',
		'#label' => elgg_echo('widgets:image_slider:label:text'),
		'name' => 'params[slider_' . $i . '_text]',
		'value' => $widget->{'slider_' . $i . '_text'},
	]);

	$slider_settings .= elgg_view_field([
		'#type' => 'url',
		'#label' => elgg_echo('widgets:image_slider:label:link'),
		'name' => 'params[slider_' . $i . '_link]',
		'value' => $widget->{'slider_' . $i . '_link'},
	]);
	
	$id = $widget->guid . '-slide-' . $i;
	echo elgg_view_module('info', elgg_echo('widgets:image_slider:title') . ' - ' . $i, $slider_settings, [
		'menu' => elgg_view('output/url', [
			'href' => false,
			'rel' => 'toggle',
			'text' => elgg_echo('show'),
			'data-toggle-selector' => "#{$id} .elgg-body",
		]),
		
		'class' => 'image-slider-module',
		'id' => $id,
	]);
}
