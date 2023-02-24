define(['jquery'], function ($) {

	function SlideShow(selector) {
		var SlideShow = this;
		this.$container = $(selector);
		this.totalSlides = this.$container.find('> .slide-fade').length;
		this.currentSlide = 1;
		this.slideTimeout = 5000;
		
		this.$container.on('click', '.slide-next', function() {
			SlideShow.moveNext();
		});
		this.$container.on('click', '.slide-previous', function() {
			SlideShow.movePrevious();
		});
		
		this.timeoutId = setTimeout(function() {
			SlideShow.moveNext();
		}, this.slideTimeout);
	}
	
	SlideShow.prototype = {
		showSlide: function(index) {
			clearTimeout(this.timeoutId);
			
			this.$container.find('> .slide-fade:not(.hidden)').addClass('hidden');
			
			this.$container.find('> .slide-fade').eq(index - 1).removeClass('hidden');
			
			this.timeoutId = setTimeout(this.moveNext.bind(this), this.slideTimeout);
		},
		moveNext: function() {
			if (this.currentSlide >= this.totalSlides) {
				this.currentSlide = 1;
			} else {
				this.currentSlide++;
			}
			
			this.showSlide(this.currentSlide);
		},
		movePrevious: function() {
			if (this.currentSlide <= 1) {
				this.currentSlide = this.totalSlides;
			} else {
				this.currentSlide--;
			}
			
			this.showSlide(this.currentSlide);
		}
	};
	
	return SlideShow;
});
