<?php

namespace ColdTrick\WidgetPack;

/**
 * Content By Tag widget helpers
 */
class ContentByTag {
	
	/**
	 * Returns the supported object subtypes to be used in the content_by_tag widget
	 *
	 * @return array
	 */
	public static function getSupportedContent(): array {
		$result = [
			'blog' => 'blog',
			'file' => 'file',
			'pages' => 'page',
			'bookmarks' => 'bookmarks',
			'thewire' => 'thewire',
			'event_manager' => 'event',
			'discussions' => 'discussion',
			'poll' => 'poll',
			'questions' => 'question',
			'static' => 'static',
		];
		
		foreach ($result as $plugin => $subtype) {
			if (elgg_is_active_plugin($plugin)) {
				continue;
			}
			
			unset($result[$plugin]);
		}
		
		return (array) elgg_trigger_event_results('supported_content', 'widgets:content_by_tag', $result, $result);
	}
}
