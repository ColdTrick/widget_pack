<?php

namespace ColdTrick\WidgetPack;

use Elgg\Http\OkResponse;
use Elgg\Http\ErrorResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Widgets
 */
class Widgets {
		
	/**
	 * Returns an array of cacheable widget handlers
	 *
	 * @param \Elgg\Event $event 'cacheable_handlers', 'widget_manager'
	 *
	 * @return array
	 */
	public static function getCacheableWidgets(\Elgg\Event $event) {
		$return_value = $event->getValue();
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
	 * @param \Elgg\Event $event 'action:validate', 'widgets/save'
	 *
	 * @return void
	 */
	public static function disableFreeHTMLInputFilter(\Elgg\Event $event): void {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (elgg_get_plugin_setting('disable_free_html_filter', 'widget_pack') === 'no') {
			return;
		}
		
		$widget = get_entity((int) get_input('guid'));
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if ($widget->handler !== 'free_html') {
			return;
		}
			
		$advanced_context = elgg_trigger_event_results('advanced_context', 'widget_manager', ['entity' => $widget], ['index']);
		if (is_array($advanced_context) && in_array($widget->context, $advanced_context)) {
			elgg_unregister_event_handler('sanitize', 'input', \Elgg\Input\ValidateInputHandler::class);
		}
	}
	
	/**
	 * Returns a rss widget specific date_time notation
	 *
	 * @param \Elgg\Event $event 'format', 'friendly:time'
	 *
	 * @return null|string
	 */
	public static function rssFriendlyTime(\Elgg\Event $event): ?string {
		if (empty($event->getParam('time'))) {
			return null;
		}
	
		if (!elgg_in_context('rss_date')) {
			return null;
		}
	
		$date_info = getdate($event->getParam('time'));
	
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
	 * @param \Elgg\Event $event 'entity:url', 'object'
	 *
	 * @return null|string
	 */
	public static function getTitleURLs(\Elgg\Event $event): ?string {
		$return = $event->getValue();
		if ($return) {
			// someone else provided already a result
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			// not a widget
			return null;
		}
		
		switch ($widget->handler) {
			case 'index_activity':
				return elgg_generate_url('default:river');
			case 'messages':
				$user = elgg_get_logged_in_user_entity();
				if ($user) {
					return elgg_generate_url('collection:object:messages:owner', [
						'username' => $user->username,
					]);
				}
				break;
			case 'index_members_online':
			case 'index_members':
				return elgg_generate_url('collection:user:user');
			case 'start_discussion':
				$owner = $widget->getOwnerEntity();
				if ($owner instanceof \ElggGroup) {
					return elgg_generate_url('add:object:discussion', [
						'guid' => $owner->guid,
					]);
				}
				break;
		}
		
		return null;
	}
	
	/**
	 * Strips data-widget-id from submitted script code and saves that
	 *
	 * @param \Elgg\Event $event 'action:validate', 'widgets/save'
	 *
	 * @return void
	 */
	public static function twitterSearchGetWidgetID(\Elgg\Event $event): void {
		
		$widget = get_entity((int) get_input('guid'));
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'twitter_search') {
			return;
		}
		
		$embed_code = get_input('embed_code', [], false); // do not strip code
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
			elgg_register_error_message(elgg_echo('widgets:twitter_search:embed_code:error'));
		}
	}
	
	/**
	 * Expands the allowable searchable fields for the user search widget
	 *
	 * @param \Elgg\Event $event 'search:fields', 'user'
	 *
	 * @return null|array
	 */
	public static function userSearchByEmail(\Elgg\Event $event): ?array {
		if ($event->getParam('widget') !== 'user_search') {
			return null;
		}
		
		$value = (array) $event->getValue();
		
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
	 * @param \Elgg\Event $event 'update:after', 'object'
	 *
	 * @return void
	 */
	public static function rssServerInvalidateCache(\Elgg\Event $event): void {
		
		$widget = $event->getObject();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'rss_server') {
			return;
		}
		
		elgg_delete_system_cache("rss_cache_{$widget->guid}");
	}
	
	/**
	 * Return image_slideshow widget icon sizes
	 *
	 * @param \Elgg\Event $event 'entity:{$type}:sizes', 'object'
	 *
	 * @return null|array
	 */
	public static function getSlideshowIconSizes(\Elgg\Event $event): ?array {
		if (!preg_match('/^entity:slider_image_[\d]+:sizes$/', $event->getName())) {
			return null;
		}
		
		$result = $event->getValue();
		
		$result['landscape'] = [
			'w' => 800,
			'h' => 450,
			'square' => false,
			'upscale' => true,
			'crop' => true,
		];
		
		$result['portrait'] = [
			'w' => 800,
			'h' => 1060,
			'square' => false,
			'upscale' => true,
			'crop' => true,
		];
		
		return $result;
	}
	
	/**
	 * Saves the slideshow config
	 *
	 * @param \Elgg\Event $event 'response', 'action:widgets/save'
	 *
	 * @return null|OkResponse
	 */
	public static function saveSlideshowConfig(\Elgg\Event $event): ?OkResponse {
		
		$result = $event->getValue();
		if ($result instanceof ErrorResponse) {
			return null;
		}
		
		$widget = get_entity((int) get_input('guid'));
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
		
		if ($widget->handler !== 'image_slideshow') {
			return null;
		}
		
		$old_config = $widget->slides_config ?: [];
		if (is_string($old_config)) {
			$old_config = json_decode($old_config, true);
		}
		
		$slides_input = get_input('slides');
		$files = _elgg_services()->request->files;
		
		$slide_texts = elgg_extract('slider_text', $slides_input);
		$slide_urls = elgg_extract('slider_url', $slides_input);
		
		$slides_config = [];
		$valid_images = [];
		$index = -1; // keep track of the index to get slide config
		foreach ($files as $image_input_name => $slide_image) {
			$index++;
			if (!$widget->hasIcon('master', $image_input_name) && (!$slide_image instanceof UploadedFile || !$slide_image->isValid())) {
				// no existing icon and invalid upload
				continue;
			}
			
			if ($slide_image instanceof UploadedFile && $slide_image->isValid()) {
				$widget->saveIconFromUploadedFile($image_input_name, $image_input_name);
			}
			
			$valid_images[] = $image_input_name;
			
			$slides_config[] = [
				'image' => $image_input_name,
				'text' => elgg_extract($index, $slide_texts),
				'url' => elgg_extract($index, $slide_urls),
			];
		}
		
		$widget->slides_config = json_encode($slides_config);
		
		foreach ($old_config as $old_slide) {
			$image = elgg_extract('image', $old_slide);
			if (!in_array($image, $valid_images)) {
				$widget->deleteIcon($image);
			}
		}
		
		$content = $result->getContent();
		$content['content'] = elgg_view('object/widget/elements/content', ['entity' => $widget]);
		
		$result->setContent($content);
		
		return $result;
	}
}
