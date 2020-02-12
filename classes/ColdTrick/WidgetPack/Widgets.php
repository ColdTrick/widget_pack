<?php

namespace ColdTrick\WidgetPack;

use Elgg\Hook;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Widgets {
		
	/**
	 * Returns an array of cacheable widget handlers
	 *
	 * @param \Elgg\Hook $hook 'cacheable_handlers', 'widget_manager'
	 *
	 * @return bool
	 */
	public static function getCacheableWidgets(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		if (!is_array($return_value)) {
			return $return_value;
		}
	
		$return_value[] = 'iframe';
		$return_value[] = 'free_html';
		$return_value[] = 'twitter_search';
	
		return $return_value;
	}

	/**
	 * Function that unregisters html validation for admins to be able to save freehtml widgets with special html
	 *
	 * @param \Elgg\Hook $hook 'action:validate', 'widgets/save'
	 *
	 * @return void
	 */
	public static function disableFreeHTMLInputFilter(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (elgg_get_plugin_setting('disable_free_html_filter', 'widget_pack') == 'no') {
			return;
		}
		
		$widget = get_entity(get_input('guid'));
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if ($widget->handler !== 'free_html') {
			return;
		}
			
		$advanced_context = elgg_trigger_plugin_hook('advanced_context', 'widget_manager', ['entity' => $widget], ['index']);
		if (is_array($advanced_context) && in_array($widget->context, $advanced_context)) {
			elgg_unregister_plugin_hook_handler('validate', 'input', '_elgg_htmlawed_filter_tags');
		}
	}
	
	/**
	 * Returns a rss widget specific date_time notation
	 *
	 * @param \Elgg\Hook $hook 'format', 'friendly:time'
	 *
	 * @return string
	 */
	public static function rssFriendlyTime(\Elgg\Hook $hook) {
		if (empty($hook->getParam('time'))) {
			return;
		}
	
		if (!elgg_in_context('rss_date')) {
			return;
		}
	
		$date_info = getdate($hook->getParam('time'));
	
		$date_array = [
			elgg_echo('date:weekday:' . $date_info['wday']),
			elgg_echo('date:month:' . str_pad($date_info['mon'], 2, '0', STR_PAD_LEFT), [$date_info['mday']]),
			$date_info['year'],
		];
	
		return implode(' ', $date_array);
	}

	/**
	 * Returns urls for widget titles
	 *
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return string
	 */
	public static function getTitleURLs(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		if ($return) {
			// someone else provided already a result
			return;
		}
		
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			// not a widget
			return;
		}
		
		switch($widget->handler) {
			case 'index_activity':
				return elgg_generate_url('default:river');
			case 'messages':
				$user = elgg_get_logged_in_user_entity();
				if ($user) {
					return elgg_generate_url('collection:object:messages:owner', [
						'username' => $user->username,
					]);
				}
			case 'index_members_online':
			case 'index_members':
				return elgg_generate_url('collection:user:user');
			case 'friends_of':
				$owner = $widget->getOwnerEntity();
				return elgg_generate_url('collection:friends_of:owner', ['username' => $owner->username]);
		}
	}
	
	/**
	 * Strips data-widget-id from submitted script code and saves that
	 *
	 * @param \Elgg\Hook $hook 'widget_settings', 'twitter_search'
	 *
	 * @return void
	 */
	public static function twitterSearchGetWidgetID(\Elgg\Hook $hook) {
		
		$widget = $hook->getParam('widget');
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		// get embed code
		$embed_code = elgg_extract('embed_code', get_input('params', [], false)); // do not strip code
	
		if (empty($embed_code)) {
			return;
		}
	
		$data_found = false;
		
		// look for data-widget-id in embed code
		$pattern = '/data-widget-id=\"(\d+)\"/i';
		$matches = [];
		if (preg_match($pattern, $embed_code, $matches)) {
			$widget->widget_id = $matches[1];
			$data_found = true;
		}
		
		$pattern = '/href=["\']?([^"\'>]+)["\']?/i';
		$matches = [];
		if (preg_match($pattern, $embed_code, $matches)) {
			$widget->embed_href = $matches[1];
			$data_found = true;
		}
		
		if (!$data_found) {
			register_error(elgg_echo('widgets:twitter_search:embed_code:error'));
		}
	}
	
	/**
	 * Expands the allowable searchable fields for the user search widget
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return array
	 */
	public static function userSearchByEmail(\Elgg\Hook $hook) {
		if ($hook->getParam('widget') !== 'user_search') {
			return;
		}
		
		$value = (array) $hook->getValue();
		
		$defaults = [
			'metadata' => [],
		];
		
		$value = array_merge($defaults, $value);
		
		$value['metadata'][] = 'email';
		
		return $value;
	}
	
	/**
	 * Invalidate cached data from the rss feed
	 *
	 * @param \Elgg\Hook $hook Hook 'widget_settings', 'rss_server'
	 *
	 * @return void
	 */
	public static function rssServerInvalidateCache(\Elgg\Hook $hook) {
		
		$widget = $hook->getParam('widget');
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'rss_server') {
			return;
		}
		
		elgg_delete_system_cache("rss_cache_{$widget->guid}");
	}
	
	/**
	 * Saves images uploaded by the image_slider widget
	 *
	 * @param \Elgg\Hook $hook Hook 'widget_settings', 'image_slider'
	 *
	 * @return void
	 */
	public static function saveImageSliderImages(\Elgg\Hook $hook) {
		$widget = $hook->getParam('widget');
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'image_slider') {
			return;
		}
		
		$files = _elgg_services()->request->files->all();
		foreach ($files as $name => $file) {
			if (!$file instanceof UploadedFile || !$file->isValid()) {
				continue;
			}
			
			if (stristr($name, 'slider_image_') === false) {
				continue;
			}
			
			$widget->saveIconFromUploadedFile($name, $name);
		}
		
		foreach (_elgg_services()->request->getParams(false) as $name => $value) {
			if ((stristr($name, 'slider_image_') === false) || empty($value)) {
				continue;
			}
						
			$icon_name = str_ireplace('_remove', '', $name);
			$widget->deleteIcon($icon_name);
		}
	}
}
