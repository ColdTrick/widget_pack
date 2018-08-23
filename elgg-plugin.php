<?php


$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'settings' => [
		'disable_free_html_filter' => 'no',
	],
	'views' => [
		'default' => [
			'flexslider/' => $composer_path . 'vendor/bower-asset/flexslider-customized',
			'widgets/image_slider/fonts/' => $composer_path . 'vendor/bower-asset/flexslider-customized/fonts',
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
		'friends_of' => [
			'context' => ['profile', 'dashboard'],
			'required_plugin' => ['friends'],
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
			'name' => elgg_echo('activity'),
			'context' => ['index'],
			'multiple' => true,
		],
		'index_login' => [
			'name' => elgg_echo('login'),
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
// 		'likes' => [
// 			'context' => ['index', 'groups', 'profile', 'dashboard'],
// 			'multiple' => true,
// 		],
		'register' => [
			'context' => ['index'],
		],
		'rss' => [
			'context' => ['profile', 'dashboard', 'index', 'groups'],
			'multiple' => true,
		],
		'rss_server' => [
			'context' => ['index'],
			'multiple' => true,
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
