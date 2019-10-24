<?php
/**
 *
 * Meal List Page
 *
 */

add_action('pre_get_posts', 'ftp_modify_query_exclude_category');

function ftp_modify_query_exclude_category(){

}

get_header();

if (is_page(FTP()->endpoint_menu())) {
    // Modify main Query

    global $wp_query;

    $meal_post_type = 'meal';
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $args = array(
        'post_type'         => $meal_post_type,
        'post_status'       => 'publish',
        'paged'             => $paged
    );

    $wp_query = new WP_Query($args);
};


?>
<div class="wrap">
    <div id="meal-list" class="meal-prep">
        <div class="">

            <?php include FoodToPrep::template_patch().'template-parts/navigation-category.php'; ?>

            <div id="meal-content-filter" class="meal-content-filter">

                <section class=section">
                    <div class="row">
                        <div class="col-12">
                            <div class="grid grid-gallery">
                                <?php

                                while ( have_posts() ) :
                                    the_post();

                                    include FoodToPrep::template_patch() . 'template-parts/meal-content.php';

                                endwhile;

                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <section>
                <div class="row">
                    <div class="col-12">
                        <?php

                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => __( 'Prev', 'food-to-prep' ),
                            'next_text' => __( 'Next', 'food-to-prep' ),
                        ) );

                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php

get_footer();
?>