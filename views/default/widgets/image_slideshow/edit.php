<?php

elgg_require_js('widgets/image_slideshow/edit');

$widget = elgg_extract('entity', $vars);
$orientation = $widget->orientation ?: 'landscape';

$slides_config = $widget->slides_config;
if (empty($slides_config)) {
	$slides_config = [
		[
			'image' => 'slider_image_1',
			'text' => '',
			'url' => '',
		],
	];
} elseif (is_string($slides_config)) {
	$slides_config = json_decode($slides_config, true);
}

$icon_sizes = elgg_get_icon_sizes('object', 'widget', 'slider_image_0');
$landscape_config = elgg_extract('landscape', $icon_sizes);
$portrait_config = elgg_extract('portrait', $icon_sizes);

echo elgg_view_field([
	'#type' => 'radio',
	'#label' => elgg_echo('widgets:image_slideshow:orientation'),
	'#help' => elgg_echo('widgets:image_slideshow:orientation:help'),
	'id' => 'slideshow-orientation',
	'name' => 'params[orientation]',
	'align' => 'horizontal',
	'value' => $orientation,
	'data-landscape-aspect-ratio' => (int) elgg_extract('w', $landscape_config) / (int) elgg_extract('h', $landscape_config),
	'data-portrait-aspect-ratio' => (int) elgg_extract('w', $portrait_config) / (int) elgg_extract('h', $portrait_config),
	'options_values' => [
		'landscape' => elgg_echo('widgets:image_slideshow:orientation:landscape'),
		'portrait' => elgg_echo('widgets:image_slideshow:orientation:portrait'),
	],
]);

$menu = elgg_view_menu('slide_menu', [
	'class' => 'elgg-menu-hz',
	'items' => [
		[
			'name' => 'edit',
			'href' => false,
			'text' => elgg_echo('edit'),
		],
		[
			'name' => 'delete',
			'href' => false,
			'icon' => 'delete',
			'text' => false,
			'link_class' => 'mlm',
		]
	]
]);

foreach ($slides_config as $slide_config) {
	$img_name = elgg_extract('image', $slide_config);
	$slide = elgg_view('entity/edit/icon', [
		'entity' => $widget,
		'name' => $img_name,
		'icon_type' => $img_name,
		'show_remove' => false,
		'cropper_enabled' => true,
		'cropper_aspect_ratio_size' => $orientation,
	]);
	
	$slide .= elgg_view_field([
		'#type' => 'text',
		'#label' => elgg_echo('widgets:image_slideshow:caption'),
		'name' => 'slides[slider_text][]',
		'value' => elgg_extract('text', $slide_config),
	]);
	
	$slide .= elgg_view_field([
		'#type' => 'text',
		'#label' => elgg_echo('widgets:image_slideshow:url'),
		'name' => 'slides[slider_url][]',
		'value' => elgg_extract('url', $slide_config),
	]);
	
	echo elgg_view_module('info', elgg_view_icon('arrows-alt', ['class' => 'link']) . ' ' . elgg_echo('widgets:image_slideshow:slide'), $slide, ['menu' => $menu]);
}

$slide_template = elgg_view('entity/edit/icon', [
	'entity' => $widget,
	'name' => 'slider_image_template',
	'icon_type' => 'slider_image_0', // fake
	'show_remove' => false,
	'cropper_enabled' => true,
	'cropper_aspect_ratio_size' => $orientation,
]);

$slide_template .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widgets:image_slideshow:caption'),
	'name' => 'slides[slider_text][]',
]);

$slide_template .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widgets:image_slideshow:url'),
	'name' => 'slides[slider_url][]',
]);

echo elgg_view_module('info', elgg_view_icon('arrows-alt', ['class' => 'link']) . ' ' . elgg_echo('widgets:image_slideshow:slide'), $slide_template, ['id' => 'slide-template', 'class' => 'hidden', 'menu' => $menu]);

echo elgg_view_field([
	'#type' => 'button',
	'id' => 'slide-template-add',
	'icon' => 'plus',
	'class' => ['elgg-button', 'elgg-button-action'],
	'text' => elgg_echo('widgets:image_slideshow:add_slide'),
]);

// keep track of new image index
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'params[new_slide_index]',
	'value' => $widget->new_slide_index ?: 2,
]);

$init_js = '$(".elgg-form-widgets-save-image-slideshow .elgg-content").sortable({ items: ".elgg-module-info", handle: ".elgg-head > h3 > .elgg-icon-arrows-alt", containment: "parent" });';
echo elgg_format_element('script', [], 'require(["jquery", "jquery-ui/widgets/sortable"], function($) { ' . $init_js . ' });');
