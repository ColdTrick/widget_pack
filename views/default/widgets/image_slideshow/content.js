define(['jquery'], function ($) {
	var slideshow = {
		$container: null,
		container: null,
		totalSlides: 1,
		currentSlide: 1,
		timeoutId: null,
		slideTimeout: 5000,
		init: function(selector) {
			slideshow.$container = $(selector);
			slideshow.container = $(selector);
			
			slideshow.$container.on('click', '.slide-next', slideshow.moveNext);	
			slideshow.$container.on('click', '.slide-previous', slideshow.movePrevious);
			
			slideshow.totalSlides = slideshow.$container.find('> .slide-fade').length;
			
			slideshow.timeoutId = setTimeout(slideshow.moveNext, slideshow.slideTimeout);
		},
		moveNext: function() {
			if (slideshow.currentSlide === slideshow.totalSlides) {
				slideshow.currentSlide = 1;
			} else {
				slideshow.currentSlide++;
			}
			
			slideshow.showSlide(slideshow.currentSlide);
		},
		movePrevious: function() {
			if (slideshow.currentSlide === 1) {
				slideshow.currentSlide = slideshow.totalSlides;
			} else {
				slideshow.currentSlide--;
			}
			
			slideshow.showSlide(slideshow.currentSlide);
		},
		showSlide: function(index) {
			clearTimeout(slideshow.timeoutId);
			
			slideshow.$container.find('> .slide-fade:not(.hidden)').addClass('hidden');
			
			slideshow.$container.find('> .slide-fade').eq(index - 1).removeClass('hidden');
			
			slideshow.timeoutId = setTimeout(slideshow.moveNext, slideshow.slideTimeout);
		}
	};
	
	return slideshow;
});
