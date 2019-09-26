"use strict";

(($) => {
    $(document).ready( function() {
        var $grid = $('.grid').isotope({
            itemSelector: '.grid-item',
            layoutMode: 'fitRows',
            isInitLayout: false
        })
        .on( 'arrangeComplete', function( event, filteredItems ) {
            $(this).removeClass('hide');
        });
        $grid.isotope();

        $('#meal-pagination').pagination({
            currentPage: $('#meal-pagination').data('paged'),
            items: $('#meal-pagination').data('total'),
            itemsOnPage: $('#meal-pagination').data('per-page'),
            cssStyle: 'light-theme',
            hrefTextPrefix: $('#meal-pagination').data('template-uri')
        });
        let param = $('#meal-pagination').data('param');
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

        // $(".meal-item-quantity-minus").click(function (e) {
        //     let $input = $(this).closest(".meal-item-quantity").find(".input-quantity");
        //     let value = parseInt($input.val());
        //     $input.val( Math.max(1, value - 1) );
        //     if( parseInt($input.val()) > 1 ) {
        //         $(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").removeClass("disabled");
        //     } else {
        //         if( !$(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").hasClass("disabled") ) {
        //             $(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").addClass("disabled");
        //         }
        //     }
        // });
        //
        // $(".meal-item-quantity-plus").click(function (e) {
        //     let $input = $(this).closest(".meal-item-quantity").find(".input-quantity");
        //     let value = parseInt($input.val());
        //     $input.val(value + 1);
        //     if( parseInt($input.val()) > 1 ) {
        //         $(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").removeClass("disabled");
        //     } else {
        //         if( !$(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").hasClass("disabled") ) {
        //             $(this).closest(".meal-item-quantity").find(".meal-item-quantity-minus").addClass("disabled");
        //         }
        //     }
        // });

        $(".input-quantity").on('keyup enter click',function () {
            var pattern = /^[0-9]+$/;
            var quantity = $(this).val();
            if( !pattern.test(quantity) ) {
                $(this).parent().closest(".meal-item__add-to-cart").find('.error-message').addClass("show");
                $(this).addClass('has-error');
                $('.grid').isotope();
            } else {
                if( $(this).parent().closest(".meal-item__add-to-cart").find('.error-message').hasClass("show") ) {
                    $(this).parent().closest(".meal-item__add-to-cart").find('.error-message').removeClass("show");
                    $(this).removeClass('has-error');
                    $('.grid').isotope();
                }
            }
        });

        $(".input-quantity").on('change',function () {
            var quantity = $(this).val();
            if( !Number.parseInt(quantity) ) {
                $(this).val(1);
                if( $(this).parent().closest(".meal-item__add-to-cart").find('.error-message').hasClass("show") ) {
                    $(this).parent().closest(".meal-item__add-to-cart").find('.error-message').removeClass("show");
                    $(this).removeClass('has-error');
                    $('.grid').isotope();
                }
            }
        });

    });
})(jQuery);