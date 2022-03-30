<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'plugin' => [
		'version' => '3.1',
		'dependencies' => [
			'widget_manager' => [],
		],
	],
	'settings' => [
		'disable_free_html_filter' => 'no',
		'rss_cron' => 'no',
	],
	'views' => [
		'default' => [
			'flexslider/' => $composer_path . 'vendor/bower-asset/flexslider-customized',
			'widgets/image_slider/fonts/' => $composer_path . 'vendor/bower-asset/flexslider-customized/fonts',
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'widgets/rss_server/content.css' => [],
		],
	],
	'view_options' => [
		'widgets/image_slider/flexslider.css' => ['simplecache' => true],
	],
	'hooks' => [
		'action:validate' => [
			'widgets/save' =>[
				'ColdTrick\WidgetPack\Widgets::disableFreeHTMLInputFilter' => [],
			],
		],
		'cacheable_handlers' => [
			'widget_manager' =>[
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
		'entity:slider_image_1:sizes' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getImageSliderIconSizes' => [],
			],
		],
		'entity:slider_image_2:sizes' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getImageSliderIconSizes' => [],
			],
		],
		'entity:slider_image_3:sizes' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getImageSliderIconSizes' => [],
			],
		],
		'entity:slider_image_4:sizes' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getImageSliderIconSizes' => [],
			],
		],
		'entity:slider_image_5:sizes' => [
			'object' => [
				'ColdTrick\WidgetPack\Widgets::getImageSliderIconSizes' => [],
			],
		],
		'format' => [
			'friendly:time' => [
				'ColdTrick\WidgetPack\Widgets::rssFriendlyTime' => [],
			],
		],
		'search:fields' => [
			'user' => [
				'ColdTrick\WidgetPack\Widgets::userSearchByEmail' => [],
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
		'widget_settings' => [
			'image_slider' => [
				'ColdTrick\WidgetPack\Widgets::saveImageSliderImages' => [],
			],
			'rss_server' => [
				'ColdTrick\WidgetPack\Widgets::rssServerInvalidateCache' => [],
			],
			'twitter_search' => [
				'ColdTrick\WidgetPack\Widgets::twitterSearchGetWidgetID' => [],
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
		'image_slider' => [
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
