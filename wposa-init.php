<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( class_exists('MTP_Dashboard_Settings') ) {
    /**
     * Object Instantiation.
     *
     * Object for the class `MTP_Dashboard_Settings`.
     */
    // Section: Basic Settings.
    MTP_OSA()->add_section(
        array(
            'id'    => 'meal_prep_paypal_express',
            'title' => __( 'Paypal Setting', 'food-to-prep' ),
        )
    );
    // Section: Other Settings.
    MTP_OSA()->add_section(
        array(
            'id'    => 'meal_prep_other',
            'title' => __( 'Other Settings', 'food-to-prep' ),
        )
    );

    // Field: Client ID
    MTP_OSA()->add_field(
        'meal_prep_paypal_express',
        array(
            'id'      => 'client_id',
            'type'    => 'text',
            'name'    => __( 'Client ID', 'food-to-prep' ),
            'desc'    => __( 'PayPal Client ID', 'food-to-prep' ),
            'default' => '',
        )
    );

    // Field: Client Secret
    MTP_OSA()->add_field(
        'meal_prep_paypal_express',
        array(
            'id'      => 'client_secret',
            'type'    => 'text',
            'name'    => __( 'Client Secret', 'food-to-prep' ),
            'desc'    => __( 'PayPal Client Secret ID', 'food-to-prep' ),
            'default' => '',
        )
    );

    // Field: PayPal sandbox
    MTP_OSA()->add_field(
        'meal_prep_paypal_express',
        array(
            'id'   => 'paypal_test_mode',
            'type' => 'checkbox',
            'name' => __( 'PayPal sandbox', 'food-to-prep' ),
            'desc' => __( 'Enable PayPal sandbox.', 'food-to-prep' )
        )
    );

    $page_options = array();

    if( $pages = get_pages() ){
        foreach( $pages as $page ){
            $page_options[$page->post_name] = $page->post_title;
        }
    }

    // Field: Thanks you page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_thankyou',
            'type'    => 'select',
            'name'    => __( 'Thanks you page', 'food-to-prep' ),
            'desc'    => __( 'Page content for thankyou order! We will redirect to this page after payment success.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Cart page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_cart',
            'type'    => 'select',
            'name'    => __( 'Cart page', 'food-to-prep' ),
            'desc'    => __( 'Page display cart.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Checkout page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_checkout',
            'type'    => 'select',
            'name'    => __( 'Checkout page', 'food-to-prep' ),
            'desc'    => __( 'Page display checkout.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Endpoint revice order.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_revice_order',
            'type'    => 'text',
            'name'    => __( 'Revice order', 'food-to-prep' ),
            'default' => 'meal-revice-revice',
        )
    );


    // Field: Thanks you page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'title',
            'type'    => 'title',
            'name'    => '<h3>Settings</h3>',
        )
    );

    // Field: Client ID
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'post_per_page',
            'type'    => 'number',
            'name'    => __( 'Post Per Page', 'food-to-prep' ),
            'default' => '3',
        )
    );

    $currency_support = array(
        'USD' => 'USD ($)',
        'EUR' => 'Euro (€)',
        'GBP' => 'Pound (£)'
    );

    // Field: Currency
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'currency',
            'type'    => 'select',
            'name'    => __( 'Currency', 'food-to-prep' ),
            'desc'    => __( 'Currency for shop.', 'food-to-prep' ),
            'options' => $currency_support
        )
    );
}