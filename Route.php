<?php

/**
 *
 * Class define route of all page use in front-end of plugin
 *
 */

class Route {

    public function __construct() {
        add_filter( 'generate_rewrite_rules', array( $this, 'route' ) );
        add_filter( 'query_vars', array( $this, 'query_var' ) );
        add_action( 'template_redirect', array( $this, 'template' ) );
    }


    function route( $wp_rewrite ) {
        $wp_rewrite->rules = array_merge(
            array(
                'meal-list/paged/(.*)/?$' => 'index.php?meal-list=true&paged=$matches[1]',
                'meal-list/(.*)/paged/(.*)/?$' => 'index.php?meal-list=true&category=$matches[1]&paged=$matches[2]',
                'meal-list/(.*)/?$' => 'index.php?category=$matches[1]',
                'meal-list/?$' => 'index.php?meal-list=true',
                'meal/(.*?)/?$' => 'index.php?meal-slug=$matches[1]'
            )
        , $wp_rewrite->rules );
        return $wp_rewrite->rules;
    }

    function query_var( $query_vars ) {
        $query_vars[] = 'meal-list';
        $query_vars[] = 'paged';
        $query_vars[] = 'category';
        $query_vars[] = 'meal-slug';
        return $query_vars;
    }

    function template() {
        $is_meal_list_page = get_query_var( 'meal-list' );
        if ( $is_meal_list_page ) {
            include FoodToPrep::template_patch().'page-meal-list.php';
            die();
        }

        $meal_category = get_query_var('category');
        if( $meal_category ) {
            include FoodToPrep::template_patch().'page-meal-list.php';
            die();
        }

        $meal_slug = get_query_var('meal-slug');
        if( $meal_slug ) {
            include FoodToPrep::template_patch().'page-meal-detail.php';
            die();
        }
    }

}