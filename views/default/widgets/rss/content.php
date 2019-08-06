<?php

$widget = elgg_extract('entity', $vars);

$feed_url = $widget->rssfeed;

$limit = $widget->rss_count ?: 4;

$post_date = ($widget->post_date !== 'no');
$show_feed_title = ($widget->show_feed_title == 'yes');
$excerpt = ($widget->excerpt == 'yes');
$show_item_icon = ($widget->show_item_icon == 'yes');

$show_in_lightbox = false;
if ($widget->show_in_lightbox == 'yes') {
	$show_in_lightbox = true;
}

if (empty($feed_url)) {
	echo elgg_echo('widgets:rss:error:notset');
	return;
}

echo elgg_format_element('div', [
	'id' => 'widget-rss-' . $widget->guid,
	'data-feed-url' => $feed_url,
	'data-limit' => $limit,
	'data-post-date' => $post_date,
	'data-show-feed-title' => $show_feed_title,
	'data-show-excerpt' => $excerpt,
	'data-show-item-icon' => $show_item_icon,
	'data-show-in-lightbox' => $show_in_lightbox,
]);

echo elgg_format_element('script', [], 'require(["widgets/rss/content"], function (rss) { rss("#widget-rss-' . $widget->guid . '"); });');
