<?php

$widget = elgg_extract('entity', $vars);

$rss_count = sanitise_int($widget->rss_count, false);
if (empty($rss_count)) {
	$rss_count = 4;
}

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

$noyes_options = array_reverse($yesno_options);

echo elgg_view_input('text', [
	'name' => 'params[rssfeed]',
	'label' => elgg_echo('widgets:rss:settings:rssfeed'),
	'value' => $widget->rssfeed,
]);

echo elgg_view_input('select', [
	'name' => 'params[rss_count]',
	'label' => elgg_echo('widgets:rss:settings:rss_count'),
	'value' => $rss_count,
	'options' => range(1,10),
]);

echo elgg_view_input('select', [
	'name' => 'params[excerpt]',
	'label' => elgg_echo('widgets:rss:settings:excerpt'),
	'value' => $widget->excerpt,
	'options_values' => $yesno_options,
]);

echo elgg_view_input('select', [
	'name' => 'params[show_item_icon]',
	'label' => elgg_echo('widgets:rss:settings:show_item_icon'),
	'value' => $widget->show_item_icon,
	'options_values' => $noyes_options,
]);

echo elgg_view_input('select', [
	'name' => 'params[post_date]',
	'label' => elgg_echo('widgets:rss:settings:post_date'),
	'value' => $widget->post_date,
	'options_values' => $yesno_options,
]);

echo elgg_view_input('select', [
	'name' => 'params[show_in_lightbox]',
	'label' => elgg_echo('widgets:rss:settings:show_in_lightbox'),
	'value' => $widget->show_in_lightbox,
	'options_values' => $noyes_options,
]);
	