<?php

defined('ABSPATH') || exit;

if (!class_exists('CPT_MP_Meal')) :

    /**
     *
     * Global variable
     *
     */
    class CPT_MP_Meal
    {
        private $post_type;
        private $category;

        function __construct()
        {
            $this->post_type = 'meal';
            $this->category = 'meal-category';

            add_action('init', array($this, 'meal_init'), 0);
            add_action('init', array($this, 'meal_category_inti'), 0);

            add_filter("manage_{$this->post_type}_posts_columns", array($this, 'set_custom_edit_meal_columns'));

            add_action("manage_{$this->post_type}_posts_custom_column", array($this, 'custom_meal_column'), 10, 2);

            add_action('admin_init', array($this, 'metabox_init'));
            add_action('save_post', array($this, 'save_meal_cpt_meta'));

            add_filter( 'single_template', array($this, 'get_custom_post_type_template') );
            add_filter( 'taxonomy_template', array($this, 'filter_category_template') );

        }




        /**
         * Registers the `meal` post type.
         */
        function meal_init()
        {
            $meal_post_type = $this->post_type;

            register_post_type($meal_post_type, array(
                'labels' => array(
                    'name' => __('Meals', 'food-to-prep'),
                    'singular_name' => __('Meal', 'food-to-prep'),
                    'all_items' => __('All Meals', 'food-to-prep'),
                    'archives' => __('Meal Archives', 'food-to-prep'),
                    'attributes' => __('Meal Attributes', 'food-to-prep'),
                    'insert_into_item' => __('Insert into Meal', 'food-to-prep'),
                    'uploaded_to_this_item' => __('Uploaded to this Meal', 'food-to-prep'),
                    'featured_image' => _x('Featured Image', 'meal', 'food-to-prep'),
                    'set_featured_image' => _x('Set featured image', 'meal', 'food-to-prep'),
                    'remove_featured_image' => _x('Remove featured image', 'meal', 'food-to-prep'),
                    'use_featured_image' => _x('Use as featured image', 'meal', 'food-to-prep'),
                    'filter_items_list' => __('Filter Meals list', 'food-to-prep'),
                    'items_list_navigation' => __('Meals list navigation', 'food-to-prep'),
                    'items_list' => __('Meals list', 'food-to-prep'),
                    'new_item' => __('New Meal', 'food-to-prep'),
                    'add_new' => __('Add New', 'food-to-prep'),
                    'add_new_item' => __('Add New Meal', 'food-to-prep'),
                    'edit_item' => __('Edit Meal', 'food-to-prep'),
                    'view_item' => __('View Meal', 'food-to-prep'),
                    'view_items' => __('View Meals', 'food-to-prep'),
                    'search_items' => __('Search Meals', 'food-to-prep'),
                    'not_found' => __('No Meals found', 'food-to-prep'),
                    'not_found_in_trash' => __('No Meals found in trash', 'food-to-prep'),
                    'parent_item_colon' => __('Parent Meal:', 'food-to-prep'),
                    'menu_name' => __('Meals', 'food-to-prep'),
                ),
                'public' => true,
                'hierarchical' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields'),
                'has_archive' => true,
                'can_export' => true,
                'exclude_from_search' => false,
                'rewrite' => true,
                'query_var' => true,
                'menu_icon' => 'dashicons-admin-post'
            ));

        }

        /**
         *
         * Register Category of 'meal' post type
         *
         */
        function meal_category_inti()
        {
            $taxonomy = $this->category;
            $meal_post_type = $this->post_type;

            $labels = array(
                'name' => __('Meal Categories', 'food-to-prep'),
                'singular_name' => __('Meal Category', 'food-to-prep'),
                'search_items' => __('Search Meal Category', 'food-to-prep'),
                'all_items' => __('All Meal Category', 'food-to-prep'),
                'parent_item' => __('Parent Meal Category', 'food-to-prep'),
                'parent_item_colon' => __('Parent Meal Category:', 'food-to-prep'),
                'update_item' => __('Update Meal Category', 'food-to-prep'),
                'add_new_item' => __('Add New Meal Category', 'food-to-prep'),
                'new_item_name' => __('New Meal Category Name', 'food-to-prep'),
                'menu_name' => __('Categories', 'food-to-prep'),
            );

            register_taxonomy($taxonomy, array($meal_post_type), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => true
            ));
        }

        /**
         *
         * Register metabox
         *
         */

        function metabox_init()
        {
            $meal_post_type = $this->post_type;

            add_meta_box("price_meta", "Price", array($this, 'price_meta_cb'), $meal_post_type, "normal", "high");
            add_meta_box("details_meta", "Details", array($this, 'details_meta_cb'), $meal_post_type, "normal", "high");

        }


        /**
         *
         * Function callback Price Meta Meal CPT
         *
         */
        function price_meta_cb()
        {
            global $post;
            $price_meta = get_post_custom($post->ID);
            $normal_price = $special_price = '';
            if (is_array($price_meta)) {
                if (array_key_exists("normal_price", $price_meta)) {
                    $normal_price = $price_meta["normal_price"][0];
                }
                if (array_key_exists("special_price", $price_meta)) {
                    $special_price = $price_meta["special_price"][0];
                }
            }
            print '
        <p>
            <label>' . __('Normal Price', 'food-to-prep') . ':</label><br />
            <input type="number" class="meal-number-meta" name="normal_price" value="' . esc_attr($normal_price) . '" /> $
        </p>
        <p>
            <label>' . __('Special Price', 'food-to-prep') . ':</label><br />
            <input type="number" class="meal-number-meta" name="special_price" value="' . esc_attr($special_price) . '" /> $
        </p>
    ';
        }

        /**
         *
         * Function save price meta
         *
         */
        function save_price_meta()
        {
            global $post;

            if (!is_wp_error($post) && is_object($post) && $post->post_type == $this->post_type) {
                $normal_price = sanitize_text_field($_POST["normal_price"]);
                $special_price = sanitize_text_field($_POST["special_price"]);

                update_post_meta($post->ID, "normal_price", $normal_price);
                update_post_meta($post->ID, "special_price", $special_price);
            }
        }

        /**
         *
         * Function callback Details Meta Meal CPT
         *
         */
        function details_meta_cb()
        {
            global $post;
            $details_meta = get_post_custom($post->ID);
            $quantity = '';
            $unit = '';
            if (is_array($details_meta)) {
                if (array_key_exists("quantity", $details_meta) && array_key_exists("unit", $details_meta)) {
                    $quantity = $details_meta["quantity"][0];
                    $unit = $details_meta["unit"][0];
                }
            }
            print '
        <div id="meal-details-meta">
            <label>' . __('Quantity', 'food-to-prep') . ':</label><br />
            <input type="number" class="meal-number-meta--quantity" name="quantity" value="' . esc_attr($quantity) . '" />
            <label class="unit-label">' . __('Unit', 'food-to-prep') . ':</label><br />
            <input type="text" class="meal-text-meta--unit" name="unit" value="' . esc_attr($unit) . '" />
        </div>
    ';
        }

        /**
         * Function save details meta
         */
        function save_details_meta()
        {
            global $post;

            $quantity = sanitize_text_field($_POST["quantity"]);
            $unit = sanitize_text_field($_POST["unit"]);

            if (!is_wp_error($post) && is_object($post) && isset($quantity) && trim($unit) && $post->post_type == $this->post_type) {
                update_post_meta($post->ID, "quantity", $quantity);
                update_post_meta($post->ID, "unit", trim($unit));
            }
        }

        /**
         * Function save meal CPT meta
         */
        function save_meal_cpt_meta()
        {
            $this->save_price_meta();
            $this->save_details_meta();
        }

        /**
         * Add columns to admin list view.
         *
         * @param $columns
         * @return mixed
         */
        function set_custom_edit_meal_columns($columns){
            $columns['price'] = __('Price', 'food-to-prep');

            return $columns;
        }

        /**
         * Render column list view.
         *
         * @param $column
         * @param $post_id
         */
        function custom_meal_column($column, $post_id)
        {
            $meal = get_post($post_id);
            $product = new MP_Product($meal);

            switch ($column) {
                case 'price' :
                    echo esc_html($product->get_formated_normal_price());
                    break;
            }
        }


        function get_custom_post_type_template( $single_template ) {
            global $post;

            if ( $this->post_type === $post->post_type ) {
                $single_template = FoodToPrep::template_patch() . 'single-meal.php';
            }

            return $single_template;
        }

        function filter_category_template( $template ) {

            if (is_tax($this->category)) {
                $template = FoodToPrep::template_patch() . 'page-meal-list.php';
            }

            return $template;
        }
    }

    new CPT_MP_Meal();

endif;
