<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( class_exists('MTP_Dashboard_Settings') ) {
    /**
     * Object Instantiation.
     *
     * Object for the class `MTP_Dashboard_Settings`.
     */

    // Section: General Settings.
    MTP_OSA()->add_section(
        array(
            'id'    => 'meal_prep_other',
            'title' => __( 'General Settings', 'food-to-prep' ),
        )
    );

    // Section: Payment Settings.
    MTP_OSA()->add_section(
        array(
            'id'    => 'meal_prep_paypal_express',
            'title' => __( 'PayPal Settings', 'food-to-prep' ),
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

    // Field: Enable Sandbox Mode
    MTP_OSA()->add_field(
        'meal_prep_paypal_express',
        array(
            'id'   => 'paypal_test_mode',
            'type' => 'checkbox',
            'name' => __( 'Enable Sandbox Mode', 'food-to-prep' ),
            'desc' => __( 'Enable PayPal sandbox to test payments.', 'food-to-prep' )
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
            'id'      => 'endpoint_meal_list',
            'type'    => 'select',
            'name'    => __( 'Menu', 'food-to-prep' ),
            'desc'    => __( 'Page content for Menu page. This display list food.', 'food-to-prep' ),
            'options' => $page_options
        )
    );


    // Field: Thanks you page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_thankyou',
            'type'    => 'select',
            'name'    => __( 'Order Confirmation Page', 'food-to-prep' ),
            'desc'    => __( 'Redirect the user to this page after they purchase. payment success.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Cart Page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_cart',
            'type'    => 'select',
            'name'    => __( 'Cart Page', 'food-to-prep' ),
            'desc'    => __( 'Display the user\'s shopping cart on this page.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Checkout Page.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_checkout',
            'type'    => 'select',
            'name'    => __( 'Checkout Page', 'food-to-prep' ),
            'desc'    => __( 'Display the checkout on this page.', 'food-to-prep' ),
            'options' => $page_options
        )
    );

    // Field: Endpoint revice order.
    MTP_OSA()->add_field(
        'meal_prep_other',
        array(
            'id'      => 'endpoint_revice_order',
            'type'    => 'text',
            'name'    => __( 'Order Review Page', 'food-to-prep' ),
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
            'desc'    => __( 'Which currency would you like to run your shop in?.', 'food-to-prep' ),
            'options' => $currency_support
        )
    );
}