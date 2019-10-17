<?php
/**
 *
 * Template navigation filter by category
 *
 */

$taxonomy = 'meal-category';

$category_id = get_query_var('cat');


$args_meal = array(
    'taxonomy' => array($taxonomy),
    'hide_empty' => true,
    'fields' => 'all'
);

$terms_meal = get_terms($args_meal);

$template_uri = home_url( FTP()->endpoint_menu());
//if ($category_id) {
//    $template_sort_uri .= $category_id . '/';
//}

$param_get = '';
$sort_key = '';
if (array_key_exists('sortby', $_GET)) {
    $sort_key = sanitize_text_field($_GET['sortby']);
    $param_get = '?sortby=' . $sort_key;
}

?>
<div class="container">

    <div class="meal-item__messages"></div>
    <div id="meal-category-filter" class="meal-category-filter">
        <div class="row">
            <ul class="meal-category">
                <li class="category-item <?php esc_attr_e((!$category_id) ? 'active' : '', 'food-to-prep'); ?>" id="category-item-all">
                    <a href="<?php echo esc_attr($template_uri . $param_get); ?>"><?php esc_html_e('All', 'food-to-prep') ?></a>
                </li>
                <?php
                foreach ($terms_meal as $index => $term):
                    ?>
                    <li class="category-item <?php esc_attr_e(($category_id == $term->term_id) ? 'active' : '', 'food-to-prep'); ?>"
                        id="category-item-<?php esc_attr_e($term->slug, 'food-to-prep') ?>">
                        <a href="<?php echo get_category_link($term->term_id); ?>"><?php esc_attr_e($term->name, 'food-to-prep') ?></a>
                    </li>
                <?php
                endforeach;
                ?>
            </ul>
        </div>
    </div>
    <div id="meal-category-sortby" class="meal-category-sortby">
        <div class="row">
            <div class="col-12">
                <div class="sortby-meal">
                    <span>Sort By:</span>
                    <ul>
                        <?php
                        $keys = array(
                            'a-z' => 'A-Z',
                            'newest' => 'NEWEST',
                            'oldest' => 'OLDEST'
                        );

                        foreach ($keys as $key => $title){

                            $sortby_url = esc_url(add_query_arg(array(
                                'sortby' => $key
                            )));

                            ?>
                            <li class="sortby-item <?php echo esc_attr(($sort_key == $key) ? 'active' : ''); ?>">
                                <a href="<?php echo $sortby_url; ?>"><?php echo esc_html($title); ?></a>
                            </li>
                            <?php
                        }

                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
