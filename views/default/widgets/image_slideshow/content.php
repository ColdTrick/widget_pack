<?php

/**
 * @var \ElggWidget
 */
$widget = elgg_extract('entity', $vars);

$orientation = $widget->orientation ?: 'landscape';

echo elgg_format_element('link', ['rel' => 'stylesheet', 'href' => elgg_get_simplecache_url('widgets/image_slideshow/content.css')]);

if ($widget->canEdit()) {
	echo elgg_format_element('link', ['rel' => 'stylesheet', 'href' => elgg_get_simplecache_url('cropperjs/cropper.css')]);
}

$config = $widget->slides_config;
if (empty($config)) {
	echo elgg_echo('widgets:not_configured');
	return;
}

if (is_string($config)) {
	$config = json_decode($config, true);
}

$slides = '';
foreach ($config as $index => $slide) {
	$image = elgg_extract('image', $slide);
	$content = elgg_view('output/img', [
		'src' => $widget->getIconUrl(['type' => $image, 'size' => $orientation]),
	]);
	
	$text = elgg_extract('text', $slide);
	if (!empty($text)) {
		$content .= elgg_format_element('span', ['class' => 'slide-text'], $text);
	}
	
	$url = elgg_extract('url', $slide);
	
	if (!empty($url)) {
		$content = elgg_view('output/url', [
			'href' =>  $url,
			'text' => $content,
		]);
	}
	
	$classes = ['slide-fade'];
	if ($index > 0) {
		$classes[] = 'hidden';
	}
	
	$slides .= elgg_format_element('div', ['class' => $classes], $content);
}

$slides .= elgg_format_element('div', ['class' => 'slide-next'], elgg_view_icon('angle-right'));
$slides .= elgg_format_element('div', ['class' => 'slide-previous'], elgg_view_icon('angle-left'));

$id = "slideshow_{$widget->guid}";

echo elgg_format_element('div', ['id' => $id, 'class' => 'widget-slideshow-container'], $slides);
?>
<script>
require(['widgets/image_slideshow/content'], function(SlideShow) {
	new SlideShow('#<?php echo $id; ?>');
});
</script>