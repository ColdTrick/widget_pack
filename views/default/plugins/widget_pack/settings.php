<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_pack:settings:disable_free_html_filter'),
	'name' => 'params[disable_free_html_filter]',
	'checked' => $plugin->disable_free_html_filter === 'yes',
	'default' => 'no',
	'value' => 'yes',
	'switch' => true,
]);

// Rss server settings
$rss_server = elgg_view('output/longtext', [
	'value' => elgg_echo('widget_pack:settings:rss:description'),
]);
$rss_server .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widget_pack:settings:rss:proxy_host'),
	'name' => 'params[rss_proxy_host]',
	'value' => $plugin->rss_proxy_host,
]);

$rss_server .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widget_pack:settings:rss:proxy_port'),
	'name' => 'params[rss_proxy_port]',
	'value' => $plugin->rss_proxy_port,
	'min' => 0,
	'max' => 65535,
]);
$rss_server .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widget_pack:settings:rss:proxy_username'),
	'name' => 'params[rss_proxy_username]',
	'value' => $plugin->rss_proxy_username,
]);
$rss_server .= elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('widget_pack:settings:rss:proxy_password'),
	'name' => 'params[rss_proxy_password]',
	'value' => $plugin->rss_proxy_password,
	'always_empty' => false,
]);
$rss_server .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_pack:settings:rss:verify_ssl'),
	'#help' => elgg_echo('widget_pack:settings:rss:verify_ssl:help'),
	'name' => 'params[rss_verify_ssl]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->rss_verify_ssl === 'yes',
	'switch' => true,
]);
$rss_server .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_pack:settings:rss:cron'),
	'#help' => elgg_echo('widget_pack:settings:rss:cron:help'),
	'name' => 'params[rss_cron]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->rss_cron === 'yes',
	'switch' => true,
]);

echo elgg_view_module('info', elgg_echo('widget_pack:settings:rss:title'), $rss_server);
