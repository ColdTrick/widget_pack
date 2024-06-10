<?php

namespace ColdTrick\WidgetPack;

/**
 * Cron jobs
 */
class Cron {
	
	/**
	 * Fetch RSS server widget feeds to reduce performance impact on users
	 *
	 * @param \Elgg\Event $event 'cron', 'fiveminute'
	 *
	 * @return void
	 */
	public static function fetchRssServerWidgets(\Elgg\Event $event): void {
		
		if (elgg_get_plugin_setting('rss_cron', 'widget_pack') !== 'yes') {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			// all rss calls could take a while
			set_time_limit(0);
			
			// prepare cache location
			$cache_location = elgg_get_cache_path() . 'simplepie';
			if (!is_dir($cache_location)) {
				mkdir($cache_location, 0755, true);
			}
			
			$widgets = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'widget',
				'limit' => false,
				'batch' => true,
				'metadata_name_value_pairs' => [
					[
						'name' => 'handler',
						'value' => 'rss_server',
					],
					[
						'name' => 'rssfeed',
						'value' => '',
						'operand' => '!=',
					],
				],
			]);
			
			/* @var $widget \ElggWidget */
			foreach ($widgets as $widget) {
				$timeout = (int) $widget->rss_cachetimeout;
				if ($timeout < 1) {
					$timeout = 3600;
				}
				
				// special timeout settings to make sure cron is before user
				$rss_cachetimeout = $timeout - 1799;
				if ($rss_cachetimeout < 1800) {
					$rss_cachetimeout = 1799;
				}
				
				// read the rss from the source
				$feed = RssReader::getReader();
				$feed->set_feed_url($widget->rssfeed);
				$feed->set_cache_location($cache_location);
				$feed->set_cache_duration($rss_cachetimeout);
				
				$feed->init();
			}
		});
	}
}
