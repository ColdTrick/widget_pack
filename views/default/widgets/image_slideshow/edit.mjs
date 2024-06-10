import 'jquery';
import Cropper from 'entity/edit/icon/crop';

$(document).on('click', '#slide-template-add', function() {
	$(this).closest('.elgg-tabs-content').find('.elgg-module-info:not("#slide-template") > .elgg-body').addClass('hidden');
	
	var $template = $('#slide-template');
	var $newslide = $template.clone().insertBefore($template);

	var $slide_index_input = $(this).closest('form').find('input[name="params[new_slide_index]"]');
	var new_index = parseInt($slide_index_input.val());
	
	$slide_index_input.val(new_index + 1);
	
	var new_slide_name = 'slider_image_' + new_index;
	
	$newslide.removeAttr('id').removeClass('hidden');
	$newslide.find('input[name="slider_image_template"').attr('name', new_slide_name);
	$newslide.find('input[name="slider_image_template_x1"').attr('name', new_slide_name + '_x1');
	$newslide.find('input[name="slider_image_template_x2"').attr('name', new_slide_name + '_x2');
	$newslide.find('input[name="slider_image_template_y1"').attr('name', new_slide_name + '_y1');
	$newslide.find('input[name="slider_image_template_y2"').attr('name', new_slide_name + '_y2');
	
	var cropper = new Cropper();
	
	cropper.init('input[type="file"][name="' + new_slide_name + '"]');
});

$(document).on('click', '.elgg-menu-slide-menu .elgg-menu-item-edit', function() {
	$(this).closest('.elgg-module-info').find('> .elgg-body').toggleClass('hidden');
});

$(document).on('click', '.elgg-menu-slide-menu .elgg-menu-item-delete', function() {
	$(this).closest('.elgg-module-info').remove();
});

$(document).on('change', '#slideshow-orientation', function(event) {
	var aspect_ratio = $(event.target).data($(event.target).val() + 'AspectRatio');
	$(this).closest('form').find('img[data-icon-cropper]').each(function() {
		var config = $(this).data('iconCropper');
		config.aspectRatio = aspect_ratio;
		config.initialAspectRatio = aspect_ratio;
		
		// update config of all (including the template)
		$(this).attr('data-icon-cropper', JSON.stringify(config));

		// update active croppers
		$(this).cropper('setAspectRatio', aspect_ratio);
	});
});
