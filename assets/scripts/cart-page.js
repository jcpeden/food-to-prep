(function ($) {

    if ($('#cart-content').length > 0){
        var data = {
        };

        $.post( meal_prep.mp_ajax_url.cart_content , data, function (response) {
            $('#cart-content').html(response.html);
        });
    }

})(jQuery);