<?php

/**
 *
 * Meal Detail Page
 *
 */
global $meal_post_type;
$meal_slug = get_query_var('meal-slug');

if ($meal_slug) {
    $args = array(
        'name' => $meal_slug,
        'post_type' => $meal_post_type,
        'post_status' => 'publish',
        'posts_per_page' => 1
    );
    $meal_post = get_posts($args);
    $meal_id = $meal_post[0]->ID;
    $meal_title = $meal_post[0]->post_title;
    $meal_content = $meal_post[0]->post_content;
    $photo = get_the_post_thumbnail_url($meal_id, 'post-thumbnail');
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

    if (!is_wp_error($meal_post)) {
        get_header();
        ?>
        <div class="wrap">
            <div class="meal-prep">

                <div id="meal-detail" class="content-area">
                    <div class="container">
                        <div class="meal-item__messages"></div>

                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="meal-detail__image">
                                    <img src="<?php esc_attr_e($photo, 'food-to-prep'); ?>" class="img img-fullwidth"/>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="meal-detail__title">
                                    <h1><?php esc_html_e($meal_title, 'food-to-prep'); ?></h1>
                                </div>
                                <div class="meal-detail__price">
                                    <h2><?php esc_html_e(FTP()->get_format_currency($price), 'food-to-prep'); ?></h2>
                                </div>
                                <div class="meal-detail__content">
                                    <?php esc_html_e($meal_content, 'food-to-prep'); ?>
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
        get_footer();
    } else {
        include get_template_directory() . '/404.php';
    }
} else {
    include get_template_directory() . '/404.php';
}

?>