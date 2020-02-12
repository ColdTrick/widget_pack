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
		
		$this->registerPluginHooks();
	}
	
	/**
	 * Register plugin hook handlers
	 *
	 * @return void
	 */
	protected function registerPluginHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('action:validate', 'widgets/save', __NAMESPACE__ . '\Widgets::disableFreeHTMLInputFilter');
		$hooks->registerHandler('cacheable_handlers', 'widget_manager', __NAMESPACE__ . '\Widgets::getCacheableWidgets');
		$hooks->registerHandler('cron', 'fiveminute', __NAMESPACE__ . '\Cron::fetchRssServerWidgets');
		$hooks->registerHandler('entity:url', 'object', __NAMESPACE__ . '\Widgets::getTitleURLs');
		$hooks->registerHandler('format', 'friendly:time', __NAMESPACE__ . '\Widgets::rssFriendlyTime');
		$hooks->registerHandler('search:fields', 'user', __NAMESPACE__ . '\Widgets::userSearchByEmail');
		$hooks->registerHandler('view_vars', 'widgets/content_by_tag/display/simple', __NAMESPACE__ . '\Bookmarks::changeEntityURL');
		$hooks->registerHandler('view_vars', 'widgets/content_by_tag/display/slim', __NAMESPACE__ . '\Bookmarks::changeEntityURL');
		$hooks->registerHandler('widget_settings', 'image_slider', __NAMESPACE__ . '\Widgets::saveImageSliderImages');
		$hooks->registerHandler('widget_settings', 'rss_server', __NAMESPACE__ . '\Widgets::rssServerInvalidateCache');
		$hooks->registerHandler('widget_settings', 'twitter_search', __NAMESPACE__ . '\Widgets::twitterSearchGetWidgetID');
	}
}
