<?php

namespace ColdTrick\WidgetPack;

class Bookmarks {
	
	/**
	 * Change the entity URL used in the content_by_tag widget output for bookmarks
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function changeEntityURL($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $return_value);
		if (!elgg_instanceof($entity, 'object', 'bookmarks')) {
			return;
		}
		
		$return_value['entity_url'] = $entity->address;
		
		return $return_value;
	}
}
