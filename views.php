<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'default' => [
		'flexslider/' => $composer_path . 'vendor/bower-asset/flexslider-customized',
		'widgets/image_slider/fonts/' => $composer_path . 'vendor/bower-asset/flexslider-customized/fonts',

	],
];
