<?php

@include_once(dirname(__FILE__) . '/vendor/autoload.php');

// register default Elgg events
elgg_register_event_handler('init', 'system', 'widget_pack_init');

/**
 * Used to perform initialization of the widgets
 *
 * @return void
 */
function widget_pack_init() {
	
	elgg_register_plugin_hook_handler('action', 'widgets/save', '\ColdTrick\WidgetPack\Widgets::disableFreeHTMLInputFilter');
	elgg_register_plugin_hook_handler('cacheable_handlers', 'widget_manager', '\ColdTrick\WidgetPack\Widgets::getCacheableWidgets');
	elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\WidgetPack\Widgets::getTitleURLs');
	elgg_register_plugin_hook_handler('format', 'friendly:time', '\ColdTrick\WidgetPack\Widgets::rssFriendlyTime');
	elgg_register_plugin_hook_handler('view_vars', 'widgets/content_by_tag/display/simple', '\ColdTrick\WidgetPack\Bookmarks::changeEntityURL');
	elgg_register_plugin_hook_handler('view_vars', 'widgets/content_by_tag/display/slim', '\ColdTrick\WidgetPack\Bookmarks::changeEntityURL');
	elgg_register_plugin_hook_handler('widget_settings', 'rss_server', '\ColdTrick\WidgetPack\Widgets::rssServerFlushCacheOnSave');
	elgg_register_plugin_hook_handler('widget_settings', 'twitter_search', '\ColdTrick\WidgetPack\Widgets::twitterSearchGetWidgetID');
	
	elgg_extend_view('css/elgg', 'widgets/rss/content.css');
	elgg_extend_view('css/elgg', 'widgets/rss_server/content.css');
	
	elgg_register_event_handler('cache:flush', 'system', '\ColdTrick\WidgetPack\Widgets::rssServerFlushAllCache');
	
	// image slider
	elgg_define_js('widgets/image_slider/flexslider', [
		'src' => elgg_get_simplecache_url('flexslider/jquery.flexslider-min.js'),
	]);
	elgg_register_simplecache_view('widgets/image_slider/flexslider.css');
	
	// messages
	if (elgg_is_active_plugin('messages')) {
		elgg_register_widget_type([
			'id' => 'messages',
			'name' => elgg_echo('messages'),
			'context' => ['dashboard', 'index'],
		]);
	}
}

/**
 * Returns the supported object subtypes to be used in the content_by_tag widget
 *
 * @return array
 */
function widget_pack_content_by_tag_get_supported_content() {
	$result = [
		'blog' => 'blog',
		'file' => 'file',
		'pages' => 'page',
		'bookmarks' => 'bookmarks',
		'thewire' => 'thewire',
		'videolist' => 'videolist_item',
		'event_manager' => 'event',
		'tasks' => 'task_top',
		'discussions' => 'discussion',
		'poll' => 'poll',
		'questions' => 'question',
		'static' => 'static',
	];
	
	foreach ($result as $plugin => $subtype) {
		if (!elgg_is_active_plugin($plugin)) {
			unset($result[$plugin]);
		}
	}
	
	return elgg_trigger_plugin_hook('supported_content', 'widgets:content_by_tag', $result, $result);
}
