jQuery(document).ready(function() {
            (function() {
                var opts = jQuery.parseJSON('[[0,3],[470,4],[760,5],[980,7],[1100,7]]');
                // jQuery("#refine-images").owlCarousel({
                //     itemsCustom: opts,
                //     autoPlay: 4000,
                //     touchDrag: true,
                //     stopOnHover: true,
                //     navigation: true,
                //     scrollPerPage: true,
                //     navigationText: false,
                //     paginationSpeed: 400,
                //     margin: 13
                // });

            })();



            if (jQuery(window).width() < 760) {
                jQuery('.journal-header-center .journal-links').before($('.journal-header-center .journal-language'));
                jQuery('.journal-header-center .journal-logo').after($('.journal-header-center .journal-search'));
            }






})