(function( $ ) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
  
    // onReady handlers
    $(function() {
        //initialize swiper when document ready  
        var mySwiper = new Swiper ('.swiper-container', {
            loop: true,
            autoplay: 8000,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            //autoplay: 0,
            autoplayDisableOnInteraction: true,
            calculateHeight: true,
            autoHeight: true
        });
    });

    // onWindow Load handlers run after onReady event
    $( window ).load(function() {

    });
})( jQuery );