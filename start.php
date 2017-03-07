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
		
	// cacheable widget handlers
	elgg_register_plugin_hook_handler('cacheable_handlers', 'widget_manager', '\ColdTrick\WidgetPack\Widgets::getCacheableWidgets');
	elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\WidgetPack\Widgets::getTitleURLs');
	
	// content_by_tag
	foreach (widget_pack_content_by_tag_get_supported_content() as $plugin => $subtype) {
		if (elgg_is_active_plugin($plugin)) {
			elgg_register_widget_type([
				'id' => 'content_by_tag',
				'context' => ['profile', 'dashboard', 'index', 'groups'],
				'multiple' => true,
			]);
			break;
		}
	}
	
	elgg_register_plugin_hook_handler('view_vars', 'widgets/content_by_tag/display/slim', '\ColdTrick\WidgetPack\Bookmarks::changeEntityURL');
	elgg_register_plugin_hook_handler('view_vars', 'widgets/content_by_tag/display/simple', '\ColdTrick\WidgetPack\Bookmarks::changeEntityURL');
	
	// entity_statistics
	elgg_register_widget_type([
		'id' => 'entity_statistics',
		'context' => ['index'],
	]);
	
	// free_html
	elgg_register_widget_type([
		'id' => 'free_html',
		'context' => ['profile', 'dashboard', 'index', 'groups'],
		'multiple' => true,
	]);
	elgg_register_plugin_hook_handler('action', 'widgets/save', '\ColdTrick\WidgetPack\Widgets::disableFreeHTMLInputFilter');
	
	// index_login
	elgg_register_widget_type([
		'id' => 'index_login',
		'name' => elgg_echo('login'),
		'context' => ['index'],
	]);
	
	// likes
// 	elgg_register_widget_type([
// 		'id' => 'likes',
// 		'context' => ['index', 'groups', 'profile', 'dashboard'],
// 		'multiple' => true,
// 	]);
	
	// iframe
	elgg_register_widget_type([
		'id' => 'iframe',
		'context' => ['profile', 'dashboard', 'index', 'groups'],
		'multiple' => true,
	]);
	
	// user_search
	elgg_register_widget_type([
		'id' => 'user_search',
		'context' => ['admin'],
	]);

	// rss widget
	elgg_register_widget_type([
		'id' => 'rss',
		'context' => ['profile', 'dashboard', 'index', 'groups'],
		'multiple' => true,
	]);
	elgg_register_widget_type([
		'id' => 'rss_server',
		'context' => ['index'],
		'multiple' => true,
	]);
	elgg_register_plugin_hook_handler('widget_settings', 'rss_server', '\ColdTrick\WidgetPack\Widgets::rssServerFlushCacheOnSave');
	elgg_register_plugin_hook_handler('format', 'friendly:time', '\ColdTrick\WidgetPack\Widgets::rssFriendlyTime');
	elgg_extend_view('css/elgg', 'widgets/rss/content.css');
	elgg_extend_view('css/elgg', 'widgets/rss_server/content.css');
	
	// image slider
	elgg_define_js('widgets/image_slider/flexslider', ['src' => elgg_get_simplecache_url('flexslider/jquery.flexslider-min.js')]);
	
	elgg_register_simplecache_view('widgets/image_slider/flexslider.css');
	elgg_register_widget_type([
		'id' => 'image_slider',
		'context' => ['index', 'groups'],
		'multiple' => true,
	]);
	
	// index activity
	elgg_register_widget_type([
		'id' => 'index_activity',
		'name' => elgg_echo('activity'),
		'context' => ['index'],
		'multiple' => true,
	]);
	
	// twitter_search
	elgg_register_widget_type([
		'id' => 'twitter_search',
		'context' => ['profile', 'dashboard', 'index', 'groups'],
		'multiple' => true,
	]);
	elgg_register_plugin_hook_handler('widget_settings', 'twitter_search', '\ColdTrick\WidgetPack\Widgets::twitterSearchGetWidgetID');
	
	// messages
	if (elgg_is_active_plugin('messages')) {
		elgg_register_widget_type([
			'id' => 'messages',
			'name' => elgg_echo('messages'),
			'context' => ['dashboard', 'index'],
		]);
	}
	
	// index_members_online
	elgg_register_widget_type([
		'id' => 'index_members_online',
		'context' => ['index'],
		'multiple' => true,
	]);

	// index_members
	elgg_register_widget_type([
		'id' => 'index_members',
		'context' => ['index'],
		'multiple' => true,
	]);
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
	
	return elgg_trigger_plugin_hook('supported_content', 'widgets:content_by_tag', $result, $result);
}
