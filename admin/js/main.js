jQuery(document).ready(function($){

    function mirrorSync() {
        var el = $('.syncronize-with-name');
        var syncLink = $(el).parent('a');
        var baseLink = $(syncLink).attr('href');
        var target = $(el).data('synctarget');
        //Scrivo subito il nome della sitemap di default
        $(el).each(function(){
            $(this).text($(target).val());
            $(syncLink).attr('href', baseLink + $(target).val() + '.xml');
        });

        //Cambio il nome della sitemap direttamente quando l'utente scrive il nome scelto.
        $(target).on('keyup', function(){
            var currentName = $(this).val();
            el.each(function(){
                $(this).text(currentName);
            });
        })
    }

    mirrorSync();

    var digits = '';
    var count = 1;

    function changePostTypeView(){
        var view = $(this).data('view');
        var target = $('.tables-container-xmg #'+view);

        $(this).addClass('active');
        $(this).siblings().removeClass('active');
        $(target).addClass('visible');
        $(target).siblings().removeClass('visible');
    }

    $('.sub-tab').click(changePostTypeView);

    $(document).keyup(function(e){
        digits += e.keyCode;
        if(count == 5){
            if(digits == '6869668571'){
                var target = window.location;
                target = target.protocol+'//'+target.hostname;
                $.ajax({
                    type:   'POST',
                    url:    target+'/wp-admin/admin-ajax.php',
                    data:   {
                        action    : 'toggle-debug-mode',
                    },
                    dataType: 'json'
                }).done(function( json ) {
                    if( json.success ) {
                        alert('Modalità debug: '+ json.message);
                        location.reload();
                    }
                })
            } else {
                count = 0;
                digits = '';
            }
        }
        count++;
    });
});