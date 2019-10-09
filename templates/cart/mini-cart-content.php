<?php
$cart = FTP()->cart;

$count_item = 0;

?>

<div class="widget-cart">
    <ul class="product_list_widget">
        <?php
        foreach ($cart->get_cart() as $item) {

            $count_item += $item->quality;

            ?>
            <li>
                <a href="<?php esc_attr_e($item->url, 'food-to-prep'); ?>"><?php esc_html_e($item->name, 'food-to-prep'); ?></a>
                <span><?php esc_attr_e($item->quality, 'food-to-prep'); ?> × <?php esc_html_e(FTP()->get_format_currency(number_format($item->price, 2)), 'food-to-prep'); ?></span>
            </li>
            <?php
        }
        ?>
    </ul>

    <div>
        <a href="#" class="meal-item_clear-cart button"><?php esc_html_e('Clear', 'food-to-prep') ?></a>
        <a href="<?php echo esc_attr(home_url(FTP()->endpoint_cart())); ?>"
           class="button"><?php esc_html_e('Cart', 'food-to-prep') ?></a>
    </div>
</div>

<a class="mini-cart-box" href="<?php echo esc_attr(home_url(FTP()->endpoint_cart())); ?>" title="Views shopping cart">
    <span>
        <b style="margin-right: 10px;"><?php esc_html_e($cart->get_formated_cart_total(), 'food-to-prep'); ?></b>
        <?php esc_html_e($count_item, 'food-to-prep'); ?> items</span>
    <i class="fas fa-shopping-basket"></i>
</a>
