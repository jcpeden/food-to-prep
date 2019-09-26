<?php

add_filter( 'body_class','page_cart_body_classes' );
function page_cart_body_classes( $classes ) {

    $classes[] = 'meal-prep-cart-page';

    return $classes;

}

get_header(); ?>

    <div class="meal-prep">
        <div id="primary" class="content-area">
            <main id="main" class="site-main cart-page" role="main">

                <div class="row">
                    <div class="col-12">
                        <h1><?php esc_html_e('CART', 'food-to-prep'); ?></h1>
                    </div>
                    <div class="col-12">
                        <div id="cart-content">

                        </div>
                    </div>
                </div>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div>

<?php
get_footer();
