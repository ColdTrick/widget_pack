<?php

use ColdTrick\WidgetPack\ContentByTag;

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$content_type = $widget->content_type;

$content_options_values = [];
foreach (ContentByTag::getSupportedContent() as $plugin => $subtype) {
	$content_options_values[$subtype] = elgg_echo("item:object:{$subtype}");
}

if (empty($content_type) && !empty($content_options_values)) {
	$keys = array_keys($content_options_values);
	$content_type = $keys[0];
}

echo elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('widgets:content_by_tag:owner_guids'),
	'#help' => elgg_echo('widgets:content_by_tag:owner_guids:description'),
	'name' => 'params[owner_guids]',
	'values' => $widget->owner_guids,
	'only_friends' => false,
]);

if ($widget->context == 'groups') {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('widgets:content_by_tag:group_only'),
		'name' => 'params[group_only]',
		'checked' => $widget->group_only !== 'no',
		'default' => 'no',
		'value' => 'yes',
		'switch' => true,
	]);
} elseif(elgg_view_exists('input/grouppicker')) {
	$container_guids = elgg_echo('widgets:content_by_tag:container_guids') . '<br />';
	$container_guids .= elgg_view('input/hidden', ['name' => 'params[container_guids]', 'value' => 0]);
	$container_guids .= elgg_view('input/grouppicker', ['name' => 'params[container_guids]', 'values' => $widget->container_guids]);
	$container_guids .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widgets:content_by_tag:container_guids:description'));
	echo elgg_format_element('div', [], $container_guids);
}

echo elgg_view('object/widget/edit/num_display', [
	'name' => 'content_count',
	'entity' => $widget,
	'default' => 8,
]);

echo elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('widgets:content_by_tag:entities'),
	'name' => 'params[content_type]',
	'value' => $content_type,
	'options' => array_flip($content_options_values),
]);

echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('widgets:content_by_tag:tags'),
	'name' => 'params[tags]',
	'value' => $widget->tags,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:content_by_tag:tags_option'),
	'name' => 'params[tags_option]',
	'value' => $widget->tags_option ?: 'and',
	'options_values' => [
		'and' => elgg_echo('widgets:content_by_tag:tags_option:and'),
		'or' => elgg_echo('widgets:content_by_tag:tags_option:or'),
	],
]);

echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('widgets:content_by_tag:excluded_tags'),
	'name' => 'params[excluded_tags]',
	'value' => $widget->excluded_tags,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:content_by_tag:show_search_link'),
	'#help' => elgg_echo('widgets:content_by_tag:show_search_link:disclaimer'),
	'name' => 'params[show_search_link]',
	'checked' => $widget->show_search_link === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:content_by_tag:display_option'),
	'name' => 'params[display_option]',
	'value' => $widget->display_option,
	'options_values' => [
		'normal' => elgg_echo('widgets:content_by_tag:display_option:normal'),
		'simple' => elgg_echo('widgets:content_by_tag:display_option:simple'),
		'slim' => elgg_echo('widgets:content_by_tag:display_option:slim'),
	],
]);

$display_options = '';

$content = elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:content_by_tag:show_avatar'),
	'name' => 'params[show_avatar]',
	'checked' => $widget->show_avatar !== 'no',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

$display_options .= elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options-simple'], $content);

$content = elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widgets:content_by_tag:show_timestamp'),
	'name' => 'params[show_timestamp]',
	'checked' => $widget->show_timestamp !== 'no',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

$display_options .= elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options-simple widgets-content-by-tag-display-options-slim'], $content);

echo elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options'], $display_options);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:content_by_tag:order_by'),
	'name' => 'params[order_by]',
	'value' => $widget->order_by,
	'options_values' => [
		'time_created' => elgg_echo('widgets:content_by_tag:order_by:time_created'),
		'alpha' => elgg_echo('widgets:content_by_tag:order_by:alpha'),
	],
]);

echo elgg_format_element('script', [], 'require(["widgets/content_by_tag/edit"], function() { init(); })');
