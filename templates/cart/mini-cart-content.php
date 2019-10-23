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
                <a href="<?php echo esc_url($item->url) ?>"><?php echo esc_html($item->name) ?></a>
                <span><?php echo esc_html($item->quality) ?> × <?php echo esc_html(FTP()->get_format_currency(number_format($item->price, 2))) ?></span>
            </li>
            <?php
        }
        ?>
    </ul>

    <div>
        <a href="#" class="meal-item_clear-cart button"><?php esc_html_e('Clear', 'food-to-prep') ?></a>
        <a href="<?php echo esc_url(home_url(FTP()->endpoint_cart())); ?>"
           class="button"><?php esc_html_e('Cart', 'food-to-prep') ?></a>
    </div>
</div>

<a class="mini-cart-box" href="<?php echo esc_url(home_url(FTP()->endpoint_cart())); ?>" title="Views shopping cart">
    <span>
        <b style="margin-right: 10px;"><?php echo esc_html($cart->get_formated_cart_total()); ?></b>
        <?php echo esc_html($count_item) ?> items</span>
    <i class="fas fa-shopping-basket"></i>
</a>
