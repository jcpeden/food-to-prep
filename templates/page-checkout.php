<?php
    if (FTP()->cart->is_empty()){
        wp_redirect(home_url());
    }

    add_filter( 'body_class','page_cart_body_classes' );
    function page_cart_body_classes( $classes ) {

        $classes[] = 'meal-prep-checkout-page';

        return $classes;

    }
    ?>

<?php
get_header(); ?>

    <div class="meal-prep">
        <div id="primary" class="content-area">
            <main id="main" class="site-main checkout-page" role="main">

                <div class="row">
                    <div class="col-12">
                        <h1><?php esc_html_e('CHECKOUT', 'food-to-prep'); ?></h1>
                    </div>
                    <div class="col-12">
                        <form id="checkout-content" class="row">
                            <div class="col-md-6">
                                <h3 class="title"><?php esc_html_e('Billing details', 'food-to-prep'); ?></h3>
                                <div class="row">
                                    <div class="col-md-6 billing-info">
                                        <label class="label"><?php esc_html_e('First name', 'food-to-prep'); ?></label>
                                        <input type="text" name="billing_first_name" />
                                    </div>

                                    <div class="col-md-6 billing-info">
                                        <label class="label"><?php esc_html_e('Last name', 'food-to-prep'); ?></label>
                                        <input type="text" name="billing_last_name" />
                                    </div>

                                    <div class="col-md-12 billing-info">
                                        <label class="label"><?php esc_html_e('Address', 'food-to-prep') ?></label>
                                        <input type="text" name="billing_address">
                                    </div>

                                    <div class="col-md-12 billing-info">
                                        <label class="label"><?php esc_html_e('Postcode', 'food-to-prep') ?></label>
                                        <input type="text" name="billing_postcode" />
                                    </div>

                                    <div class="col-md-6 billing-info">
                                        <label class="label"><?php esc_html_e('Phone', 'food-to-prep') ?></label>
                                        <input type="text" name="billing_phone">
                                    </div>

                                    <div class="col-md-6 billing-info">
                                        <label class="label"><?php esc_html_e('Email', 'food-to-prep') ?></label>
                                        <input type="text" name="billing_email">
                                    </div>
                                </div>
                                <br />
                                <h3><?php esc_html_e('Additional information', 'food-to-prep'); ?></h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="label"><?php esc_html_e('Order notes', 'food-to-prep'); ?></label>
                                        <textarea name="billing_order_comments" class="input-text" id="billing_order_comments"
                                                  placeholder="<?php esc_html_e('Notes about your order, e.g. special notes for delivery.', 'food-to-prep'); ?>"
                                                  rows="2" cols="5" spellcheck="false"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h3 class="title"><?php esc_html_e('Your order', 'food-to-prep'); ?></h3>
                                <div id="checkout-order-detail" class="row">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div>

<?php
get_footer();
