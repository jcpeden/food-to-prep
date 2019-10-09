<?php

/**
 *
 * Meal Detail Page
 *
 */

get_header();

while (have_posts()) :
    the_post();


    $meal_id = $post->ID;
    $meal_meta = get_post_custom($meal_id);
    $price = 0;
    if (is_array($meal_meta) && array_key_exists('normal_price', $meal_meta)) {
        $price = $meal_meta['normal_price'][0];
    }
    $term_obj = wp_get_post_terms($meal_id, 'category', array('fields' => 'all'));
    $term_name = "";
    $term_id = "";
    if (!is_wp_error($term_obj)) {
        foreach ($term_obj as $term) {
            if ($term->name) {
                $term_name = $term->name;
            }
            if ($term->term_id) {
                $term_id = $term->term_id;
            }
        }
    }

    $template_uri_category = '/meal-list/';

    ?>
    <div class="wrap">
        <div class="meal-prep">

            <div id="meal-detail" class="content-area">
                <div class="container">
                    <div class="meal-item__messages"></div>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="meal-detail__image">
                                <?php the_post_thumbnail('full', ['class' => 'img img-fullwidth']) ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="meal-detail__title">
                                <?php the_title('<h1>', '</h1>'); ?>
                            </div>
                            <div class="meal-detail__price">
                                <h2><?php echo esc_html(FTP()->get_format_currency($price)); ?></h2>
                            </div>
                            <div class="meal-detail__content">
                                <?php the_content() ?>
                            </div>
                            <?php
                            do_action('meal-item__add-to-cart', $meal_id);
                            ?>
                            <hr/>
                            <div class="meal-detail__more-info">
                                <span><?php esc_html_e('Category:', 'food-to-prep'); ?> </span><span><a
                                        href="<?php esc_attr_e($template_uri_category . $term_id, 'food-to-prep'); ?>"><?php esc_html_e($term_name, 'food-to-prep'); ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

endwhile; // End of the loop.

get_footer();

?>