<table class="meal-prep-cart__contents">
    <thead>
        <tr>
<!--            <th class="product-remove">--><?php //esc_html_e(''); ?><!--</th>-->
            <th class="product-thumbnail"><?php esc_html_e('', 'food-to-prep'); ?></th>
            <th class="product-title"><?php esc_html_e('Product', 'food-to-prep'); ?></th>
            <th class="product-price"><?php esc_html_e('Price', 'food-to-prep'); ?></th>
            <th class="product-quantity"><?php esc_html_e('Quantity', 'food-to-prep'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php

    if (sizeof(FTP()->cart->get_cart() ) > 0){


    foreach (FTP()->cart->get_cart() as $item) {
        ?>
        <tr>
<!--            <td class="product-remove"><a href=""><i class="fa fa-times" aria-hidden="true"></i></a></td>-->
            <td class="product-thumbnail">
                <a href="<?php esc_attr_e($item->url, 'food-to-prep'); ?>">
                    <img width="60" height="60" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" src="<?php esc_attr_e($item->photo, 'food-to-prep'); ?>" />
                </a>
            </td>
            <td class="product-title"><a href="<?php esc_attr_e($item->url, 'food-to-prep'); ?>"><?php esc_html_e(($item->name), 'food-to-prep'); ?></a></td>
            <td class="product-price"><?php esc_html_e(FTP()->get_format_currency($item->price), 'food-to-prep') ?></td>
            <td class="product-quantity"><?php esc_html_e($item->quality, 'food-to-prep'); ?></td>
        </tr>
        <?php
    }
        }


        ?>
    </tbody>
</table>

<div class="row">
    <div class="col-md-6 offset-md-6">
        <h2><?php esc_html_e('Cart totals', 'food-to-prep'); ?></h2>

        <table class="table-cart-totals">
            <tbody>
                <tr class="cart-subtotal">
                    <th class="cart-subtotal__title"><?php esc_html_e('Subtotal', 'food-to-prep'); ?></th>
                    <td class="cart-subtotal__amount"><?php esc_html_e(FTP()->cart->get_formated_cart_subtotal(), 'food-to-prep'); ?></td>
                </tr>
                <tr class="cart-total">
                    <th class="cart-total__title"><?php esc_html_e('Total', 'food-to-prep'); ?></th>
                    <td class="cart-total__amount"><?php esc_html_e(FTP()->cart->get_formated_cart_total(), 'food-to-prep'); ?></td>
                </tr>

            </tbody>
        </table>

        <a class="button button__proceed-to-checkout" href="<?php esc_attr_e(home_url(FTP()->endpoint_checkout()), 'food-to-prep'); ?>"><?php esc_html_e('Proceed to checkout', 'food-to-prep'); ?><i class="fas fa-long-arrow-alt-right"></i></a>
    </div>
</div>

