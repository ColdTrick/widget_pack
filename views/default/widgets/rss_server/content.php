<?php

$widget = elgg_extract('entity', $vars);

$cache_location = elgg_get_cache_path() . 'simplepie';
if (!file_exists($cache_location)) {
	mkdir($cache_location, 0755, true);
}

// check feed url
$feed_url = $widget->rssfeed;
if (empty($feed_url)) {
	echo elgg_echo('widgets:rss:error:notset');
	return;
}

$rss_cachetimeout = sanitise_int($widget->rss_cachetimeout, false) ?: 3600;

// check local cached data
$cache_key = "rss_cache_{$widget->guid}";
$feed_data = elgg_load_system_cache($cache_key);

$cache_ts = elgg_extract('cache_ts', $feed_data, 0);
if ($cache_ts < (time() - $rss_cachetimeout)) {
	$feed_data = false;
}

// did we have cached data
if (empty($feed_data)) {
	$limit = (int) $widget->rss_count;
	if ($limit < 1) {
		$limit = 4;
	}
	
	// read the rss from the source
	$feed = new SimplePie();
	$feed->set_feed_url($feed_url);
	$feed->set_cache_location($cache_location);
	$feed->set_cache_duration($rss_cachetimeout);
	
	$feed->init();
	
	$feed_data = [
		'title_text' => $feed->get_title(),
		'title_href' => $feed->get_permalink(),
		'items' => [],
	];
		
	foreach ($feed->get_items(0, $limit) as $index => $item) {
		$feed_item = [
			'title' => $item->get_title(),
			'href' => $item->get_permalink(),
			'icon' => '',
			'author' => '',
			'content' => $item->get_content(),
			'excerpt' => $item->get_description(),
			'timestamp' => $item->get_date('U'),
		];
		
		$enclosures = $item->get_enclosures();
		if (!empty($enclosures)) {
			foreach ($enclosures as $enclosure) {
				if (strpos($enclosure->type, 'image/') !== false) {
					$feed_item['icon_url'] = $enclosure->link;
					break;
				}
			}
		}
		
		$authors = $item->get_authors();
		if (!empty($authors)) {
			$author = $authors[0]->name;
			if (empty($author)) {
				$author = $authors[0]->link;
			}
			if (empty($author)) {
				$author = $authors[0]->email;
			}
			
			if (!empty($author)) {
				$feed_item['author'] = $author;
			}
		}
		
		$feed_data['items'][] = $feed_item;
	}
	
	// write to cache
	$feed_data['cache_ts'] = time();
	elgg_save_system_cache($cache_key, $feed_data);
	
	// cleanup
	unset($feed);
}

if (empty($feed_data) || empty($feed_data['items'])) {
	// something went wrong
	echo elgg_echo('widgets:rss:error:notset');
	return;
}

// get widget settings
$post_date = ($widget->post_date !== 'no');
$show_feed_title = ($widget->show_feed_title == 'yes');
$excerpt = ($widget->excerpt == 'yes');
$show_item_icon = ($widget->show_item_icon == 'yes');

$show_in_lightbox = false;
if ($widget->show_in_lightbox == 'yes') {
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');

	$show_in_lightbox = true;
}

$show_author = ($widget->show_author == 'yes');

// proccess data
if ($show_feed_title) {
	$feed_title = elgg_extract('title_text', $feed_data);
	$feed_url= elgg_extract('title_href', $feed_data);
	
	if (!empty($feed_title) && !empty($feed_url)) {
		echo elgg_format_element('h3', [], elgg_view('output/url', [
			'text' => $feed_title,
			'href' => $feed_url,
			'target' => '_blank',
		]));
	}
}

// proccess items
echo '<ul class="widget-rss-server-result elgg-list">';

foreach ($feed_data['items'] as $index => $item) {
	echo '<li class="elgg-item">';
	
	$title_text = elgg_extract('title', $item);
	$href = elgg_extract('href', $item);
	
	$title = '';
	$content = '';
	$icon = '';
	
	if ($show_item_icon) {
		$icon_url = elgg_extract('icon_url', $item);
		if (!empty($icon_url)) {
			$icon = elgg_view('output/url', [
				'text' => elgg_view('output/img', [
					'src' => $icon_url,
					'alt' => $title_text,
					'class' => 'widgets-rss-server-feed-item-image',
				]),
				'href' => $href,
				'target' => '_blank',
			]);
		}
	}
	
	if ($show_author) {
		$author = elgg_extract('author', $item);
		if (!empty($author)) {
			$title_text .= ' (' . $author . ')';
		}
	}
	
	if ($show_in_lightbox) {
		$id = 'widget-rss-server-' . $widget->getGUID() . '-item-' . $index;
		
		$title = elgg_view('output/url', [
			'text' => $title_text,
			'href' => 'javascript:return void(0);',
			'class' => 'elgg-lightbox',
			'data-colorbox-opts' => json_encode([
				'inline' => true,
				'href' => "#{$id}",
				'innerWidth' => 600,
			]),
		]);
		
		// lightbox title
		$module_title = elgg_view('output/url', [
			'text' => $title_text,
			'href' => $href,
			'target' => '_blank',
		]);
		
		// lightbox content
		$module_content = elgg_extract('content', $item);
		$module_content .= elgg_view('output/url', [
			'text' => elgg_echo('widget_pack:readmore'),
			'href' => $href,
			'target' => '_blank',
			'class' => 'mls',
		]);
		
		$module_text = $icon;
		$module_text .= elgg_view('output/longtext', [
			'value' => $module_content,
		]);
		
		// lightbox
		$module = elgg_view_module('rss-popup', $module_title, $module_text, [
			'id' => $id,
			'class' => 'elgg-module-info',
		]);
		
		$content .= elgg_format_element('div', ['class' => 'hidden'], $module);
	} else {
		$title = elgg_view('output/url', [
			'text' => $title_text,
			'href' => $href,
			'target' => '_blank',
		]);
	}
	
	if ($excerpt) {
		$content .= elgg_format_element('div', [
			'class' => 'elgg-content',
		], $icon . elgg_view('output/longtext', [
				'value' => elgg_extract('excerpt', $item),
			])
		);
	}
	
	if ($post_date) {
		elgg_push_context('rss_date');
		$time = elgg_view_friendly_time(elgg_extract('timestamp', $item));
		elgg_pop_context();
		
		$content .= elgg_format_element('div', ['class' => 'elgg-subtext'], $time);
	}
	
	echo $title;
	echo $content;
	
	echo '</li>';
}

echo '</ul>';
