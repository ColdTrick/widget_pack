<?php

namespace ColdTrick\WidgetPack;

/**
 * Bookmarks helpers
 */
class Bookmarks {
	
	/**
	 * Change the entity URL used in the content_by_tag widget output for bookmarks
	 *
	 * @param \Elgg\Event $event 'view_vars', 'widgets/content_by_tag/display/[simple|slim]'
	 *
	 * @return void|array
	 */
	public static function changeEntityURL(\Elgg\Event $event) {

		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggBookmark) {
			return;
		}
		
		$return_value = $event->getValue();
		$return_value['entity_url'] = $entity->address;
		
		return $return_value;
	}
}
