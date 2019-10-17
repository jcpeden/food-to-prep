"use strict";

(($) => {
    $(document).ready( function() {

        $('#meal-pagination .page-link').each(function () {
           let href = $(this).attr('href');
           $(this).attr('href', href+param);
        });

        $(".meal-item__add-to-cart button").on('click', function (e) {
            e.stopPropagation();
        });

        $(".meal-item__add-to-cart .meal-item-quantity").on('click', function (e) {
            e.stopPropagation();
        });

        $('.meal-item').click(function (e) {
            window.location = $(this).data('meal-detail-page');
        });
    });
})(jQuery);