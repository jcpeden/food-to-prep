<div class="col-12">
    <table class="table-your-order">
        <thead>
            <tr>
                <th class="product-name"><?php esc_html_e('Product', 'food-to-prep') ?></th>
                <th class="product-total"><?php esc_html_e('Total', 'food-to-prep') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (FTP()->cart->get_cart() as $item) {
                ?>
                <tr>
                    <td><?php esc_attr_e($item->name, 'food-to-prep' ); ?><b> × <?php esc_attr_e($item->quality, 'food-to-prep') ?></b></td>
                    <td><?php esc_attr_e( FTP()->get_format_currency(number_format(($item->quality * $item->price), 2)), 'food-to-prep'); ?></td>
                </tr>
                <?php
            }

            ?>
        </tbody>
        <tfoot>
        <tr class="cart-subtotal">
            <th><?php esc_html_e('Subtotal', 'food-to-prep'); ?></th>
            <td><?php esc_attr_e(FTP()->cart->get_formated_cart_subtotal(), 'food-to-prep'); ?></td>
        </tr>
        <tr class="cart-total">
            <th><?php esc_html_e('Total', 'food-to-prep'); ?></th>
            <td class="cart-total__amount"><?php esc_attr_e(FTP()->cart->get_formated_cart_total(), 'food-to-prep'); ?></td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="meal-prep__checkout-payment col-12">
    <ul>
        <?php

        $payment_methods = FTP()->payment_gateways()->get_available_payment_gateways();

        foreach ($payment_methods as $key => $method){
            ?>
            <li>
                <div class="payment-input">
                    <input id="<?php esc_attr_e($method->id, 'food-to-prep'); ?>" name="payment_method" type="radio" class="input-radio" value="<?php esc_attr_e($method->id, 'food-to-prep'); ?>"/>
                    <label for="<?php esc_attr_e($method->id, 'food-to-prep'); ?>">
                        <?php esc_html_e($method->method_title, 'food-to-prep'); ?>
                    </label>
                </div>
                <div class="payment-description"><?php esc_html_e($method->description, 'food-to-prep'); ?></div>
            </li>
            <?php
        }

        ?>


    </ul>
    <div class="place-order">
        <input class="button button__place-order" type="submit" value="Place order" />
    </div>
</div>