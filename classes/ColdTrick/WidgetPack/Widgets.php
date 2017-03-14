<?php

namespace ColdTrick\WidgetPack;

class Widgets {
		
	/**
	 * Returns an array of cacheable widget handlers
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return bool
	 */
	public static function getCacheableWidgets($hook_name, $entity_type, $return_value, $params) {
	
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
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function disableFreeHTMLInputFilter($hook_name, $entity_type, $return_value, $params) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (elgg_get_plugin_setting('disable_free_html_filter', 'widget_pack') !== 'yes') {
			return;
		}
		
		$guid = get_input('guid');
		$widget = get_entity($guid);
	
		if (!($widget instanceof \ElggWidget)) {
			return;
		}
		
		if ($widget->handler !== 'free_html') {
			return;
		}
			
		$advanced_context = elgg_trigger_plugin_hook('advanced_context', 'widget_manager', ['entity' => $widget], ['index']);
		if (is_array($advanced_context) && in_array($widget->context, $advanced_context)) {
			elgg_unregister_plugin_hook_handler('validate', 'input', 'htmlawed_filter_tags');
		}
	}
	
	/**
	 * Returns a rss widget specific date_time notation
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return string
	 */
	public static function rssFriendlyTime($hook_name, $entity_type, $return_value, $params) {
		if (empty($params['time'])) {
			return;
		}
	
		if (!elgg_in_context('rss_date')) {
			return;
		}
	
		$date_info = getdate($params['time']);
	
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
	 * @param string $hook   name of the hook
	 * @param string $type   type of the hook
	 * @param string $return current return value
	 * @param array  $params hook parameters
	 *
	 * @return string
	 */
	public static function getTitleURLs($hook, $type, $return, $params) {
		if ($return) {
			// someone else provided already a result
			return;
		}
		
		$widget = elgg_extract('entity', $params);
		if (!($widget instanceof \ElggWidget)) {
			// not a widget
			return;
		}
		
		switch($widget->handler) {
			case 'index_activity':
				return '/activity';
				break;
			case 'messages':
				$user = elgg_get_logged_in_user_entity();
				if ($user) {
					return '/messages/inbox/' . $user->username;
				}
				break;
			case 'index_members_online':
			case 'index_members':
				if (elgg_is_active_plugin('members')) {
					return '/members';
				}
				break;
		}
	}
	
	/**
	 * Strips data-widget-id from submitted script code and saves that
	 *
	 * @param string $hook   name of the hook
	 * @param string $type   type of the hook
	 * @param string $return current return value
	 * @param array  $params hook parameters
	 *
	 * @return void
	 */
	public static function twitterSearchGetWidgetID($hook, $type, $return, $params) {
		
		if ($type !== 'twitter_search') {
			return;
		}
		
		$widget = elgg_extract('widget', $params);
		if (!elgg_instanceof($widget, 'object', 'widget')) {
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
	 * Listen to the widget settings save of the RSS server widget
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function rssServerFlushCacheOnSave($hook_name, $entity_type, $return_value, $params) {
	
		$widget = elgg_extract('widget', $params);
		if (!elgg_instanceof($widget, 'object', 'widget')) {
			return;
		}
	
		if ($widget->handler != 'rss_server') {
			return;
		}
	
		$cache_file = elgg_get_config('dataroot') . 'widgets/rss/' . $widget->getGUID() . '.json';
		if (file_exists($cache_file)) {
			unlink($cache_file);
		}
	}
}