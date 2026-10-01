<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_pack:settings:rss:cron'),
	'#help' => elgg_echo('widget_pack:settings:rss:cron:help'),
	'name' => 'params[rss_cron]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->rss_cron === 'yes',
	'switch' => true,
]);
