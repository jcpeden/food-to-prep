<?php

defined('ABSPATH') || exit;

?>

<p><?php esc_html_e('Hi', 'food-to-prep'); ?> <?php esc_html_e($order->get_billing_first_name(), 'food-to-prep'); ?>, </p>
<p><?php esc_html_e('Thanks for your order. It’s on-hold until we confirm that payment has been received. In the meantime, here’s a reminder of what you ordered:', 'food-to-prep'); ?></p>

<h3>[<?php esc_html_e(get_the_title($order->get_id()), 'food-to-prep'); ?>] (<?php esc_html_e($order->get_created_date(), 'food-to-prep') ?>)</h3>

<div>
    <table>
        <thead>
        <tr style="text-align: left;">
            <th><?php esc_html_e('Product', 'food-to-prep'); ?></th>
            <th><?php esc_html_e('Quantity', 'food-to-prep'); ?></th>
            <th><?php esc_html_e('Price', 'food-to-prep'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php

        $order_items = $order->get_order_items();

        if (is_array($order_items) && sizeof($order_items) > 0) {
            foreach ($order_items as $item) {
                ?>
                <tr>
                    <td><?php esc_html_e($item->getName(), 'food-to-prep'); ?></td>
                    <td><?php esc_html_e($item->getQuality(), 'food-to-prep'); ?></td>
                    <td><?php esc_html_e($item->get_formated_price(), 'food-to-prep'); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="3"><?php esc_html_e('Nothing here!', 'food-to-prep'); ?></td>
            </tr>
            <?php
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <td colspan="2"><?php esc_html_e('Total', 'food-to-prep'); ?></td>
            <td><?php esc_html_e($order->get_formated_total(), 'food-to-prep'); ?></td>
        </tr>
        </tfoot>
    </table>
</div>

<h3><?php esc_html_e('Address', 'food-to-prep'); ?></h3>

<div>
    <p><?php esc_html_e($order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), 'food-to-prep'); ?></p>
    <p><?php esc_html_e($order->get_billing_address(), 'food-to-prep'); ?></p>
    <p><?php esc_html_e($order->get_billing_phone(), 'food-to-prep'); ?></p>
    <p><?php esc_html_e($order->get_billing_email(), 'food-to-prep'); ?></p>
</div>