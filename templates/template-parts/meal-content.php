<?php

$meal = get_post();

$id = $meal->ID;
$product = new MP_Product($meal);

$title = $product->get_title();
$content_summary = $product->get_description_excerpt();
$price = $product->get_normal_price();
$slug = $product->get_slug();

$photo = $product->get_photo();

?>

<div class="grid-item" <?php esc_attr_e($price ? '' : 'style="display: none;"', 'food-to-prep'); ?> >
    <div id="<?php esc_attr_e($id, 'food-to-prep'); ?>" class="meal-item"
         data-meal-detail-page="<?php esc_attr_e(get_home_url() . "/meal/" . $slug, 'food-to-prep'); ?>">
        <div class="meal-item__photo"
             style="background-image: url(<?php echo esc_url($photo); ?>);"></div>
        <div class="meal-item__details">
            <div class="meal-item__title">
                <h3><?php echo esc_html($title); ?></h3>
            </div>
            <div class="meal-item__content">
                <p><?php echo esc_html($content_summary); ?></p>
            </div>
            <div class="meal-item__price">
                <p><?php echo esc_html(FTP()->get_format_currency($price)); ?></p>
            </div>
            <?php
            do_action('meal-item__add-to-cart', $id);
            ?>
        </div>
    </div>
</div>