;(function ($, window, undefined) {
  'use strict';

  var $doc = $(document),
      Modernizr = window.Modernizr;

  $(document).ready(function() {
    $('#gallerie').orbit({
         animation: 'fade',                  // fade, horizontal-slide, vertical-slide, horizontal-push
         animationSpeed: 800,                // how fast animtions are
         timer: true, 			 // true or false to have the timer
         resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
         advanceSpeed: 4000, 		 // if timer is enabled, time between transitions
         pauseOnHover: false, 		 // if you hover pauses the slider
         startClockOnMouseOut: false, 	 // if clock should start on MouseOut
         startClockOnMouseOutAfter: 1000, 	 // how long after MouseOut should the timer start again
         directionalNav: true, 		 // manual advancing directional navs
         captions: true, 			 // do you want captions?
         captionAnimation: 'fade', 		 // fade, slideOpen, none
         captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
         bullets: true,			 // true or false to activate the bullet navigation
         bulletThumbs: true,		 // thumbnails for the bullets
         bulletThumbLocation: '',		 // location from this file where thumbs will be
         afterSlideChange: function(){}, 	 // empty function
         fluid: true                         // or set a aspect ratio for content slides (ex: '4x3')
    });

    $('#buttonForModal').click(function() {
          $('#myModal').reveal();
        });

    $('#sponsoring').orbit({
         animation: 'horizontal-slide',                  // fade, horizontal-slide, vertical-slide, horizontal-push
         animationSpeed: 800,                // how fast animtions are
         timer: true, 			 // true or false to have the timer
         resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
         advanceSpeed: 4000, 		 // if timer is enabled, time between transitions
         pauseOnHover: false, 		 // if you hover pauses the slider
         startClockOnMouseOut: false, 	 // if clock should start on MouseOut
         startClockOnMouseOutAfter: 1000, 	 // how long after MouseOut should the timer start again
         directionalNav: true, 		 // manual advancing directional navs
         captions: true, 			 // do you want captions?
         captionAnimation: 'fade', 		 // fade, slideOpen, none
         captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
         bullets: true,			 // true or false to activate the bullet navigation
         bulletThumbs: true,		 // thumbnails for the bullets
         bulletThumbLocation: 'bottom',		 // location from this file where thumbs will be
         afterSlideChange: function(){}, 	 // empty function
         fluid: true                         // or set a aspect ratio for content slides (ex: '4x3')
    });

    $('#bonusImage').orbit({
         animation: 'horizontal-slide',                  // fade, horizontal-slide, vertical-slide, horizontal-push
         animationSpeed: 800,                // how fast animtions are
         timer: true, 			 // true or false to have the timer
         resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
         advanceSpeed: 4000, 		 // if timer is enabled, time between transitions
         pauseOnHover: false, 		 // if you hover pauses the slider
         startClockOnMouseOut: false, 	 // if clock should start on MouseOut
         startClockOnMouseOutAfter: 1000, 	 // how long after MouseOut should the timer start again
         directionalNav: true, 		 // manual advancing directional navs
         captions: true, 			 // do you want captions?
         captionAnimation: 'fade', 		 // fade, slideOpen, none
         captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
         bullets: true,			 // true or false to activate the bullet navigation
         bulletThumbs: true,		 // thumbnails for the bullets
         bulletThumbLocation: '',		 // location from this file where thumbs will be
         afterSlideChange: function(){}, 	 // empty function
         fluid: true                         // or set a aspect ratio for content slides (ex: '4x3')
    });
    $.fn.foundationAlerts           ? $doc.foundationAlerts() : null;
    $.fn.foundationButtons          ? $doc.foundationButtons() : null;
    $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
    $.fn.foundationNavigation       ? $doc.foundationNavigation() : null;
    $.fn.foundationTopBar           ? $doc.foundationTopBar() : null;
    $.fn.foundationCustomForms      ? $doc.foundationCustomForms() : null;
    $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
    $.fn.foundationTabs             ? $doc.foundationTabs({callback : $.foundation.customForms.appendCustomMarkup}) : null;
    $.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
    $.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
    $.fn.foundationClearing         ? $doc.foundationClearing() : null;

    $('input, textarea').placeholder();
  });

  // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
  // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
  // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
  // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
  // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

  // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
  if (Modernizr.touch && !window.location.hash) {
    $(window).load(function () {
      setTimeout(function () {
        window.scrollTo(0, 1);
      }, 0);
    });
  }

})(jQuery, this);