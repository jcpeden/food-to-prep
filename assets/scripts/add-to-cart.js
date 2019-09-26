(function ($) {

    $('.meal-item__add-to-cart button').click(function (e) {
        e.preventDefault();

        var btn = $(this);
        var item = btn.val();
        var pattern = /^[0-9]+$/;
        var quantity = $(this).closest(".meal-item__add-to-cart").find('.meal-item-quantity .input-quantity').val();
        if( !pattern.test(quantity) ) {
            $(this).closest(".meal-item__add-to-cart").find('.error-message').addClass("show");
            $('.grid').isotope();
            setTimeout( () => {
                $(this).closest(".meal-item__add-to-cart").find('.error-message').removeClass("show");
                $('.grid').isotope();
            }, 3000);
        }

        var data = {
            'id': item,
            'quantity': quantity
        };

        btn.addClass('loading');

        $.post(meal_prep.mp_ajax_url.add_to_cart, data, function (response) {

            $.each( response.fragments, function( key, content ) {
                $(key).html(content);
            });

        }).done(function () {
            btn.removeClass('loading');
        })
    });

    if ($('.meal-item__mini-cart').length > 0) {
        var data = {
        };

        $.post(meal_prep.mp_ajax_url.get_refreshed_fragments, data, function (response) {
            $.each( response.fragments, function( key, content ) {
                $(key).html(content);
            });
        });
    }

    $(document).on('click', '.meal-item_clear-cart', function (e) {
        e.preventDefault();

        var data = {
        };

        $.post(meal_prep.mp_ajax_url.clear_cart , data, function (response) {

            $.each( response.fragments, function( key, content ) {
                $(key).html(content);
            });

        });
    })



})(jQuery);