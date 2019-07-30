<?php

namespace ColdTrick\WidgetPack;

class Bookmarks {
	
	/**
	 * Change the entity URL used in the content_by_tag widget output for bookmarks
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'widgets/content_by_tag/display/[simple|slim]'
	 *
	 * @return void|array
	 */
	public static function changeEntityURL(\Elgg\Hook $hook) {

		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggBookmark) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value['entity_url'] = $entity->address;
		
		return $return_value;
	}
}
