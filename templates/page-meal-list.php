<?php

/**
 *
 * Meal List Page
 *
 */

ob_start();
get_header();
$header = ob_get_clean();
$header = preg_replace('#<title>(.*?)<\/title>#', '<title>Meal List</title>', $header);
print $header;
global $wpdb, $meal_post_type, $taxonomy;

$setting = FTP()->settings;
$paged = intval( get_query_var('paged') );
$paged = $paged?$paged:1;
$posts_per_page = $setting->get_post_per_page();
$category_id = intval( get_query_var('category') );
$template_pagination_uri = '/meal-list/paged/';
$orderby = 'date';
$sortby = 'DESC';
$param_get = '';
if( array_key_exists('sortby', $_GET) ) {

    $sort_key = sanitize_text_field($_GET['sortby']);
    $param_get = '?sortby='.$sort_key;



    switch ( $sort_key ) {
        case 'oldest':
            $sortby = 'ASC';
            break;
        case 'a-z':
            $orderby = 'title';
            $sortby = 'ASC';
            break;
        default:
            $orderby = 'date';
            $sortby = 'DESC';
    }
}

$args = array(
    'post_type'         => $meal_post_type,
    'post_status'       => 'publish',
    'posts_per_page'    => $posts_per_page,
    'paged'             => $paged,
    'orderby'           => $orderby,
    'order'             => $sortby
);

if($category_id) {
    $query_taxonomy = array(
        'taxonomy' => $taxonomy,
        'field' => 'term_id',
        'terms' => $category_id
    );
    $args['tax_query'] = array($query_taxonomy);
    $template_pagination_uri = '/meal-list/'.$category_id.'/paged/';
}

$meals = get_posts( $args );

$total_meal = 0;
if( !$category_id ) {
    $count_meal_obj = wp_count_posts( $meal_post_type );
    if( !is_wp_error($count_meal_obj) ) {
        $total_meal = $count_meal_obj->publish;
    }
}
else {
    $query_str = "
    SELECT COUNT( DISTINCT cat_posts.ID ) AS post_count
        FROM wp_term_taxonomy AS cat_term_taxonomy INNER JOIN wp_terms AS cat_terms ON
        cat_term_taxonomy.term_id = cat_terms.term_id
        INNER JOIN wp_term_relationships AS cat_term_relationships 
        ON cat_term_taxonomy.term_taxonomy_id = cat_term_relationships.term_taxonomy_id
        INNER JOIN wp_posts AS cat_posts 
        ON cat_term_relationships.object_id = cat_posts.ID
        WHERE cat_posts.post_status = 'publish' 
        AND cat_posts.post_type = '".$meal_post_type."' 
        AND cat_term_taxonomy.taxonomy = '".$taxonomy."' 
        AND cat_terms.term_id = ".$category_id."
    ";
    $total_meal = $wpdb->get_var($query_str);
}

$maxium_page = apply_filters('compute_pagination', array($total_meal, $posts_per_page));

?>
<div class="wrap">
    <div id="meal-list" class="meal-prep">
        <div class="">
            <?php include FoodToPrep::template_patch().'template-parts/navigation-category.php'; ?>
            <div id="meal-content-filter" class="meal-content-filter">
                <?php include FoodToPrep::template_patch().'template-parts/grid-gallery.php'; ?>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php if( $total_meal > $posts_per_page ): ?>
                        <div id="meal-pagination" class="meal-pagination"
                             data-total="<?php echo esc_attr($total_meal); ?>"
                             data-per-page="<?php echo esc_attr($posts_per_page); ?>"
                             data-template-uri="<?php echo esc_attr($template_pagination_uri); ?>"
                             data-paged="<?php echo esc_attr($paged); ?>"
                             data-param="<?php echo esc_attr($param_get); ?>"
                        >
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

get_footer();
?>