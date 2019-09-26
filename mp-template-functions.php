<?php
defined('ABSPATH') || exit;

if (!function_exists('meal_to_prep_mini_cart')) {

    function meal_to_prep_mini_cart()
    {
        include( FoodToPrep::template_patch() . 'cart/mini-cart-content.php' );
    }
}

if (!function_exists('meal_to_prep_get_html_mini_cart')){

    /**
     * Display mini cart
     *
     * @return string
     */
    function meal_to_prep_get_html_mini_cart()
    {
        ob_start();
        include( FoodToPrep::template_patch() . 'cart/mini-cart.php' );
        return ob_get_clean();
    }
}

if (!function_exists('meal_to_pre_get_cart_content')){
    function meal_to_pre_get_cart_content(){
        include( FoodToPrep::template_patch() . 'cart/cart-content.php' );
    }
}

if (!function_exists('meal_to_prep_get_checkout_content')){

    function meal_to_prep_get_checkout_content(){
        include( FoodToPrep::template_patch() . 'cart/checkout-content.php' );
    }
}


if (!function_exists('meal_to_prep_get_add_to_cart_button')){
    function meal_to_prep_get_add_to_cart_button($id)
    {
        ob_start();
        include( FoodToPrep::template_patch() . 'add-to-cart/add-to-cart.php' );
        print ob_get_clean();
    }
}