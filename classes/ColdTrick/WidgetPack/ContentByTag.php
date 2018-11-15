<?php

namespace ColdTrick\WidgetPack;

class ContentByTag {
	
	/**
	 * Returns the supported object subtypes to be used in the content_by_tag widget
	 *
	 * @return array
	 */
	public static function getSupportedContent() {
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
			if (elgg_is_active_plugin($plugin)) {
				continue;
			}
			
			unset($result[$plugin]);
		}
		
		return elgg_trigger_plugin_hook('supported_content', 'widgets:content_by_tag', $result, $result);
	}
}
