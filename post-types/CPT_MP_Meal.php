<?php

defined('ABSPATH') || exit;

if (!class_exists('CPT_MP_Meal')) :

    /**
     *
     * Global variable
     *
     */
    $taxonomy = 'category';
    $meal_post_type = 'meal';

    class CPT_MP_Meal
    {
        private $post_type;

        function __construct()
        {
            $this->post_type = 'meal';

            add_action('init', array($this, 'meal_init'), 0);
            add_filter('post_updated_messages', array($this, 'meal_updated_messages'));
            add_action('init', array($this, 'meal_category_inti'), 0);
            add_action('init', array($this, 'meal_category_change_category_update_count_cb'), 100);

            add_filter("manage_{$this->post_type}_posts_columns", array($this, 'set_custom_edit_meal_columns'));

            add_action("manage_{$this->post_type}_posts_custom_column", array($this, 'custom_meal_column'), 10, 2);

            add_action('admin_init', array($this, 'metabox_init'));
            add_action('save_post', array($this, 'save_meal_cpt_meta'));

            add_filter( 'single_template', array($this, 'get_custom_post_type_template') );

        }




        /**
         * Registers the `meal` post type.
         */
        function meal_init()
        {
            global $meal_post_type;
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
                'menu_icon' => 'dashicons-admin-post',
                'show_in_rest' => true,
                'rest_base' => 'meal',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
            ));

        }

        /**
         * Sets the post updated messages for the `meal` post type.
         *
         * @param array $messages Post updated messages.
         * @return array Messages for the `meal` post type.
         */
        function meal_updated_messages($messages)
        {
            global $post;

            $permalink = get_permalink($post);

            $messages['meal'] = array(
                0 => '', // Unused. Messages start at index 1.
                /* translators: %s: post permalink */
                1 => sprintf(__('Meal updated. <a target="_blank" href="%s">View Meal</a>', 'food-to-prep'), esc_url($permalink)),
                2 => __('Custom field updated.', 'food-to-prep'),
                3 => __('Custom field deleted.', 'food-to-prep'),
                4 => __('Meal updated.', 'food-to-prep'),
                /* translators: %s: date and time of the revision */
                5 => isset($_GET['revision']) ? sprintf(__('Meal restored to revision from %s', 'food-to-prep'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
                /* translators: %s: post permalink */
                6 => sprintf(__('Meal published. <a href="%s">View Meal</a>', 'food-to-prep'), esc_url($permalink)),
                7 => __('Meal saved.', 'food-to-prep'),
                /* translators: %s: post permalink */
                8 => sprintf(__('Meal submitted. <a target="_blank" href="%s">Preview Meal</a>', 'food-to-prep'), esc_url(add_query_arg('preview', 'true', $permalink))),
                /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
                9 => sprintf(__('Meal scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Meal</a>', 'food-to-prep'),
                    date_i18n(__('M j, Y @ G:i', 'food-to-prep'), strtotime($post->post_date)), esc_url($permalink)),
                /* translators: %s: post permalink */
                10 => sprintf(__('Meal draft updated. <a target="_blank" href="%s">Preview Meal</a>', 'food-to-prep'), esc_url(add_query_arg('preview', 'true', $permalink))),
            );

            return $messages;
        }

        /**
         *
         * Register Category of 'meal' post type
         *
         */
        function meal_category_inti()
        {
            global $taxonomy, $meal_post_type;

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
                'public' => true,
                'rewrite' => true,
                'publicly_queryable' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_in_rest' => true,
                'update_count_callback' => array($this, 'meal_category_update_count_cb')
            ));
        }

        /**
         * Custom update_count_callback
         * @param array $terms
         * @param string $taxonomy
         */
        function meal_category_update_count_cb($terms = array(), $taxonomy = 'category')
        {
            global $wpdb, $taxonomy;

            // select id & count from taxonomy
            $query = "SELECT term_taxonomy_id, MAX(total) AS total FROM ((
        SELECT tt.term_taxonomy_id, COUNT(*) AS total FROM $wpdb->term_relationships tr, $wpdb->term_taxonomy tt WHERE tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = %s GROUP BY tt.term_taxonomy_id
        ) UNION ALL (
        SELECT term_taxonomy_id, 0 AS total FROM $wpdb->term_taxonomy WHERE taxonomy = %s
        )) AS unioncount GROUP BY term_taxonomy_id";
            $rsCount = $wpdb->get_results($wpdb->prepare($query, $taxonomy, $taxonomy));
            // update all count values from taxonomy
            foreach ($rsCount as $rowCount) {
                $wpdb->update($wpdb->term_taxonomy, array('count' => $rowCount->total), array('term_taxonomy_id' => $rowCount->term_taxonomy_id));
            }
        }

        /**
         * Change default update_count_callback for category taxonomy
         * @action init
         */
        function meal_category_change_category_update_count_cb()
        {
            global $wp_taxonomies, $taxonomy;

            if ($taxonomy == 'category') {
                if (!taxonomy_exists('category')) {
                    return false;
                }

                $new_arg = &$wp_taxonomies['category']->update_count_callback;
                $this->meal_category_update_count_cb($new_arg);
            }
        }

        /**
         *
         * Register metabox
         *
         */

        function metabox_init()
        {
            global $meal_post_type;
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
    }

    new CPT_MP_Meal();

endif;
