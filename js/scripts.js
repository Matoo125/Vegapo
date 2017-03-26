(function($) {
    "use strict";

    $('body').scrollspy({
        target: '.fixed-top',
        offset: 60
    });

    new WOW().init();
    
    $('a.page-scroll').bind('click', function(event) {
        var $ele = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($ele.attr('href')).offset().top - 60)
        }, 1450, 'easeInOutExpo');
        event.preventDefault();
    });
    
    $('#collapsingNavbar li a').click(function() {
        /* always close responsive nav after click */
        $('.navbar-toggler:visible').click();
    });

    $('#galleryModal').on('show.bs.modal', function (e) {
       $('#galleryImage').attr("src",$(e.relatedTarget).data("src"));
    });



})(jQuery);

    var bLazy = new Blazy({

      success: function(element){
        setTimeout(function(){
        // We want to remove the loader gif now.
        // First we find the parent container
        // then we remove the "loading" class which holds the loader image
        var parent = element.parentNode;
        parent.className = parent.className.replace(/\bloading\b/,'');
        }, 200);
        }
   });