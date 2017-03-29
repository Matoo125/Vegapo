(function($) {
    "use strict";

    $('body').scrollspy({
        target: '.fixed-top',
        offset: 60
    });

    new WOW().init();
    
    
    $('#collapsingNavbar li a').click(function() {
        /* always close responsive nav after click */
        $('.navbar-toggler:visible').click();
    });

    $('#subscribe').submit(function(e) {
        e.preventDefault();

        var form = $( this );
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize,
            dataType: 'json',
            success: function (data) {
                alert('ok')
                console.log(data)
            },
            error: function (xhr, desc, err) {
                alert('error')
                console.log(err)
                console.log(xhr)
            }
        });

    })



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