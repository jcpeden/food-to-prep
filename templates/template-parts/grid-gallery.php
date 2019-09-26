<?php
/**
 *
 * Template Gird Gallery
 *
 */

?>

<div class="container section">
    <div class="row">
        <div class="col-12">
            <div class="grid grid-gallery hide">
                <?php
                foreach ($meals as $meal):
                    $id = $meal->ID;
                    $product = new MP_Product($meal);

                    $title = $product->get_title();
                    $content_summary = $product->get_description_excerpt();
                    $price = $product->get_normal_price();
                    $slug = $product->get_slug();

                    $photo = get_the_post_thumbnail_url($id, 'post-thumbnail');
                    ?>
                    <div class="grid-item" <?php esc_attr_e($price ? '' : 'style="display: none;"', 'food-to-prep'); ?> >
                        <div id="<?php esc_attr_e($id, 'food-to-prep'); ?>" class="meal-item"
                             data-meal-detail-page="<?php esc_attr_e(get_home_url() . "/meal/" . $slug, 'food-to-prep'); ?>">
                            <div class="meal-item__photo"
                                 style="background-image: url(<?php esc_attr_e($photo, 'food-to-prep'); ?>);"></div>
                            <div class="meal-item__details">
                                <div class="meal-item__title">
                                    <h3><?php esc_html_e($title, 'food-to-prep'); ?></h3>
                                </div>
                                <div class="meal-item__content">
                                    <p><?php esc_html_e($content_summary, 'food-to-prep'); ?></p>
                                </div>
                                <div class="meal-item__price">
                                    <p><?php esc_html_e(FTP()->get_format_currency($price), 'food-to-prep'); ?></p>
                                </div>
                                <?php
                                do_action('meal-item__add-to-cart', $id);
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>
</div>
