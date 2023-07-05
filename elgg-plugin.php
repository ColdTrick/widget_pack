<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'plugin' => [
		'version' => '6.1',
		'dependencies' => [
			'widget_manager' => [],
		],
	],
	'settings' => [
		'disable_free_html_filter' => 'no',
		'rss_cron' => 'no',
	],
	'view_extensions' => [
		'elgg.css' => [
			'widgets/rss_server/content.css' => [],
		],
	],
	'events' => [
		'action:validate' => [
			'widgets/save' => [
				'ColdTrick\WidgetPack\Widgets::disableFreeHTMLInputFilter' => [],
				'ColdTrick\WidgetPack\Widgets::twitterSearchGetWidgetID' => [],
			],
		],
		'all' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getSlideshowIconSizes' => [],
			],
		],
		'cacheable_handlers' => [
			'widget_manager' => [
				'ColdTrick\WidgetPack\Widgets::getCacheableWidgets' => [],
			],
		],
		'cron' => [
			'fiveminute' => [
				'ColdTrick\WidgetPack\Cron::fetchRssServerWidgets' => [],
			],
		],
		'entity:url' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getTitleURLs' => [],
			],
		],
		'format' => [
			'friendly:time' => [
				'ColdTrick\WidgetPack\Widgets::rssFriendlyTime' => [],
			],
		],
		'response' => [
			'action:widgets/save' => [
				'ColdTrick\WidgetPack\Widgets::saveSlideshowConfig' => [],
			],
		],
		'search:fields' => [
			'user' => [
				'ColdTrick\WidgetPack\Widgets::userSearchByEmail' => [],
			],
		],
		'update:after' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::rssServerInvalidateCache' => [],
			],
		],
		'view_vars' => [
			'widgets/content_by_tag/display/simple' => [
				'ColdTrick\WidgetPack\Bookmarks::changeEntityURL' => [],
			],
			'widgets/content_by_tag/display/slim' => [
				'ColdTrick\WidgetPack\Bookmarks::changeEntityURL' => [],
			],
		],
	],
	'widgets' => [
		'content_by_tag' => [
			'context' => ['profile', 'dashboard', 'index', 'groups'],
			'multiple' => true,
		],
		'entity_statistics' => [
			'context' => ['index'],
		],
		'free_html' => [
			'context' => ['profile', 'dashboard', 'index', 'groups'],
			'multiple' => true,
		],
		'iframe' => [
			'context' => ['profile', 'dashboard', 'index', 'groups'],
			'multiple' => true,
		],
		'image_slideshow' => [
			'context' => ['index', 'groups'],
			'multiple' => true,
		],
		'index_activity' => [
			'context' => ['index'],
			'multiple' => true,
		],
		'index_login' => [
			'context' => ['index'],
		],
		'index_members' => [
			'context' => ['index'],
			'multiple' => true,
		],
		'index_members_online' => [
			'context' => ['index'],
			'multiple' => true,
		],
		'messages' => [
			'context' => ['dashboard', 'index'],
			'required_plugin' => ['messages'],
		],
		'register' => [
			'context' => ['index'],
		],
		'rss_server' => [
			'context' => ['index'],
			'multiple' => true,
		],
		'start_discussion' => [
			'context' => ['index', 'dashboard', 'groups'],
		],
		'twitter_search' => [
			'context' => ['profile', 'dashboard', 'index', 'groups'],
			'multiple' => true,
		],
		'user_search' => [
			'context' => ['admin'],
		],
	],
];
