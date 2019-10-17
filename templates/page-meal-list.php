<?php
/**
 *
 * Meal List Page
 *
 */

get_header();

if (is_page(FTP()->endpoint_menu())) {

    add_action('pre_get_posts','wpse50761_alter_query');
    function wpse50761_alter_query($query){

        if( $query->is_main_query() ){
            //Do something to main query
        }
    }


    $meal_post_type = 'meal';
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $args = array(
        'post_type'         => $meal_post_type,
        'post_status'       => 'publish',
        'paged'             => $paged
    );

    query_posts( $args );
};

?>
<div class="wrap">
    <div id="meal-list" class="meal-prep">
        <div class="">

            <?php include FoodToPrep::template_patch().'template-parts/navigation-category.php'; ?>

            <div id="meal-content-filter" class="meal-content-filter">

                <div class="container section">
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
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php

                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => __( 'Back', 'textdomain' ),
                            'next_text' => __( 'Next', 'textdomain' ),
                        ) );

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

get_footer();
?>