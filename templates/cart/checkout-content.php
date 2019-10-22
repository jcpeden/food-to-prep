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
                    <td><?php echo esc_html($item->name) ?><b> × <?php echo esc_html($item->quality) ?></b></td>
                    <td><?php echo esc_html( FTP()->get_format_currency(number_format(($item->quality * $item->price), 2))); ?></td>
                </tr>
                <?php
            }

            ?>
        </tbody>
        <tfoot>
        <tr class="cart-subtotal">
            <th><?php esc_html_e('Subtotal', 'food-to-prep'); ?></th>
            <td><?php echo esc_html(FTP()->cart->get_formated_cart_subtotal()); ?></td>
        </tr>
        <tr class="cart-total">
            <th><?php esc_html_e('Total', 'food-to-prep'); ?></th>
            <td class="cart-total__amount"><?php echo esc_html(FTP()->cart->get_formated_cart_total()); ?></td>
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
                    <input id="<?php echo esc_attr($method->id); ?>" name="payment_method" type="radio" class="input-radio" value="<?php echo esc_attr($method->id); ?>"/>
                    <label for="<?php echo esc_attr($method->id); ?>">
                        <?php echo esc_html($method->method_title); ?>
                    </label>
                </div>
                <div class="payment-description"><?php echo esc_html($method->description); ?></div>
            </li>
            <?php
        }

        ?>


    </ul>
    <div class="place-order">
        <input class="button button__place-order" type="submit" value="<?php echo esc_attr('Place order') ?>" />
    </div>
</div>