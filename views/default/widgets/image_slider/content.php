<?php

// https://github.com/woothemes/FlexSlider/wiki/FlexSlider-Properties

global $IMAGE_SLIDER_CSS_LOADED;

$widget = elgg_extract('entity', $vars);

$max_slider_options = (int) elgg_extract('max_slider_options', $vars, 5);

$object_id = 'slider_' . $widget->getGUID();

$configured_slides = [];
for ($i = 1; $i <= $max_slider_options; $i++) {
	$url = $widget->{'slider_' . $i . '_url'};
	if (!empty($url)) {
		
		$text = $widget->{'slider_' . $i . '_text'};
		$link = $widget->{'slider_' . $i . '_link'};
	
		$configured_slides[] = [
			'url' => $url,
			'text' => $text,
			'link' => $link,
		];
	}
}

if (empty($configured_slides)) {
	$configured_slides = [
		[
			'url' => 'http://flexslider.woothemes.com/images/kitchen_adventurer_donut.jpg',
			'text' => '<strong>Lorem ipsum dolor</strong><br>Consectetuer adipiscing elit. Donec eu massa vitae arcu laoreet aliquet.',
			'link' => false,
		], [
			'url' => 'http://flexslider.woothemes.com/images/kitchen_adventurer_caramel.jpg',
			'text' => '<strong>Praesent</strong><br>Maecenas est erat, aliquam a, ornare eu, pretium nec, pede.',
			'link' => false,
		], [
			'url' => 'http://flexslider.woothemes.com/images/kitchen_adventurer_cheesecake_brownie.jpg',
			'text' => '<strong>In hac habitasse</strong><br>Quisque ipsum est, fermentum quis, sodales nec, consectetuer sed, quam. Nulla feugiat lacinia odio.',
			'link' => false,
		], [
			'url' => 'http://flexslider.woothemes.com/images/kitchen_adventurer_lemon.jpg',
			'text' => '<strong>Fusce rhoncus</strong><br>Praesent pellentesque nibh sed nibh. Sed ac libero. Etiam quis libero.',
			'link' => false,
		],
	];
}

$slides_list_items = '';
foreach ($configured_slides as $slide) {
	
	$list_item = elgg_view('output/img', [
		'src' => $slide['url'],
		'class' => 'slider_img',
		'alt' => 'slide image',
	]);
	
	if (!empty($slide['text'])) {
		$list_item .= elgg_format_element('div', ['class' => 'flex-caption'], $slide['text']);
	}
	
	if (!empty($slide['link'])) {
		$list_item = elgg_view('output/url', ['href' => $slide['link'], 'text' => $list_item]);
	}
	
	$slides_list_items .= elgg_format_element('li', [], $list_item);
}
$slider = elgg_format_element('ul', ['class' => 'slides'], $slides_list_items);
$slider = elgg_format_element('div', ['class' => 'flexslider'], $slider);
echo elgg_format_element('div', ['id' => $object_id], $slider);

if (!isset($IMAGE_SLIDER_CSS_LOADED)) {
	echo elgg_format_element('link', [
		'rel' => 'stylesheet',
		'type' => 'text/css',
		'href' => elgg_get_simplecache_url('widgets/image_slider/flexslider.css'),
	]);
	$IMAGE_SLIDER_CSS_LOADED = true;
}
?>
<script type='text/javascript'>
	require(['jquery', 'widgets/image_slider/flexslider'], function($, flex) {
    	$('#<?php echo $object_id; ?> .flexslider').flexslider({
    		slideshow: true,
    		pauseOnHover: true,
			animation: 'slide',
			controlNav: false,
			smoothHeight: true,
			prevText: '',
    		nextText: ''
		});
	});
</script>