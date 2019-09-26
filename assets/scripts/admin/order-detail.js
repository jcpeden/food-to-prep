(($) => {
    $(document).ready(() => {
        if ($("#meal-prep__order-data")[0]) {
            let order_id = $(".meal-prep__order_data").data("order-id");
            $("#meal-prep__order-data h2.hndle span").remove();
            $("#meal-prep__order-data h2.hndle").append("Order #" + order_id + " Details");
        }

        $('#meal-prep__order-data #order_status').on('change', function () {
            var value = $(this).val();

            $('select[name=post_status]').val(value);

            console.log($('select[name=post_status]').val());
        });


        if ($("#meal-prep__order-data")[0]) {
            $('#toplevel_page_meal-prep').addClass('wp-has-current-submenu');
            $('#toplevel_page_meal-prep > a').addClass('wp-has-current-submenu');
        }

    });
})(jQuery);