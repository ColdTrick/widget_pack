<?php

namespace ColdTrick\WidgetPack;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		
		elgg_extend_view('elgg.css', 'widgets/rss/content.css');
		elgg_extend_view('elgg.css', 'widgets/rss_server/content.css');
		
		// image slider
		elgg_define_js('widgets/image_slider/flexslider', [
			'src' => elgg_get_simplecache_url('flexslider/jquery.flexslider-min.js'),
		]);
		elgg_register_simplecache_view('widgets/image_slider/flexslider.css');
	}
	
	/**
	 * Register plugin hook handlers
	 *
	 * @return void
	 */
	protected function registerPluginHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hook->registerHandler('action', 'widgets/save', __NAMESPACE__ . '\Widgets::disableFreeHTMLInputFilter');
		$hook->registerHandler('cacheable_handlers', 'widget_manager', __NAMESPACE__ . '\Widgets::getCacheableWidgets');
		$hook->registerHandler('entity:url', 'object', __NAMESPACE__ . '\Widgets::getTitleURLs');
		$hook->registerHandler('format', 'friendly:time', __NAMESPACE__ . '\Widgets::rssFriendlyTime');
		$hook->registerHandler('search:fields', 'user', 'ColdTrick\WidgetPack\Widgets::userSearchByEmail');
		$hook->registerHandler('view_vars', 'widgets/content_by_tag/display/simple', __NAMESPACE__ . '\Bookmarks::changeEntityURL');
		$hook->registerHandler('view_vars', 'widgets/content_by_tag/display/slim', __NAMESPACE__ . '\Bookmarks::changeEntityURL');
		$hook->registerHandler('widget_settings', 'twitter_search', __NAMESPACE__ . '\Widgets::twitterSearchGetWidgetID');
	}
}
