(function ($) {

    if ($('#checkout-order-detail').length > 0) {
        var data = {};

        $.post( meal_prep.mp_ajax_url.checkout_order_detail , data, function (response) {
            $('#checkout-order-detail').html(response.html);

            $('.checkout-page [name=payment_method]').first().prop("checked", true).change();
        });
    }

    $(document).on('submit', '#checkout-content', function (e) {
        e.preventDefault();

        var form = $(this);

        var data = form.serialize();

        $.post(meal_prep.mp_ajax_url.checkout, data, function (response) {

            if (response.result == 'failure'){
                    $('#checkout-content .checkout-errors').remove();
                    $('#checkout-content').prepend('<div class="checkout-errors col-12">'+ response.messages +'</div>');

                    $('html, body').animate({
                        scrollTop: $('#checkout-content .checkout-errors').offset().top
                    }, 1000);

            }else{
                console.log(response);

                if (response.reload){
                    window.location.href = response.messages;
                }
            }
        });
    });

    // $(document).on('change', '#checkout-content input', function (e) {
    //     var form = $('#checkout-content');
    //     var data = form.serialize();
    //
    //     console.log(data);
    // });

    // $(document).ready( () => {
    //
    //     $('.billing_country')
    //         .dropdown({
    //             clearable: true,
    //             match: 'text',
    //             placeholder: 'Select country ...'
    //         })
    //     ;
    // });

    $('.checkout-page').on('change', '[name=payment_method]', function (e) {
        var that = $(this);

        $('.checkout-page .payment-description').each(function () {
            $(this).hide();
        });

        that.closest('li').find('.payment-description').show();
    });

})(jQuery);