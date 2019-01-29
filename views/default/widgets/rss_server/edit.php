<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widgets:rss:settings:rssfeed'),
	'name' => 'params[rssfeed]',
	'value' => $widget->rssfeed,
]);

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'rss_count',
	'label' => elgg_echo('widgets:rss:settings:rss_count'),
	'entity' => $widget,
	'default' => 4,
	'min' => 1,
	'max' => 50,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widgets:rss:settings:rss_cachetimeout'),
	'name' => 'params[rss_cachetimeout]',
	'value' => $widget->rss_cachetimeout,
	'min' => 0,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss:settings:show_feed_title'),
	'name' => 'params[show_feed_title]',
	'checked' => $widget->show_feed_title === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss_server:settings:show_source'),
	'name' => 'params[show_source]',
	'checked' => $widget->show_source === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss_server:settings:show_author'),
	'name' => 'params[show_author]',
	'checked' => $widget->show_author === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss:settings:excerpt'),
	'name' => 'params[excerpt]',
	'checked' => $widget->excerpt === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss:settings:show_item_icon'),
	'name' => 'params[show_item_icon]',
	'checked' => $widget->show_item_icon === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss:settings:post_date'),
	'name' => 'params[post_date]',
	'checked' => $widget->post_date !== 'no',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:rss:settings:show_in_lightbox'),
	'name' => 'params[show_in_lightbox]',
	'checked' => $widget->show_in_lightbox === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);
