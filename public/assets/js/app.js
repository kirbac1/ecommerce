$(function() {
    //Toggle
    $('body').on('click', '#menu-trigger', function(e){
        e.preventDefault();
        var x = $(this).data('trigger');

        $(x).toggleClass('toggled');
        $(this).toggleClass('open');

        //Close opened sub-menus
        $('.sub-menu.toggled').not('.active').each(function(){
            $(this).removeClass('toggled');
            $(this).find('ul').hide();
        });



        $('.profile-menu .main-menu').hide();

        if (x == '#sidebar') {

            $elem = '#sidebar';
            $elem2 = '#menu-trigger';

            $('#chat-trigger').removeClass('open');

            if (!$('#chat').hasClass('toggled')) {
                $('#header').toggleClass('sidebar-toggled');
            }
            else {
                $('#chat').removeClass('toggled');
            }
        }

        //When clicking outside
        if ($('#header').hasClass('sidebar-toggled')) {
            $(document).on('click', function (e) {
                if (($(e.target).closest($elem).length === 0) && ($(e.target).closest($elem2).length === 0)) {
                    setTimeout(function(){
                        $($elem).removeClass('toggled');
                        $('#header').removeClass('sidebar-toggled');
                        $($elem2).removeClass('open');
                    });
                }
            });
        }
    })
});

/*
 * Auto Height Size Textarea
 */
autosize($('.auto-size'));