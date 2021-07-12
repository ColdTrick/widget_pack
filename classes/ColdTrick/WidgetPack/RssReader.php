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
		
		$proxy_config = elgg_get_config('proxy', []);
		
		// load curl settings
		$curl_options = [
			CURLOPT_SSL_VERIFYPEER => elgg_extract('verify_ssl', $proxy_config, true),
		];
		
		// proxy host
		$proxy_host = elgg_extract('host', $proxy_config);
		if (!empty($proxy_host)) {
			$curl_options[CURLOPT_PROXY] = $proxy_host;
		}
		
		// proxy port
		$proxy_port = elgg_extract('port', $proxy_config);
		if (!empty($proxy_port)) {
			$curl_options[CURLOPT_PROXYPORT] = $proxy_port;
		}
		
		// proxy username/password
		$proxy_username = elgg_extract('username', $proxy_config);
		if (!elgg_is_empty($proxy_username)) {
			// check password
			$proxy_password = elgg_extract('password', $proxy_config);
			if (!elgg_is_empty($proxy_password)) {
				$proxy_username .= ":{$proxy_password}";
			}
			
			$curl_options[CURLOPT_PROXYUSERPWD] = $proxy_username;
		}
		
		self::$reader->set_curl_options($curl_options);
		
		return self::$reader;
	}
}
