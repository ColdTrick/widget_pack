<?php

namespace ColdTrick\WidgetPack;

class RssReader {
	
	/**
	 * @var \SimplePie
	 */
	protected static $reader;
	
	/**
	 * Get a serverside RSS reader
	 *
	 * @return \SimplePie
	 */
	public static function getReader() {
		
		if (isset(self::$reader)) {
			return self::$reader;
		}
		
		self::$reader = new \SimplePie();
		
		// load curl settings
		$curl_options = [];
		
		// proxy host
		$proxy_host = elgg_get_plugin_setting('rss_proxy_host', 'widget_pack');
		if (!empty($proxy_host)) {
			$curl_options[CURLOPT_PROXY] = $proxy_host;
		}
		
		// proxy port
		$proxy_port = (int) elgg_get_plugin_setting('rss_proxy_port', 'widget_pack');
		if (!empty($proxy_port)) {
			$curl_options[CURLOPT_PROXYPORT] = $proxy_port;
		}
		
		// proxy username/password
		$proxy_username = elgg_get_plugin_setting('rss_proxy_username', 'widget_pack');
		if (!elgg_is_empty($proxy_username)) {
			// check password
			$proxy_password = elgg_get_plugin_setting('rss_proxy_password', 'widget_pack');
			if (!elgg_is_empty($proxy_password)) {
				$proxy_username .= ":{$proxy_password}";
			}
			
			$curl_options[CURLOPT_PROXYUSERPWD] = $proxy_username;
		}
		
		// disable ssl verification
		if (elgg_get_plugin_setting('rss_verify_ssl', 'widget_pack') === 'no') {
			$curl_options[CURLOPT_SSL_VERIFYPEER] = false;
		}
		
		// did we have additional settings
		if (!empty($curl_options)) {
			self::$reader->set_curl_options($curl_options);
		}
		
		return self::$reader;
	}
}
